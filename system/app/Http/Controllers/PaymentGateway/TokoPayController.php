<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TokoPayController extends Controller
{
    protected $url = 'https://api.tokopay.id/v1';
    
    public function __construct()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->MerchantID = $api->tokopay_merchantid;
        $this->SecretKey = $api->tokopay_secretkey;
        $this->wa_admin = $api->nomor_admin;
        $this->logoHeader = ENV("APP_LOGOFAVICON");
        $this->ex_hours = $api->expired_invoice_hours;
        $this->ex_minutes = $api->expired_invoice_minutes;
    }

    public function requestPay($harga, $order_id, $nomor, $method, $email, $returnURL, $kodeProduk)
    {
        
        $date_now = Carbon::now()->format('Y-m-d H:i:s');
        $expired = date('Y-m-d H:i:s', strtotime('+'.$this->ex_hours.' Hours +'.$this->ex_minutes.' minutes', strtotime($date_now)));
        $formatTanggal = Carbon::parse($expired)->format('Y-m-d H:i:s');
        
        $body = [
            'merchant_id' => $this->MerchantID,
            'kode_channel' => $method,
            'reff_id' => $order_id,
            'amount' => $harga,
            'customer_name' => ENV("APP_NAME"),
            'customer_email' => $email,
            'customer_phone' => $nomor,
            'redirect_url' => $returnURL,
            'expired_ts' => Carbon::parse($formatTanggal)->timestamp,
            'signature'=> md5($this->MerchantID.":".$this->SecretKey.":".$order_id),
            'items'=> [
                [
                    'product_code'=> $kodeProduk,
                    'name'=> 'Pembayaran ' . $method . ' ' . $order_id,
                    'price'=> $harga,
                    'product_url'=> "".ENV("APP_URL")."",
                    'image_url'=> "".ENV("APP_URL").'/'.$this->logoHeader."",
                ],
            ]
        ]; 
        $response = $this->connect('/order', $body);
        if(isset($response->data)){
            $data = $response->data;
            if(empty($data->ovo_push)){
                if(empty($data->nomor_va)){
                    if(empty($data->checkout_url)){
                        $paymentNumber = $data->qr_string;
                    }else{
                        $paymentNumber = $data->checkout_url;
                    }
                }else{
                    $paymentNumber = $data->nomor_va;
                }
            } else {
                $paymentNumber = $data->ovo_push;
            }
            return array('success' => true, 'amount' => $data->total_bayar, 'no_pembayaran' => $paymentNumber, 'reference' => $data->trx_id, 'checkout_url' => $data->pay_url);
        } else {
            return array('success' => false,'msg' => $response->error_msg);
        }
    }
    
    public function checkaccount()
    {
        $body = [
            'merchant_id' => $this->MerchantID,
            'signature'=> md5($this->MerchantID.$this->SecretKey)
        ];
        $response = $this->connect('/merchant', $body);
        if($response->status == 1){
            return array('success' => true,'nama_toko' => $response->data->nama_toko, 'saldo_tersedia' => $response->data->saldo_tersedia, 'saldo_tertahan' => $response->data->saldo_tertahan);
        } else {
            return array('success' => false,'msg' => $response->error_msg);
        }
    }
    
    public function withdraw($nominal)
    {
        $body = [
            'nominal' => $nominal,
            'merchant_id' => $this->MerchantID,
            'signature'=> md5($this->MerchantID.$this->SecretKey)
        ];
        $response = $this->connect('/tarik-saldo', $body);
        if($response->status == 1){
            return array('success' => true,'msg' => $response->message);
        } else {
            return array('success' => false,'msg' => $response->error_msg);
        }
    }

    public function connect($endPoint, $body = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.$endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($body),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response;
    }
}
