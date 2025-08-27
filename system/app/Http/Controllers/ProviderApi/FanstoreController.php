<?php
namespace App\Http\Controllers\ProviderApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Formater;

class FanstoreController extends Controller
{
    private $base = 'https://fanstore.web.id/api/v2';
    private $key;
    
    public function __construct() {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->key = $api->fanstore_token;
    }
    
    public function order($target = null, $service = null, $quantity = null)
    {
        $data = $this->connect($this->base,array(
            'action' => 'add',
            'service' => $service,
            'link' => $target,
            'quantity' => $quantity,
            'key' => $this->key
        ));
        
        if(isset($data['error'])) {
            return ['result' => false,'data' => null,'message' => $data['error']];
        } else {
            return ['result' => true,'data' => ['trxid' => $data['order']],'message' => 'Order Data successfully obtained.'];
        }
    }

    public function status($id = null)
    {
        $data = $this->connect($this->base,array(
            'action' => 'status',
            'order' => $id,
            'key' => $this->key
        ));
        
        if(isset($data['error'])) {
            return ['result' => false,'data' => null,'message' => $data['error']];
        } else {
            return ['result' => true,'data' => ['trxid' => $id,'start_count' => $data['start_count'],'status' => $data['status'],'remains' => $data['remains']],'message' => 'Status Order successfully obtained.'];
        }
    }
    
    public function refill($id = null)
    {
        $data = $this->connect($this->base,array(
            'action' => 'refill',
            'order' => $id,
            'key' => $this->key
        ));
        
        if(isset($data['error'])) {
            return ['result' => false,'data' => null,'message' => $data['error']];
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
        
        if(isset($data['error'])) {
            return ['result' => false,'data' => null,'message' => $data['error']];
        } else {
            return ['result' => true,'data' => ['status' => $data['status']],'message' => 'Status Refill successfully obtained.'];
        }
    }
    
    public function service()
    {
        $formatter = new Formater;
        $data = $this->connect($this->base,array(
            'action' => 'services',
            'key' => $this->key
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
                'status' => $data[$i]['service'] ? 'available' : 'empty',
                'category' => $data[$i]['category'],
            ];
        }
        return ['result' => true,'data' => $out,'message' => 'Service Data successfully obtained.'];
    }
    
    public function CheckBalance()
    {
        $formatter = new Formater;
        $data = $this->connect($this->base,array(
            'action' => 'balance',
            'key' => $this->key
        ));

        
        if(isset($data['error'])) {
            return ['result' => false,'data' => null,'message' => $data['error']];
        } else {
            return ['result' => true,'data' => ['balance' => $data['balance']],'message' => 'Balance successfully obtained.'];
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
