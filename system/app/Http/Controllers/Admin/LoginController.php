<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Berita;
use App\Models\LogUser;
use App\Models\User;
use App\Helpers\Formater;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;
use Session;

class LoginController extends Controller
{
    public function create()
    {
        return view('components.admin.login', [
        'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
        'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        'pay_method' => \App\Models\Method::all()
        ]);
    }
    

    public function store(Request $request)
    {
        $wa = new WhatsappController;
        $formatter = new Formater;
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $credential = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $response = $request->input('g-recaptcha-response');
        $secret = env('CAPTCHA_SECRET');
        
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query(['secret' => $secret, 'response' => $response]));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        curl_close($verify);
         
        $status = json_decode($response);
        if(!$status->success){
            return back()->withInput($request->only('username'))->withErrors(['g-recaptcha-response' => 'Captcha invalid!']);
        }
        
        if(count(User::where('username', $request->username)->where('status','Suspend')->get()) != 0) {
            return back()->with('error', 'Akun ditangguhkan, harap hubungi Admin.');
        } else if(count(User::where('username', $request->username)->where('status','Not Active')->get()) != 0) {
            Session::put('username',$request->username);
            return view('components.verifyotp-register', [
                'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
                'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
                'pay_method' => \App\Models\Method::all()
            ]);
        } else {
            if(count(User::where('username', $request->username)->where('role','Admin')->get()) != 0) {
                $params = [
                    'key' => $api->mystic_key,
                    'sign' => md5($api->mystic_id . $api->mystic_key),
                    'ip' => $formatter->client_ip()
                ];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://mystic-validasi.com/v1/validasi-ip",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                  CURLOPT_POSTFIELDS => http_build_query($params),
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                  ),
                ));
                $response = json_decode(curl_exec($curl), true);
                curl_close($curl);
                //dd($response);
                if($response['status'] == false) return back()->with('error', $response['message']);
                $client_ip = $formatter->client_ip();
                $client_iploc = $formatter->client_iploc($client_ip);
                $browser = $formatter->devices();
                $users = User::where('username', $request->username)->where('role','Admin')->select('no_wa')->first();
                    $tgl = date('Y-m-d H:i:s');
                    $pesan = "*$request->username* Baru Saja Melakukan *Login*, dengan detail berikut:\n\n".
                        "- IP : $client_ip\n".
                        "- Lokasi : $client_iploc\n".
                        "- Tanggal : $tgl\n".
                        "- Browser : $browser";
                $wa->send($users->no_wa,$pesan);
                
                $log = new LogUser();
                $log->user = $request->username;
                $log->type = 'login';
                $log->text = 'IP : '.$client_ip.' Telah Login';
                $log->ip = $client_ip;
                $log->loc = $client_iploc;
                $log->ua = $browser;
                $log->save();
                
                if (Auth::attempt($credential)) {
                    
                    $request->session()->regenerate();
                    
                    return redirect()->intended('dashboard')->with('success', 'You have Successfully logged in');
                }
            } else {
                if (Auth::attempt($credential)) {
                    
                    $request->session()->regenerate();
                    
                    return redirect()->intended('dashboard')->with('success', 'You have Successfully logged in');
                }
            }
        }

        return back()->with('error', 'Tidak ada kecocokan data yang anda masukkan dengan Database kami!');
    }

    public function destroy(Request $request)
    {
        $wa = new WhatsappController;
        $formatter = new Formater;
        $username = Auth::user()->username;
        if(count(User::where('username', $username)->where('role','Admin')->get()) != 0) {
            $client_ip = $formatter->client_ip();
            $client_iploc = $formatter->client_iploc($client_ip);
            $browser = $formatter->devices();
            
            $users = User::where('username', $username)->where('role','Admin')->select('no_wa')->first();
            $tgl = date('Y-m-d H:i:s');
            $pesan = "*$username* Baru Saja Melakukan *Logout*, dengan detail berikut:\n\n".
                    "- IP : $client_ip\n".
                    "- Lokasi : $client_iploc\n".
                    "- Tanggal : $tgl\n".
                    "- Browser : $browser";
            $wa->send($users->no_wa,$pesan);
            
            $log = new LogUser();
            $log->user = $username;
            $log->type = 'logout';
            $log->text = 'IP : '.$client_ip.' Telah Logout';
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
            
            Auth::logout();
    
            $request->session()->invalidate();
    
            $request->session()->regenerateToken();
    
            return redirect('/')->with('success', 'You have Successfully exited');
        } else {
            Auth::logout();
    
            $request->session()->invalidate();
    
            $request->session()->regenerateToken();
    
            return redirect('/')->with('success', 'You have Successfully exited');
        }
    }    
}
