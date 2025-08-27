<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TriPayController extends Controller
{
    public function request($idOrder, $jumlah, $method, $dataUser, $nohp)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $date_now = Carbon::now()->format('Y-m-d H:i:s');
        $expired = date('Y-m-d H:i:s', strtotime('+'.$api->expired_invoice_hours.' Hours +'.$api->expired_invoice_minutes.' minutes', strtotime($date_now)));
        $formatTanggal = Carbon::parse($expired)->format('Y-m-d H:i:s');
        $data = [
            'method'         => $method,
            'merchant_ref'   => $idOrder,
            'amount'         => $jumlah,
            'customer_name'  => env("APP_NAME"),
            'customer_email' => $dataUser,
            'customer_phone' => $nohp,
            'order_items'    => [
                [
                    'name'        => 'Pembayaran ' . $method . ' ' . $idOrder,
                    'price'       => $jumlah,
                    'quantity'    => 1,
                ]
            ],
            'return_url'   => env("APP_URL").'/pembelian/invoice/'.$idOrder,
            'expired_time' => Carbon::parse($formatTanggal)->timestamp, // 3 jam
            'signature'    => hash_hmac('sha256', $api->tripay_merchant_code . $idOrder . $jumlah, $api->tripay_private_key)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('TriPay request cURL error: ' . $error);
            return ['success' => false, 'msg' => 'Payment gateway request failed: ' . $error];
        }

        if($response->success == true){
            if(empty($response->data->pay_code)){
                if(empty($response->data->pay_url)){
                    $paymentNumber = $response->data->qr_string;
                }else{
                    $paymentNumber = $response->data->pay_url;
                }
            }else{
                $paymentNumber = $response->data->pay_code;
            }
            return array('success' => true, 'amount' => $response->data->amount, 'no_pembayaran' => $paymentNumber, 'reference' => $response->data->reference, 'checkout_url' => $response->data->qr_url == null ? $response->data->checkout_url : $response->data->qr_url);
        }else{
            $err = strtolower($response->message);
            $msg = '';
            if(str_contains($err, 'minimum')) { 
                $pch = explode('rp',$err);
                $msg = 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp '.$pch[1].' ';
            }elseif(str_contains($err, 'maximum')){
                $pch = explode('rp',$err);
                $msg = 'Maksimal jumlah pembayaran untuk metode pembayaran ini adalah Rp '.$pch[1].' ';
            }elseif(str_contains($err, 'Invalid amount. amount must be an integer')){
                $msg = 'Jumlah tidak valid. Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp 1.000 ';
            }else{
                //$msg = 'Metode pembayaran ini sedang tidak dapat digunakan';
                $msg = $response->message;
            }     
             return array('success' => false,'msg' => $msg);
        }
    }

    public function fee($jumlah, $code)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $payload = [
            'code'    => $code,
            'amount'    => $jumlah
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/merchant/fee-calculator?' . http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            Log::error('TriPay fee cURL error: ' . $error);
            throw new \Exception('Unable to calculate fee via payment gateway: ' . $error);
        }

        return $response->data['0']->total_fee->customer + $response->data['0']->total_fee->merchant;
    }

    public function channel()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            Log::error('TriPay channel cURL error: ' . $error);
            throw new \Exception('Unable to retrieve payment channels: ' . $error);
        }

        return $response;
    }

    public function detail($reference)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/transaction/detail?reference=' . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_private_key],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            Log::error('TriPay detail cURL error: ' . $error);
            throw new \Exception('Unable to retrieve transaction detail: ' . $error);
        }

        return $response;
    }
}
