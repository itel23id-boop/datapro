<?php
namespace App\Http\Controllers\ProviderApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IrvankedesmmController extends Controller
{
    private $base = 'https://irvankedesmm.co.id/api';
    private $key;
    private $uid;
    
    public function __construct() {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->key = $api->irvankede_key;
        $this->uid = $api->irvankede_api;
    }
    
    public function order($target = null, $service = null, $quantity = null, $custom_comments = null, $usernames = null, $hashtag = null, $username = null, $media = null, $answer_number= null)
    {
        $data = $this->connect($this->base.'/order',array(
            'api_id' => $this->uid,
            'api_key' => $this->key,
            'service' => $service,
            'target' => $target,
            'quantity' => $quantity,
            'comments' => $custom_comments,
            'usernames' => $usernames,
            'hashtag' => $hashtag,
            'username' => $username,
            'media' => $media,
            'answer_number' => $answer_number,
        ));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['msg']];
        } else {
            return ['result' => true,'data' => ['trxid' => $data['data']['id']],'message' => 'Order Data successfully obtained.'];
        }
    }

    public function status($id = null)
    {
        $data = $this->connect($this->base.'/status',array(
            'api_id' => $this->uid,
            'api_key' => $this->key,
            'id' => $id
        ));
        
        if($data['status'] == true) {
            return ['result' => true,'data' => ['trxid' => $data['data']['id'],'start_count' => $data['data']['start_count'],'status' => $data['data']['status'],'remains' => $data['data']['remains'],'note' => $data['msg']],'message' => 'Status Order successfully obtained.'];
        } else {
            return ['result' => false,'data' => null,'message' => $data['msg']];
        }
    }
    
    public function refill($id = null)
    {
        $data = $this->connect($this->base.'/refill',array(
            'api_id' => $this->uid,
            'api_key' => $this->key,
            'id' => $id
        ));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['msg']];
        } else {
            return ['result' => true,'data' => ['id_refill' => $data['data']['refill']],'message' => 'Refill Order successfully obtained.'];
        }
    }
    public function status_refill($id = null)
    {
        $data = $this->connect($this->base.'/refill/status',array(
            'api_id' => $this->uid,
            'api_key' => $this->key,
            'id' => $id
        ));
        
        if($data['status'] == true) {
            return ['result' => true,'data' => ['status' => $data['data']['refill_status']],'message' => 'Status Refill successfully obtained.'];
        } else {
            return ['result' => false,'data' => null,'message' => $data['msg']];
        }
    }
    
    public function service()
    {
        $data = $this->connect($this->base.'/services',array(
            'api_id' => $this->uid,
            'api_key' => $this->key
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
        $data = $this->connect($this->base.'/profile',array(
            'api_id' => $this->uid,
            'api_key' => $this->key
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
				$_post[] = $name.'='.urlencode($value);
			}
		}
		$ch = curl_init($end_point);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
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
