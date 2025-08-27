<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;

class SettingWebController extends Controller
{
    public function settingWeb()
    {
        return view('components.admin.settingweb',['web' => DB::table('setting_webs')->where('id',1)->first()]);
    }
    
    public function saveSettingWeb(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'judul_web' => 'required',
            'deskripsi_web' => 'required',
            'alamat_web' => 'required',
            'logo_header' => 'image:mimes:jpg,jpeg,png',
            'logo_footer' => 'image:mimes:jpg,jpeg,png',
            'logo_favicon' => 'image:mimes:jpg,jpeg,png',
            'url_wa' => 'required',
            'url_ig' => 'required',
            'url_tiktok' => 'required',
            'url_youtube' => 'required',
            'url_fb' => 'required',
            'url_telegram' => 'required',
            'email_admin' => 'required',
        ]);
        
        if($request->file('logo_header')){
            $file = $request->file('logo_header');
            $folder = 'assets/logo';
            $file->move($folder, $file->getClientOriginalName());      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_header' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        
        if($request->file('logo_footer')){
            $file2 = $request->file('logo_footer');
            $folder2 = 'assets/logo';
            $file2->move($folder2, $file2->getClientOriginalName());      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_footer' => "/".$folder2."/".$file2->getClientOriginalName()
            ]);
        }
        
        if($request->file('logo_favicon')){
            $file3 = $request->file('logo_favicon');
            $folder3 = 'assets/logo';
            $file3->move($folder3, $file3->getClientOriginalName());      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_favicon' => "/".$folder3."/".$file3->getClientOriginalName()
            ]);
        }
        
        DB::table('setting_webs')->where('id',1)->update([
           
           'judul_web' => $request->judul_web,
           'deskripsi_web' => $request->deskripsi_web,
           'alamat_web' => $request->alamat_web,
           'url_wa' => $request->url_wa,
           'url_ig' => $request->url_ig,
           'url_tiktok' => $request->url_tiktok,
           'url_youtube' => $request->url_youtube,
           'url_fb' => $request->url_fb,
           'url_telegram' => $request->url_telegram,
           'email_admin' => $request->email_admin,
           'created_at' => now(),
           'updated_at' => now()
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi website';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi website!');
        
        
    }
    
    public function saveSettingWarna(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'warna1' => $request->warna1,
          'warna2' => $request->warna2,
          'warna3' => $request->warna3,
          'warna4' => $request->warna4,
          'warna5' => $request->warna5,
          'warna6' => $request->warna6,
          'warna7' => $request->warna7,
          'warna8' => $request->warna8,
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Warna';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Warna!');
        
    }
    
    public function saveSettingMystic(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'mystic_id' => $request->mystic_id,
          'mystic_key' => $request->mystic_key
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Mystic CEKID';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        
        return back()->with('success','Berhasil konfigurasi Mystic CEKID!');
        
    }
    
    public function saveSettingLinkQu(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
            'linkqu_status' => $request->linkqu_status,
            'linkqu_username' => $request->linkqu_username,
            'linkqu_password' => $request->linkqu_password,
            'linkqu_clientID' => $request->linkqu_clientID,
            'linkqu_clientSecret' => $request->linkqu_clientSecret,
            'linkqu_signaturekey' => $request->linkqu_signaturekey
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi LINKQU';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi LINKQU!');
        
    }
    
    public function saveSettingXendit(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
          'xendit_status' => $request->xendit_status,
          'xendit_authkey' => $request->xendit_authkey,
          'xendit_tokenkey' => $request->xendit_tokenkey
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi XENDIT';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi XENDIT!');
        
    }
    
    public function saveSettingIpaymu(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
          'ipaymu_status' => $request->ipaymu_status,
          'ipaymu_va' => $request->ipaymu_va,
          'ipaymu_key' => $request->ipaymu_key
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi IPAYMU';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi IPAYMU!');
        
    }
    
    public function saveSettingTokoPay(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
          'tokopay_status' => $request->tokopay_status,
          'tokopay_merchantid' => $request->tokopay_merchantid,
          'tokopay_secretkey' => $request->tokopay_secretkey
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi IPAYMU';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi TokoPay!');
    }
    
    public function saveSettingDuitku(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
          'duitku_status' => $request->duitku_status,
          'duitku_merchant' => $request->duitku_merchant,
          'duitku_apikey' => $request->duitku_apikey
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi DUITKU';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi DUITKU!');
        
    }
    
    public function saveHargaMembership(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'harga_gold' => $request->harga_gold,
          'harga_platinum' => $request->harga_platinum,
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Harga Membership';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Harga Membership!');
        
    }
    
    
    public function saveSettingTripay(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
          'tripay_status' => $request->tripay_status,
          'tripay_api' => $request->tripay_api,
          'tripay_merchant_code' => $request->tripay_merchant_code,
          'tripay_private_key' => $request->tripay_private_key
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Tripay';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Tripay!');
        
    }
    
    public function saveSettingPartaisocmedSaldo(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $partaisocmed = new PartaisocmedController;
        $json = $partaisocmed->CheckBalance();
        
        if($json['result'] == true) {
            DB::table('setting_webs')->where('id',1)->update([
              'partaisosmed_saldo' => $json['data']['balance'],
            ]);
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan Sync Saldo PartaiSocmed';
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success','Berhasil Sync Saldo PartaiSocmed!');
        } else {
            return back()->with('error',$json['message']);
        }
    }
    
    public function saveSettingIrvankedesmmSaldo(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $irvankedesmm = new IrvankedesmmController;
        $json = $irvankedesmm->CheckBalance();
        
        if($json['result'] == true) {
            DB::table('setting_webs')->where('id',1)->update([
               
              'irvankede_saldo' => $json['data']['balance'],
                
            ]);
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan Sync Saldo IrvanKedeSMM';
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success','Berhasil Sync Saldo IrvanKedeSMM!');
        } else {
            return back()->with('error',$json['message']);
        }
        
    }
    
    public function saveSettingVipmemberSaldo(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $vipmember = new VipMemberController;
        $json = $vipmember->CheckBalance();
        
        if($json['result'] == true) {
            DB::table('setting_webs')->where('id',1)->update([
               
              'vipmember_saldo' => $json['data']['balance'],
                
            ]);
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan Sync Saldo VIP-MEMBER';
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success','Berhasil Sync Saldo VIP-MEMBER!');
        } else {
            return back()->with('error',$json['message']);
        }
    }
    
    public function saveSettingIstanamarketSaldo(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $istanamarket = new IstanaMarketController;
        $json = $istanamarket->CheckBalance();
        
        if($json['result'] == true) {
            DB::table('setting_webs')->where('id',1)->update([
               
              'istanamarket_saldo' => $json['data']['balance'],
                
            ]);
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan Sync Saldo ISTANA MARKET';
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success','Berhasil Sync Saldo ISTANA MARKET!');
        } else {
            return back()->with('error',$json['message']);
        }
    }
    
    public function saveSettingFanstoreSaldo(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $fanstore = new FanstoreController;
        $json = $fanstore->CheckBalance();
        
        if($json['result'] == true) {
            DB::table('setting_webs')->where('id',1)->update([
               
              'fanstore_saldo' => $json['data']['balance'],
                
            ]);
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan Sync Saldo FAN STORE';
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success','Berhasil Sync Saldo FAN STORE!');
        } else {
            return back()->with('error',$json['message']);
        }
    }
    
    public function saveSettingRasxmediaSaldo(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $rasxmedia = new RasxmediaController;
        $json = $rasxmedia->CheckBalance();
        
        if($json['result'] == true) {
            DB::table('setting_webs')->where('id',1)->update([
               
              'rasxmedia_saldo' => $json['data']['balance'],
                
            ]);
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan Sync Saldo RASXMEDIA';
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success','Berhasil Sync Saldo RASXMEDIA!');
        } else {
            return back()->with('error',$json['message']);
        }
    }
    
    public function saveSettingPartaisocmed(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'partaisosmed_status' => $request->partaisosmed_status,
          'partaisosmed_key' => $request->partaisosmed_key,
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi PartaiSocmed';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi PartaiSocmed!!');
        
    }
    
    public function saveSettingirvankedesmm(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'irvankede_status' => $request->irvankede_status,
          'irvankede_api' => $request->irvankede_api,
          'irvankede_key' => $request->irvankede_key,
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Irvankedesmm';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Irvankedesmm!!');
        
    }
    
    public function saveSettingVipmember(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'vipmember_status' => $request->vipmember_status,
          'vipmember_key' => $request->vipmember_key,
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi VIP-MEMBER';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi VIP-MEMBER!!');
        
    }
    
    public function saveSettingIstanamarket(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'istanamarket_status' => $request->istanamarket_status,
          'istanamarket_api' => $request->istanamarket_api,
          'istanamarket_key' => $request->istanamarket_key
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Istana Market';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Istana Market!!');
        
    }
    
    public function saveSettingFanstore(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'fanstore_status' => $request->fanstore_status,
          'fanstore_token' => $request->fanstore_token
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi FAN STORE';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi FAN STORE!!');
        
    }
    
    public function saveSettingRasxmedia(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'rasxmedia_status' => $request->rasxmedia_status,
          'rasxmedia_key' => $request->rasxmedia_key
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi RASXMEDIA';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi RASXMEDIA!!');
        
    }
    
    public function saveSettingWagateway(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'nomor_admin' => $request->nomor_admin,
          'wa_key' => $request->wa_key,
          //'wa_number' => $request->wa_number
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi WA gateway';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi WA gateway!');
        
    }
    public function saveSettingMembership(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'harga_upgrade_reseller' => $request->harga_upgrade_reseller,
          'harga_upgrade_vip' => $request->harga_upgrade_vip
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Membership';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Membership!');
        
    }

    public function saveSettingChangeTheme(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'change_theme' => $request->change_theme
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Change Theme';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Change Theme!');
        
    }
    public function saveSettingPaydisini(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'paydisini_status' => $request->paydisini_status,
          'paydisini_key' => $request->paydisini_key,
          'paydisini_merchant' => $request->paydisini_merchant
            
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi PAYDISINI';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi PAYDISINI!');
        
    }
    public function saveSettingLainnya(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        DB::table('setting_webs')->where('id',1)->update([
           
            'limit_order' => $request->limit_order,
            'text_limit_order' => $request->text_limit_order,
            'expired_invoice_hours' => $request->expired_invoice_hours,
            'expired_invoice_minutes' => $request->expired_invoice_minutes,
            'snk' => $request->snk,
            'privacy' => $request->privacy
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan konfigurasi Lainnya';
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
        return back()->with('success','Berhasil konfigurasi Lainnya!');
        
    }
}