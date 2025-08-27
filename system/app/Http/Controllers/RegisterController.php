<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Berita;
use App\Models\LogUser;
use Session;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;
use App\Helpers\Formater;

class RegisterController extends Controller
{
    public function create()
    {
        return view('components.register',[
        'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
        'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        'phone_country' => \DB::table('phone_countrys')->get(),
        'pay_method' => \App\Models\Method::all()
        ]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $wa = new WhatsappController;
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|min:3|unique:users,username|max:255',
            'password' => 'required|min:6|max:255',
            'no_wa' => 'required',
        ], [
            'nama.required' => 'Harap isi kolom nama!',
            'username.required' => 'Harap isi kolom username!',
            'username.min' => 'Panjang username minimal 3 huruf',
            'username.unique' => 'Username telah digunakan',
            'username.max' => 'Panjang username maximal 255 huruf',
            'password' => 'Harap isi kolom password',
            'password.min' => 'Panjang password minimal 6 huruf',
            'password.max' => 'Panjang password maximal 255 huruf',
        ]);
        $phone = preg_replace("/[^0-9]/", '', $request->no_wa);
        if(count(User::where('no_wa', $phone)->get()) == 0 || count(User::where('email', $request->email)->get()) == 0) {
            $otp = $formatter->random_number(6);
            
            $response = $request->input('g-recaptcha-response');
            $secret = env('CAPTCHA_SECRET');
            
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query(['secret' => $secret, 'response' => $response]));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
             
            $status = json_decode($response);
            if(!$status->success){
                return back()->withInput($request->only('username'))->withErrors(['g-recaptcha-response' => 'Captcha invalid!']);
            }
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_wa = $phone;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->balance = 0;
            $user->role = 'Member';
            $user->status = 'Not Active';
            $user->otp = $formatter->base64('auto',$otp);
            $user->save();
            
            Session::put('username',$request->username);
            
            $wa->send($phone, 'Masukan Kode Verifikasi (OTP) : '.$otp.''); 
            Mail::to($request->email)->send(new SendOTP([
                'otp' => $otp
            ]));
            return view('components.verifyotp-register', [
                'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
                'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
                'pay_method' => \App\Models\Method::all()
            ]);
        } else {
            return back()->with('error', 'Whatsapp number or Email has been registered!');
        }
    }
    
    public function verifyOtp(Request $request)
    {
        
        $username = Session::get('username');
        
        $formatter = new Formater;
        
        if(!$username){
            return redirect('/register');
        }
        
        $otp = '';
        foreach($request->otp as $kode){
            
            $otp .= $kode;
            
        }
        
        
        $cek = \App\Models\User::where('username',$username)->where('otp',$formatter->base64('auto',$otp))->first();
        
        if(!$cek){
            Session::get('username');
            return back()->with('error','Invalid OTP code!');
        }
        
        \App\Models\User::where('username',$username)->where('otp',$formatter->base64('auto',$otp))->update(['otp' => NULL,'status' => 'Active']);
        
        
        Auth::login($cek);
        
        return redirect()->intended('dashboard')->with('success', 'You have Successfully Registered');
    }
}
