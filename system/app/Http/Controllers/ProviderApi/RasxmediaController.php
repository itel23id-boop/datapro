<?php
namespace App\Http\Controllers\ProviderApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RasxmediaController extends Controller
{
    private $base = 'https://rasxmedia.com/api/v2';
    private $key;
    private $uid;
    
    public function __construct() {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->key = $api->rasxmedia_key;
    }
    
    public function order($target = null, $service = null, $quantity = null, $custom_comments = null)
    {
        $data = $this->connect($this->base,array(
            'key' => $this->key,
            'action' => 'add',
            'service' => $service,
            'link' => $target,
            'quantity' => $quantity,
            'comments' => $custom_comments
        ));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['data']];
        } else {
            return ['result' => true,'data' => ['trxid' => $data['order']],'message' => 'Order Data successfully obtained.'];
        }
    }

    public function status($id = null)
    {
        $data = $this->connect($this->base,array(
            'key' => $this->key,
            'action' => 'status',
            'order' => $id
        ));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['data']];
        } else {
            return ['result' => true,'data' => ['trxid' => $id,'start_count' => $data['start_count'],'status' => $data['status'],'remains' => $data['remains']],'message' => 'Status Order successfully obtained.'];
        }
    }
    
    public function refill($id = null)
    {
        $data = $this->connect($this->base,array(
            'key' => $this->key,
            'action' => 'refill',
            'order' => $id
        ));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['data']];
        } else {
            return ['result' => true,'data' => ['id_refill' => $data['refill']],'message' => 'Refill Order successfully obtained.'];
        }
    }
    public function status_refill($id = null)
    {
        $data = $this->connect($this->base,array(
            'key' => $this->key,
            'action' => 'refill_status',
            'refill' => $id
        ));
        
        if($data['status'] == false) {
            return ['result' => false,'data' => null,'message' => $data['data']];
        } else {
            return ['result' => true,'data' => ['status' => $data['status']],'message' => 'Status Refill successfully obtained.'];
        }
    }
    
    public function service()
    {
        $data = $this->connect($this->base,array(
            'key' => $this->key,
            'action' => 'services',
        ));
        
        for($i = 0; $i <= count($data)-1; $i++) {
            $out[] = [
                'name' => ucwords($data[$i]['name']),
                'note' => $data[$i]['description'],
                'id' => $data[$i]['service'],
                'type' => $data[$i]['type'],
                'min' => $data[$i]['min'],
                'max' => $data[$i]['max'],
                'price' => $data[$i]['rate'],
                'status' => $data[$i]['status'] == 1 ? 'available' : 'empty',
                'category' => $data[$i]['category'],
            ];
        }
        return ['result' => true,'data' => $out,'message' => 'Service Data successfully obtained.'];
    }
    
    public function CheckBalance()
    {
        $data = $this->connect($this->base,array(
            'key' => $this->key,
            'action' => 'balance',
        ));
        
        if($data['status'] == true) {
            return ['result' => true,'data' => ['balance' => round($data['balance'])],'message' => 'Balance successfully obtained.'];
        } else {
            return ['result' => false,'data' => null,'message' => $data['data']];
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
