<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\LogUser;
use App\Helpers\Formater;

class MemberController extends Controller
{
    public function create()
    {
        return view('components.admin.member', ['users' => User::orderBy('created_at', 'desc')->orderBy('created_at', 'desc')->get(),'total_balance' => User::sum('balance'), 'banyak_user' => User::where('role', '!=', 'Admin')->count(), 'banyak_admin' => User::where('role','Admin')->count()]);
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        return back()->with('success', 'Berhasil menghapus pengguna');
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'username' => 'required|min:3|unique:users,username|max:255',
            'password' => 'required|min:6|max:255',
            'role' => 'required'
        ], [
            'nama.required' => 'Harap isi kolom nama!',
            'username.required' => 'Harap isi kolom username!',
            'username.min' => 'Panjang username minimal 3 huruf',
            'username.unique' => 'Username telah digunakan',
            'username.max' => 'Panjang username maximal 255 huruf',
            'password' => 'Harap isi kolom password',
            'password.min' => 'Panjang password minimal 6 huruf',
            'passowrd.max' => 'Panjang password maximal 255 huruf'
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'balance' => 0,
            'role' => $request->role
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan penambahan Pengguna '.$request->username;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil menambah pengguna '.$request->username);
    }

    public function send(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'username' => 'required',
            'balance' => 'required|numeric|min:1'
        ], [
            'balance.min' => 'Balance minimal 1'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) return back()->with('error', 'Username tidak ditemukan');

        $user->update([
            'balance'  => $user->balance + $request->balance
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan kirim saldo Rp.'.number_format($request->balance).' Pengguna '.$request->username;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil menambahkan saldo');
    }

    public function show($id)
    {
        $data = User::where('id', $id)->first();

        $send = "
                <form action='" . route("member.detail.update",[$id]) . "' method='POST'>
                    <input type='hidden' name='_token' value='" . csrf_token() . "'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Username</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->username . "' readonly>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->name . "' name='name'>
                        </div>
                    </div>   
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Email</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->email . "' name='email'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>No. Whatsapp</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->no_wa . "' name='no_wa'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Balance</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='" . $data->balance . "' name='balance'>
                        </div>
                    </div>    
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Role</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='role'>
                                <option value='$data->role'>".ucwords($data->role)." (Selected)</option>
                                <option value='Member'>Member</option>
                                <option value='Reseller'>Reseller</option>
                                <option value='VIP'>VIP</option>
                                <option value='Admin'>Admin</option>
                            </select>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Status</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='status'>
                                <option value='$data->status'>".ucwords($data->status)." (Selected)</option>
                                <option value='Active'>Active</option>
                                <option value='Not Active'>Not Active</option>
                                <option value='Suspend'>Suspend</option>
                            </select>
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
        
        $data = User::where('id', $id)->first();
        
        $request->validate([
            'name' => 'required',
            'no_wa' => 'required',
            'balance' => 'required|numeric|min:0',
            'role' => 'required',
            'status' => 'required'
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
            'balance' => $request->balance,
            'role' => $request->role,
            'status' => $request->status
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update Pengguna Sebelum : ('.$data->name.', '.$data->email.', '.$data->no_wa.', '.$data->balance.', '.$data->role.' , '.$data->status.') Sesudah : ('.$request->name.', '.$request->email.', '.$request->no_wa.', '.$request->balance.', '.$request->role.', '.$request->status.')';
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil update user '.$data->username);
    }
}
