<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LogUser;
use App\Helpers\Formater;

class PhoneCountryController extends Controller
{
    public function create()
    {
        return view('components.admin.phone-country', ['data' => \DB::table('phone_countrys')->orderBy('created_at', 'ASC')->get()]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'dial_code' => 'required'
        ]);
        
        \DB::table('phone_countrys')->insert([
            'name' => $request->name,
            'code' => strtolower($request->code),
            'dial_code' => '+'.$request->dial_code,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Phone Country '.$request->name;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan Phone Country '.$request->name);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = \DB::table('phone_countrys')->where('id',$id)->first();
        
        \DB::table('phone_countrys')->where('id',$id)->delete();
            
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Phone Country '.$data->name;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
            
        return back()->with('success', 'Berhasil hapus Phone Country '.$data->name);
    }

public function detail($id)
    {
        $data = \DB::table('phone_countrys')->where('id',$id)->first();
        
        $send = "
                <form action='".route("phone-country.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Name</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->name. "' name='name'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Code Name</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->code. "' name='code'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Dial Code</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='" . $data->dial_code. "' name='dial_code'>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
                        <button type='submit' class='btn btn-primary'>Simpan</button>
                    </div>
                </form>
        ";

        return $send;        
    }  
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        \DB::table('phone_countrys')->update([
            'name' => $request->name,
            'code' => $request->code,
            'dial_code' => $request->dial_code
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan update Phone Country '.$request->nama;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
           
        return back()->with('success', 'Berhasil update Phone Country '.$request->nama);        
    }        
}
