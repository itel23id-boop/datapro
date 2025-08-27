<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Helpers\Formater;
use Session;
use Http;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;
use App\Http\Controllers\WhatsappController;

class ForgotPasswordController extends Controller
{
    
    public function forgotPassword()
    {
        return view('components.forgotpassword', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    
    public function sendOtp(Request $request)
    {
        $request->validate([
            'users' => 'required'
        ]);
        
        $wa = new WhatsappController;
        $formatter = new Formater;
        
        $cek = \App\Models\User::where('no_wa',$request->users)->orwhere('username',$request->users)->orwhere('email',$request->users)->first();
        
        if(!$cek){
            
            return back()->with('error','Akun tidak ditemukan!');
            
        }
        $otp = $formatter->random_number(6);
        
        \App\Models\User::where('no_wa',$request->users)->orwhere('username',$request->users)->update(['otp' => $formatter->base64('auto',$otp)]);
        
        Session::put('users',$request->users);
        
        $wa->send($cek->no_wa, 'Masukan Kode Verifikasi (OTP) : '.$otp.''); 
        Mail::to($cek->email)->send(new SendOTP([
            'otp' => $otp
        ]));
        return view('components.verifyotp', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function verifyOtp(Request $request)
    {
        
        $users = Session::get('users');
        
        $formatter = new Formater;
        
        if(!$users){
            
            return redirect('/forgot-password');
            
        }
        
        $otp = '';
        foreach($request->otp as $kode){
            
            $otp .= $kode;
            
        }
        
        
        $cek = \App\Models\User::where('no_wa',$users)->orwhere('username',$users)->where('otp',$formatter->base64('auto',$otp))->first();
        
        if(!$cek){
            
            return back()->with('error','Kode OTP tidak valid!');
            
        }
        
        \App\Models\User::where('no_wa',$users)->orwhere('username',$users)->where('otp',$formatter->base64('auto',$otp))->update(['otp' => NULL]);
        
        
        Auth::login($cek);
        
        
        return redirect('/user/settings');
    }
    
}