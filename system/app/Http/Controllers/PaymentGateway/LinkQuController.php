<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LinkQuController extends Controller
{
    protected $url = 'https://gateway.linkqu.id';
    
    public function __construct()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->username = $api->linkqu_username;
        $this->password = $api->linkqu_password;
        $this->clientID = $api->linkqu_clientID;
        $this->clientSecret = $api->linkqu_clientSecret;
        $this->signatureKey = $api->linkqu_signaturekey;
        $this->ex_hours = $api->expired_invoice_hours;
        $this->ex_minutes = $api->expired_invoice_minutes;
    }

    public function requestPayment($tipe, $amount, $reffid, $custid, $phone, $usermail, $codeBank)
    {
        $headers = array(
            'client-id: '.$this->clientID,
            'client-secret: ' .$this->clientSecret
        );
        $regex = '/[^0-9a-zA-Z]/';
        $now = date("Ymd");
        //$date = substr(date('YmdHis', strtotime($now. ' + 1 days')), 0, 13);
        $date = date('YmdHis', strtotime($now. '+'.$this->ex_hours.' Hours +'.$this->ex_minutes.' minutes'));
        if($tipe == 'virtual-account') { 
            $path = '/transaction/create/va';
            $method = 'POST';
            $cust_name = 'PAY '.env("APP_NAME");
            $secondvalue = strtolower(preg_replace($regex, "",$amount.$date.$codeBank.$reffid.$custid.$cust_name.$usermail.$this->clientID));
            $signToString = $path.$method.$secondvalue;
            $sign = hash_hmac('sha256', $signToString, $this->signatureKey);
            $data = '{
                "amount" : '.$amount.',
            	"partner_reff" : "'.$reffid.'",
            	"customer_id" : "'.$custid.'",
            	"customer_name" : "'.$cust_name.'",
            	"expired" : "'.$date.'",
            	"username" : "'.$this->username.'",
            	"pin" : "'.$this->password.'",
            	"customer_phone" : "'.$phone.'",
            	"customer_email" : "'.$usermail.'",
                "bank_code" : "'.$codeBank.'",
                "remark" : "'.env("APP_NAME").'",
                "signature" : "'.$sign.'",
                "url_callback" : "'.env("APP_URL").'/callback_linkqu'.'"
            }';
            $response = $this->connect('/linkqu-partner/transaction/create/va', 'POST', $data, $headers);
            if($response->response_code == 00){
                return array('success' => true, 'amount' => $response->amount, 'no_pembayaran' => $response->virtual_account, 'reference' => $response->partner_reff, 'checkout_url' => null);
            } else {
                return array('success' => false,'msg' => $response->response_desc);
            }
        } else if($tipe == 'e-walet') {
            $path = '/transaction/create/paymentewallet';
            $method = 'POST';
            $cust_name = 'GPI '.env("APP_NAME");
            $secondvalue = strtolower(preg_replace($regex, "",$amount.$date.$codeBank.$reffid.$custid.$cust_name.$usermail.$phone.$this->clientID));
            $signToString = $path.$method.$secondvalue;
            $sign = hash_hmac('sha256', $signToString, $this->signatureKey);
            $data = '{
                "amount" : '.$amount.',
            	"partner_reff" : "'.$reffid.'",
            	"customer_id" : "'.$custid.'",
            	"customer_name" : "'.$cust_name.'",
            	"expired" : "'.$date.'",
            	"username" : "'.$this->username.'",
            	"pin" : "'.$this->password.'",
                "retail_code" : "'.$codeBank.'",
            	"customer_phone" : "'.$phone.'",
            	"customer_email" : "'.$usermail.'",
                "ewallet_phone" : "'.$phone.'",
                "bill_title" : "Payment Order '.$reffid.'",
                "signature" : "'.$sign.'",
                "url_callback" : "'.env("APP_URL").'/callback_linkqu'.'"
            }';
            $response = $this->connect('/linkqu-partner/transaction/create/paymentewallet', 'POST', $data, $headers);
            if($response->response_code == 00){
                return array('success' => true, 'amount' => $response->amount, 'no_pembayaran' => $response->payment_code, 'reference' => $response->partner_reff, 'checkout_url' => $response->url_payment);
            } else {
                return array('success' => false,'msg' => $response->response_desc);
            }
        } else if($tipe == 'convenience-store') {
            $path = '/transaction/create/retail';
            $method = 'POST';
            $cust_name = 'GPR '.env("APP_NAME");
            $secondvalue = strtolower(preg_replace($regex, "",$amount.$date.$codeBank.$reffid.$custid.$cust_name.$usermail.$this->clientID));
            $signToString = $path.$method.$secondvalue;
            $sign = hash_hmac('sha256', $signToString, $this->signatureKey);
            $data = '{
                "amount" : '.$amount.',
            	"partner_reff" : "'.$reffid.'",
            	"customer_id" : "'.$custid.'",
            	"customer_name" : "'.$cust_name.'",
            	"expired" : "'.$date.'",
            	"username" : "'.$this->username.'",
            	"pin" : "'.$this->password.'",
                "retail_code" : "'.$codeBank.'",
            	"customer_phone" : "'.$phone.'",
            	"customer_email" : "'.$usermail.'",
                "remark" : "Pembayaran '.$reffid.'",
                "signature" : "'.$sign.'",
                "url_callback" : "'.env("APP_URL").'/callback_linkqu'.'"
            }';
            $response = $this->connect('/linkqu-partner/transaction/create/retail', 'POST', $data, $headers);
            if($response->response_code == 00){
                return array('success' => true, 'amount' => $response->amount, 'no_pembayaran' => $response->payment_code, 'reference' => $response->partner_reff, 'checkout_url' => null);
            } else {
                return array('success' => false,'msg' => $response->response_desc);
            }
        } else if($tipe == 'qris') {
            $path = '/transaction/create/qris';
            $method = 'POST';
            $cust_name = 'GPQ '.env("APP_NAME");
            $secondvalue = strtolower(preg_replace($regex, "",$amount.$date.$reffid.$custid.$cust_name.$usermail.$this->clientID));
            $signToString = $path.$method.$secondvalue;
            $sign = hash_hmac('sha256', $signToString, $this->signatureKey);
            $data = '{
                "amount" : '.$amount.',
            	"partner_reff" : "'.$reffid.'",
            	"customer_id" : "'.$custid.'",
            	"customer_name" : "'.$cust_name.'",
            	"expired" : "'.$date.'",
            	"username" : "'.$this->username.'",
            	"pin" : "'.$this->password.'",
            	"customer_phone" : "'.$phone.'",
            	"customer_email" : "'.$usermail.'",
                "signature" : "'.$sign.'",
                "url_callback" : "'.env("APP_URL").'/callback_linkqu'.'"
            }';
            $response = $this->connect('/linkqu-partner/transaction/create/qris', 'POST', $data, $headers);
            if($response->response_code == 00){
                return array('success' => true, 'amount' => $response->amount, 'no_pembayaran' => $response->qris_text, 'reference' => $response->partner_reff, 'checkout_url' => null);
            } else {
                return array('success' => false,'msg' => $response->response_desc);
            }
        }
    }

    public function checkTransaction($transactionId)
    {
        $headers = array(
            'client-id: '.$this->clientID,
            'client-secret: ' .$this->clientSecret
        );

        return $this->connect('/linkqu-partner/transaction/payment/checkstatus?username='.$this->username.'&partnerreff='.$transactionId, 'GET', null, $headers);
    }
    
    public function channel_ewallet()
    {
        $headers = array(
            'client-id: '.$this->clientID,
            'client-secret: ' .$this->clientSecret
        );
        return $this->connect('/linkqu-partner/data/emoney?username='.$this->username, 'GET', null, $headers);
    }
    public function channel_va()
    {
        $headers = array(
            'client-id: '.$this->clientID,
            'client-secret: ' .$this->clientSecret
        );
        return $this->connect('/linkqu-partner/masterbank/list', 'GET', null, $headers);
    }

    public function connect($endPoint, $methodurl = 'POST', $body = null, $headers)
    {
        $curl = curl_init();
        if($methodurl == 'POST') {
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->url.$endPoint,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => ''.$body.'',
              CURLOPT_HTTPHEADER => $headers,
            ));
        } else {
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->url.$endPoint,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => $headers,
            ));
        }
        $ret = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            Log::error('LinkQu API error: ' . $err);
            return $err;
        } else {
            return json_decode($ret);
        }
    }
}
