<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Method;
use Auth;
use App\Models\LogUser;
use App\Helpers\Formater;
use Illuminate\Support\Carbon;

class DsController extends Controller
{
    public function dashboard()
    {
        return view('components.user.dashboarduser',[
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function orders(Request $request)
    {
        if($request->from AND $request->to) {

            $start = Carbon::parse($request->from);
            $end = Carbon::parse($request->to);
            $Pembelian = Pembelian::where('username', Auth::user()->username)->whereDate('created_at','<=',$end->format('y-m-d'))->whereDate('created_at','>=',$start->format('y-m-d'))->limit(5)->get();
            return view('components.user.pembelianuser',[
                'from' => $request->from,
                'to' => $request->to,
                'data' => $Pembelian,
                'pay_method' => \App\Models\Method::all()
            ]);
        } else {
            return view('components.user.pembelianuser',[
                'from' => date('m/d/Y'),
                'to' => date('m/d/Y'),
                'data' => Pembelian::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->limit(5)->get(),
                'pay_method' => \App\Models\Method::all()
            ]);
        }
    }
    
    public function topup()
    {
        return view('components.user.topupuser',[
            'data' => Pembelian::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->first(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function riwayat()
    {
        return view('components.user.riwayattopup',[
            'data' => Pembayaran::where('username', Auth::user()->username)->where('type', 'deposit')->orderBy('created_at', 'desc')->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function riwayat_upgrade()
    {
        return view('components.user.riwayatupgrade',[
            'data' => Pembayaran::where('username', Auth::user()->username)->where('type', 'upgrade')->orderBy('created_at', 'desc')->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function settings()
    {
        return view('components.user.settingsuser',[
        'data' => User::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->first(),
        'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function saveEditProfile(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable|min:6|max:255',
        ], [
            'name.required' => 'Harap isi kolom nama!',
            'password.min' => 'Panjang password minimal 6 huruf',
            'password.max' => 'Panjang password maximal 255 huruf',
        ]);

        $data = [
          'name' => $request->name,
          'email' => $request->email
        ];
        
        if(!empty($request->password)){
            
            $data['password'] = bcrypt($request->password);
            
        }
        
        \App\Models\User::where('id',Auth()->user()->id)->update($data);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'user';
        $log->text = 'IP : '.$client_ip.' Melakukan mengedit profile '.$request->username;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return redirect()->back()->with('success', 'Berhasil mengedit profile!');

    }
}