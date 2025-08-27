<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class duitKuController extends Controller 
{
    protected $url = 'https://passport.duitku.com/webapi/api/merchant';
    protected $url_sandbox = 'https://sandbox.duitku.com/webapi/api/merchant';
    public function __construct()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->merchant = $api->duitku_merchant;
        $this->api_key = $api->duitku_apikey;
    }
    public function requestPayment($idOrder, $jumlah, $method, $dataUser, $nohp, $returnURL) {
        $body = array(
            'merchantCode' => $this->merchant,
            'paymentAmount' => $jumlah,
            'merchantOrderId' => $idOrder,
            'productDetails' => 'Pembayaran ' . $method . ' ' . $idOrder,
            'email' => $dataUser,
            'paymentMethod' => $method,
            'customerVaName' => env("APP_NAME"),
            'phoneNumber' => $nohp,
            'returnUrl' => $returnURL,
            'callbackUrl' => env("APP_URL").'/callback_duitku',
            'signature' => md5($this->merchant . $idOrder . $jumlah . $this->api_key)
        );
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($body))
        );
        //$response =  $this->connect('/v2/inquiry', json_encode($body), $headers);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/v2/inquiry'); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $request = curl_exec($ch);
        curl_close($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpCode == 200){
            $response = json_decode($request);
            if(empty($response->vaNumber) AND empty($response->qrString)) {
                $paymentNumber = null;
            } else {
                if(empty($response->vaNumber)){
                    $paymentNumber = $response->qrString;
                }else{
                    $paymentNumber = $response->vaNumber;
                }
            }
            if(empty($response->amount)) {
                $amount = $jumlah;
            } else {
                $amount = $response->amount;
            }
            if(empty($response->reference)) {
                $reference = $idOrder;
            } else {
                $reference = $response->reference;
            }
            return array('success' => true, 'amount' => $amount, 'no_pembayaran' => $paymentNumber, 'reference' => $reference, 'checkout_url' => $response->paymentUrl);
        } else {
            $result = json_decode($request);
            $msg = $result->Message;
            return array('success' => false,'msg' => $msg);
        }
    }
    
    public function checkTransaction($transactionId) {
        $sign = md5($this->merchant . $transactionId . $this->api_key);
        $body = array(
            'merchantCode' => $this->merchant,
            'merchantOrderId' => $transactionId,
            'signature' => $sign
        );
        
        $headers = array(
            'Content-Type: application/json',
            //'Content-Length: ' . strlen(json_encode($body))
        );
        $response = $this->connect('/transactionStatus', json_encode($body), $headers);
        if($response->statusMessage == "SUCCESS"){
            return array('success' => true, 'data' => array('reference' => $response->reference, 'amount' => $response->amount, 'fee' => $response->fee));
        } else {
            $msg = $response->Message;
            return array('success' => false,'msg' => $msg);
        }
    }
    public function channel($jumlah) {
        $datetime = date('Y-m-d H:i:s');
        $body = array(
            'merchantcode' => $this->merchant,
            'amount' => $jumlah,
            'datetime' => $datetime,
            'signature' => hash('sha256',$this->merchant . $jumlah . $datetime . $this->api_key)
        );
        $headers = array(
            'Content-Type: application/json',
            //'Content-Length: ' . strlen($body)
        );
        $response =  $this->connect('/paymentmethod/getpaymentmethod', json_encode($body), $headers);
        if($response->responseMessage == "SUCCESS"){
            return $response;
        } else {
            $msg = $response->Message;
            return array('success' => false,'msg' => $msg);
        }
    }
    
    public function connect($endPoint, $body, $headers)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->url_sandbox . $endPoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $body,
          CURLOPT_HTTPHEADER => $headers,
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }
}
