<?php
namespace App\Helpers;
use DiDom\Document;
use Illuminate\Support\Carbon;
class Formater {
    public function status($x) {
        $y = strtolower($x);
        if(in_array($y,['failed','gagal','error','partial','rejected','canceled'])) $str = 'Failed';
        if(in_array($y,['refund','refunded'])) $str = 'Refund';
        if(in_array($y,['pending'])) $str = 'Pending';
        if(in_array($y,['processing','proses','process','in progress'])) $str = 'Processing';
        if(in_array($y,['success','sukses','berhasil','completed'])) $str = 'Success';
        return (!isset($str)) ? 'Pending' : $str;
    }
    public function random($length) {
        $str = '';
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    public function random_number($length) {
        $str = ""; $characters = array_merge(range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    public function validate_date($date) {
    	$d = Carbon::createFromFormat('Y-m-d', $date);
    	$v = $d && $d->format('Y-m-d') == $date;
    	return ($v == true) ? true : false;
    }
    public function countDay($start,$end) {
        $diff = date_diff(date_create($start),date_create($end));
        return str_replace('+','',$diff->format("%R%a"));
    }
    
    public function filter_phone($type,$number) {
        $phone = preg_replace("/[^0-9]/", '', $number);
        if($type == '0') {
            if(substr($phone,0,3) == '+62'){ $change = '0'.substr($phone,3); }
            else if(substr($phone, 0, 2) == '62'){ $change = '0'.substr($phone,2); }
            else if(substr($phone, 0, 1) == '0') { $change = $phone; }
        } else {
            if(substr($phone,0,3) == '+62'){ $change = substr($phone,1); }
            else if(substr($phone, 0, 2) == '62'){ $change = $phone; }
            else if(substr($phone, 0, 1) == '0') { $change = '62'.substr($phone,1); }
        }
        return $change;
    }
    public function devices() {
        global $_SERVER;
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$ua)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($ua,0,4))){
            return 'mobile';
        } else {
            return 'desktop';
        }
    }
    public function base64($type,$data) {
        if($type == 'encode') return base64_encode($data);
        if($type == 'decode') return base64_decode($data);
        if($type == 'check') return (base64_encode(base64_decode($data)) == $data) ? (!trim(stripslashes(strip_tags(htmlspecialchars(base64_decode($data),ENT_QUOTES))))) ? false : true : false;
        if($type == 'auto') return (base64_encode(base64_decode($data)) == $data) ? (!trim(stripslashes(strip_tags(htmlspecialchars(base64_decode($data),ENT_QUOTES))))) ? base64_encode($data) : $data : base64_encode($data);
        if(!in_array($type,['encode','decode','check','auto'])) return false;
    }
    public function client_ip() {
        if(getenv('HTTP_CLIENT_IP')) { $str = getenv('HTTP_CLIENT_IP'); } 
        else if(getenv('HTTP_X_FORWARDED_FOR')) { $str = getenv('HTTP_X_FORWARDED_FOR'); } 
        else if(getenv('HTTP_X_FORWARDED')) { $str = getenv('HTTP_X_FORWARDED'); } 
        else if(getenv('HTTP_FORWARDED_FOR')) { $str = getenv('HTTP_FORWARDED_FOR'); } 
        else if(getenv('HTTP_FORWARDED')) { $str = getenv('HTTP_FORWARDED'); } 
        else if(getenv('REMOTE_ADDR')) { $str = getenv('REMOTE_ADDR'); } 
        else { $str = 'Unknown'; }
        return explode(',',$str)[0];
    }
    public function client_iploc($ip) {
        $get1 = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
        if($get1 === false) {
            $get2 = unserialize(file_get_contents("http://ip-api.com/php/$ip")); // limit 150/minutes
            if($get2 === false) {
                $ref = 'Unknown';
            } else {
                $get2u = $get2;
                if(isset($get2u['countryCode'])) {
                    $ref = ($get1u['city'] == '') ? $get1u['countryCode'] : $get1u['city'].' '.$get1u['countryCode'];
                    if(!$ref) $ref = 'Unknown';
                } else {
                    $ref = 'Unknown';
                }
            }
        } else {
            $get1u = $get1;
            if(isset($get1u['geoplugin_countryCode'])) {
                $ref = ($get1u['geoplugin_city'] == '') ? $get1u['geoplugin_countryCode'] : $get1u['geoplugin_city'].' '.$get1u['geoplugin_countryCode'];
                if(!$ref) $ref = 'Unknown';
            } else {
                $ref = 'Unknown';
            }
        }
        return $ref;
        /*return 'Unknown';*/
    }
    public function check_ip($ip,$wl) {
        return (in_array($ip,explode(',',$wl))) ? true : false;
    }
    // KURS DOLLAR TO RUPIAH
    public function kurs($qty)
	{
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.exchangerate-api.com/v4/latest/USD',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $calculate = ($response['rates']['IDR'] / $response['rates']['USD']) * $qty;
		return round($calculate);
	}
}