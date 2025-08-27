<?php
namespace App\Http\Controllers\ProviderApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiCheckController extends Controller
{
    public function check($user_id = null, $zone_id = null, $game = null) {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $params = [
            'key' => $api->mystic_key,
            'sign' => md5($api->mystic_id . $api->mystic_key),
            'type' => 'game',
            'code'    => $game,
            'user_id' => $user_id,
            'zone_id' => $zone_id
        ];
            
        $result = $this->connect('https://mystic-validasi.com/v1/check-game',$params);
        if($result['status'] == true){
            return array('status' => true,'data' => array('userNameGame' => $result['data']['username'], 'message' => $result['message']));
        }else{
            return array('status' => false, 'message' => $result['message']);
        }
    }
    public function check_bank($user_id = null, $kode_bank = null) {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $params = [
            'key' => $api->mystic_key,
            'sign' => md5($api->mystic_id . $api->mystic_key),
            'type' => 'validation',
            'bank_code' => $kode_bank,
            'number' => $user_id
        ];
            
        $result = $this->connect('https://mystic-validasi.com/v1/bank',$params);
        if($result['status'] == true){
            return array('status' => true,'data' => array('userNameGame' => $result['data']['Name'], 'message' => $result['message']));
        }else{
            return array('status' => false, 'message' => $result['message']);
        }
    }
    public function zoneid($game = null) {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $params = [
            'key' => $api->mystic_key,
            'sign' => md5($api->mystic_id . $api->mystic_key),
            'type' => 'zoneid',
            'code'    => $game
        ];
            
        $result = $this->connect('https://mystic-validasi.com/v1/check-game',$params);
        if($result['status'] == true){
            return array('status' => true,'kode' => $game,'data' => $result['data']);
        }else{
            return array('status' => false, 'message' => $result['message']);
        }
    }

    public function connect($endpoint,$data = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
          CURLOPT_POSTFIELDS => http_build_query($data),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}
