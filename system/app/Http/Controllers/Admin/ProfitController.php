<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profit;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;

class ProfitController extends Controller
{
    public function create()
    {
        return view('components.admin.profit', ['datas' => Profit::orderBy('id')->orderBy('id')->get()]);
    }
    
    public function detail($id)
    {
        $data = Profit::where('id', $id)->first();
        
        $send = "
                <form action='".route("profit.detail.update", [$id])."' method='POST'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Provider</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='". $data->provider ."' readonly>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Percent</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='percent'>
                                <option value='". $data->percent ."'>". $data->percent ." (Selected)</option>
                                <option value='+'>+</option>
                                <option value='%'>%</option>
                            </select>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Profit</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='". $data->profit ."' name='profit'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Profit Member</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='". $data->profit_member ."' name='profit_member'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Profit Reseller</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='". $data->profit_reseller ."' name='profit_reseller'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Profit VIP</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='". $data->profit_vip ."' name='profit_vip'>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
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
        $data = Profit::where('id', $id)->select('provider')->first();
        
        Profit::where('id', $id)->update([
            'percent' => $request->percent,
            'profit' => $request->profit,
            'profit_member' => $request->profit_member,
            'profit_reseller' => $request->profit_reseller,
            'profit_vip' => $request->profit_vip
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update profit '.$data->provider;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil update profit');        
    }
}
