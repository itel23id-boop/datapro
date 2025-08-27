<?php
namespace App\Http\Controllers\ProviderApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VipMemberController extends Controller
{
    private $base = 'https://vip-member.id/api/json';
    private $key;
    private $uid;
    
    public function __construct() {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->key = $api->vipmember_key;
    }
    
    public function order($target = null, $service = null, $quantity = null, $username = null, $answer_number = null, $comments = null)
    {
        $body = array(
            'service' => $service,
            'data' => $target,
            'quantity' => $quantity,
            'username' => $username, // MELAKUKAN PEMESANAN INSTAGRAM TOP COMMENTS LIKES
            'answer_number' => $answer_number, // MELAKUKAN PEMESANAN TELEGRAM POLL VOTES
            'comments' => $comments, // MELAKUKAN PEMESANAN CUSTOM COMMENTS
        );
        
        $data = $this->connect($this->base,array_merge(array('api_key' => $this->key,'action' => 'order'), $body));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['msg']];
        } else {
            return ['result' => true,'data' => ['trxid' => $data['data']['order_id']],'message' => 'Order Data successfully obtained.'];
        }
    }

    public function status($id = null)
    {
        $data = $this->connect($this->base,array(
            'api_key' => $this->key,
            'action' => 'status',
            'id' => $id
        ));
        
        if($data['status'] == true) {
            return ['result' => true,'data' => ['trxid' => $id,'start_count' => $data['data']['start_count'],'status' => $data['data']['status'],'remains' => $data['data']['remains_count']],'message' => 'Status Order successfully obtained.'];
        } else {
            return ['result' => false,'data' => null,'message' => $data['data']['msg']];
        }
    }
    
    public function service()
    {
        $data = $this->connect($this->base,array(
            'api_key' => $this->key,
            'action' => 'services'
        ));
        
        for($i = 0; $i <= count($data['data'])-1; $i++) {
            $out[] = [
                'name' => ucwords($data['data'][$i]['name']),
                'note' => $data['data'][$i]['note'],
                'id' => $data['data'][$i]['id'],
                'type' => $data['data'][$i]['type'],
                'min' => $data['data'][$i]['min'],
                'max' => $data['data'][$i]['max'],
                'price' => $data['data'][$i]['price'],
                'status' => $data['data'][$i]['status'] == 1 ? 'available' : 'empty',
                'category' => $data['data'][$i]['category'],
            ];
        }
        return ['result' => true,'data' => $out,'message' => 'Service Data successfully obtained.'];
    }
    
    public function CheckBalance()
    {
        $data = $this->connect($this->base,array(
            'api_key' => $this->key,
            'action' => 'balance'
        ));
        
        if($data['status'] == true) {
            return ['result' => true,'data' => ['balance' => round($data['data']['balance'])],'message' => 'Balance successfully obtained.'];
        } else {
            return ['result' => false,'data' => null,'message' => $data['msg']];
        }
    }
    
    private function connect($end_point, $post) {
        $_post = Array();
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }
        $ch = curl_init($end_point);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return json_decode($result, true);
    }
}
