<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Http\Controllers\WhatsappController;
use DB;
use Auth;
use Str;
use App\Models\LogUser;
use App\Helpers\Formater;




class OrderManualController extends Controller
{
    public function orderManual()
    {
        $data = \App\Models\Pembelian::orderBy('pembelians.id', 'ASC')->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembelians.*', 'pembayarans.status AS status_pembayaran', 'pembayarans.no_pembeli AS no_pembeli_pembayaran', 'metode')->where('pembayarans.provider','manual')->get();
        $data_otomatis = \App\Models\Pembelian::orderBy('pembelians.id', 'ASC')->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembelians.*', 'pembayarans.status AS status_pembayaran', 'pembayarans.no_pembeli AS no_pembeli_pembayaran', 'metode')->where('pembelians.status','Failed')->get();
        $data_manual = \App\Models\Pembelian::orderBy('pembelians.id', 'ASC')->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembelians.*', 'pembayarans.status AS status_pembayaran', 'pembayarans.no_pembeli AS no_pembeli_pembayaran', 'metode')->get();
        $kategori = \App\Models\Kategori::where('tipe','!=','joki')->get();
        return view('components.admin.ordermanual',compact('kategori','data','data_otomatis','data_manual'));
    }
    
    public function ajaxLayanan(Request $request)
    {
        $layanan = \App\Models\Layanan::where('kategori_id',$request->data)->get();
        $res = '';
        foreach($layanan as $l){ 
            $res .= '<option value="'.$l->id.'">'.$l->layanan.' ( Rp '.number_format($l->harga,0,',','.').' )</option> ';
        }
        
        return $res;
        
    }
    
    public function detail($id)
    {
        $data = Pembelian::where('order_id', $id)->first();
        $layanan = \App\Models\Layanan::where('id',$data->id_layanan)->first();
        $kategori = \App\Models\Kategori::where('id',$layanan->kategori_id)->first();
        $pembayaran = \App\Models\Pembayaran::where('order_id',$id)->first();
        
        $zone = $data->zone != null ? "-" . $data->zone : "";

        if ($data->status == "Pending") {
            $label_pesanan = 'warning';
        } else if ($data->status == "Processing") {
            $label_pesanan = 'info';
        } else if ($data->status == "Success") {
            $label_pesanan = 'success';
        } else {
            $label_pesanan = 'danger';
        }
        
        if ($pembayaran->status == "Menunggu Pembayaran") {
            $label_pesananpembayaran = 'warning';
        } else if ($pembayaran->status == "Lunas") {
            $label_pesananpembayaran = 'success';
        } else {
            $label_pesananpembayaran = 'danger';
        }

        $send = '
            <form action="'.route("pesanan.detail.update", [$id]).'" method="POST">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="kategori_id" value="'.$layanan->kategori_id.'">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>OID</th>
                                <td><input type="text" class="form-control" value="'. $data->order_id . '" name="uid" readonly></td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td><input type="text" class="form-control" value="'. $kategori->nama . '" name="kategori" readonly></td>
                            </tr>
                            <tr>
                                <th>Layanan</th>
                                <td><input type="text" class="form-control" value="'. $data->layanan . '" name="layanan" readonly></td>
                            </tr>
                            <tr>
                                <th>Target</th>
                                <td><input type="text" class="form-control" value="'. $data->user_id . '" name="uid" readonly></td>
                            </tr>
                            <tr>
                                <th>Jumlah/Quantity</th>
                                <td><input type="text" class="form-control" value="'. $data->quantity . '" name="quantity" readonly></td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td>Rp. ' . number_format($data->harga, 0, ',', '.') . '</td>
                            </tr>
                            <tr>
                                <th>Status Transaksi</th>
                                <td><span class="badge bg-' . $label_pesanan . '">' . $data->status . '</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td><span class="badge bg-' . $label_pesananpembayaran . '">' . $pembayaran->status . '</td>
                            </tr>
                            <tr>
                                <th>Tanggal transaksi</th>
                                <td>' . $data->created_at . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>';

        return $send;
    }
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'uid' => 'required',
            'kategori' => 'required',
            'layanan' => 'required',
            'quantity' => 'required'
        ]);
        
        $pembelians = \App\Models\Pembelian::where('order_id', $id)->first();
        $pembayaran = Pembayaran::where('order_id', $id)->where('status', 'Menunggu Pembayaran')->first();
        
        if(count(Pembayaran::where('order_id', $id)->where('status', 'Menunggu Pembayaran')->get()) == 0) {
            return back()->with('error', 'Tidak ditemukan pembayaran dengan status Menunggu Pembayaran');
        } else {
            if($pembelians->username == null) {
                $UserCheck = null;
                $role = null;
            } else {
                $user = \App\Models\User::where('username', $pembelians->username)->first();
                $UserCheck = true;
                $role = $user->role;
            }
            
            if($UserCheck == true){
                if($role == "Member"){
                    $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider')->first();
                }else if($role == "Reseller" || $role == "Admin"){
                    $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan','harga_reseller AS harga','kategori_id', 'provider_id', 'provider')->first();
                }            
            }else{
                $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider')->first();
            }
            
            
            $kategori = \App\Models\Kategori::where('id', $dataLayanan->kategori_id)->select('kode','nama')->first();
    
            $phone = $formatter->filter_phone('62',$pembelians->nomor);
            $wa = new WhatsappController;
            
            $api = \DB::table('setting_webs')->where('id',1)->first();
            
            if($dataLayanan->provider == "partaisocmed"){
                $partaisocmed = new PartaisocmedController;
                $order = $partaisocmed->order($pembelians->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->keywords, $pembelians->custom_comments, $pembelians->usernames, $pembelians->hashtag, $pembelians->media, $pembelians->answer_number,
                            $pembelians->minimal, $pembelians->maximal, $pembelians->post, $pembelians->old_post, $pembelians->delay, $pembelians->expiry);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "irvankedesmm"){
                $irvankedesmm = new IrvankedesmmController;
                $order = $irvankedesmm->order($pembelians->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->custom_comments, $pembelians->usernames, $pembelians->hashtag, $pembelians->media, $pembelians->answer_number);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "vipmember"){
                $vipmember = new VipMemberController;
                $order = $vipmember->order($request->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->usernames, $pembelians->answer_number, $pembelians->custom_comments);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "istanamarket"){
                $istanamarket = new IstanaMarketController;
                $order = $istanamarket->order($request->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->custom_comments);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "fanstore"){
                $fanstore = new FanstoreController;
                $order = $fanstore->order($request->user_id, $dataLayanan->provider_id, $pembelians->quantity);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "rasxmedia"){
                $rasxmedia = new RasxmediaController;
                $order = $rasxmedia->order($request->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->custom_comments);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "manual"){
                $order['transactionId'] = '';
                $order['status'] = true; 
            }
            if($order['status']){
                    $pesanSukses = 
                        "PESANAN KAMU BERHASIL DIBUAT\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : $request->uid*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n".
                        "❃ ➤ Status : *Lunas*\n".
                        "❃ ➤ Nomor Invoice : *$id*\n\n".
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : $api->nomor_admin\n".
                        "Online 24 Jam";
        
                    $pesanSuksesAdmin = 
                        "PESANAN KAMU BERHASIL DIBUAT\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : $request->uid*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n".
                        "❃ ➤ Status : *Lunas*\n".
                        "❃ ➤ Nomor Invoice : *$id*\n\n".
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : $api->nomor_admin\n".
                        "Online 24 Jam";
    
                $wa->send($phone, $pesanSukses);
                $wa->send($formatter->filter_phone('62',$api->nomor_admin), $pesanSuksesAdmin);
                
                Pembayaran::where('order_id', $id)->update(['status' => 'Lunas']);
                    
                Pembelian::where('order_id', $id)->update(['provider_order_id' => $order['transactionId'] ? $order['transactionId'] : null, 'status' => 'Pending', 'log' => json_encode($order)]);
                
                $log = new LogUser();
                $log->user = Auth::user()->username;
                $log->type = 'system';
                $log->text = 'IP : '.$client_ip.' Melakukan update transaksi manual #'.$id;
                $log->ip = $client_ip;
                $log->loc = $client_iploc;
                $log->ua = $browser;
                $log->save();
                
                return back()->with('success','Pesanan Manual #'.$id.' berhasil!');
            }else{
                return back()->with('error',isset($msg) ? $msg : 'Terjadi kesalahan');
            }
        }
    }
    
    public function detail_pending($id)
    {
        $data = Pembelian::where('order_id', $id)->first();
        $layanan = \App\Models\Layanan::where('id',$data->id_layanan)->first();
        $kategori = \App\Models\Kategori::where('id',$layanan->kategori_id)->first();
        $pembayaran = \App\Models\Pembayaran::where('order_id',$id)->first();
        
        $zone = $data->zone != null ? "-" . $data->zone : "";

        if ($data->status == "Pending") {
            $label_pesanan = 'warning';
        } else if ($data->status == "Processing") {
            $label_pesanan = 'info';
        } else if ($data->status == "Success") {
            $label_pesanan = 'success';
        } else {
            $label_pesanan = 'danger';
        }
        
        if ($pembayaran->status == "Menunggu Pembayaran") {
            $label_pesananpembayaran = 'warning';
        } else if ($pembayaran->status == "Lunas") {
            $label_pesananpembayaran = 'success';
        } else {
            $label_pesananpembayaran = 'danger';
        }

        $send = '
            <form action="'.route("pesanan.pending.detail.update", [$id]).'" method="POST">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="kategori_id" value="'.$layanan->kategori_id.'">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>OID</th>
                                <td><input type="text" class="form-control" value="'. $data->order_id . '" name="uid" readonly></td>
                            </tr>
                            <tr>
                                <th>POID</th>
                                <td><input type="text" class="form-control" value="'. $data->provider_order_id . '" readonly></td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td><input type="text" class="form-control" value="'. $kategori->nama . '" name="kategori" readonly></td>
                            </tr>
                            <tr>
                                <th>Layanan</th>
                                <td><input type="text" class="form-control" value="'. $data->layanan . '" name="layanan" readonly></td>
                            </tr>
                            <tr>
                                <th>Target</th>
                                <td><input type="text" class="form-control" value="'. $data->user_id . '" name="uid" readonly></td>
                            </tr>
                            <tr>
                                <th>Jumah/Quantity</th>
                                <td><input type="text" class="form-control" value="'. $data->quantity . '" name="quantity" readonly></td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td>Rp. ' . number_format($data->harga, 0, ',', '.') . '</td>
                            </tr>
                            <tr>
                                <th>Status Transaksi</th>
                                <td><span class="badge bg-' . $label_pesanan . '">' . $data->status . '</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td><span class="badge bg-' . $label_pesananpembayaran . '">' . $pembayaran->status . '</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td><input type="text" class="form-control" placeholder="isi keterangan" name="keterangan" required></td>
                            </tr>
                            <tr>
                                <th>Tanggal transaksi</th>
                                <td>' . $data->created_at . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>';

        return $send;
    }
    
    public function patch_pending(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'uid' => 'required',
            'kategori' => 'required',
            'layanan' => 'required',
            'quantity' => 'required'
        ]);
        
        $pembelians = \App\Models\Pembelian::where('order_id', $id)->first();
        $pembayaran = Pembayaran::where('order_id', $id)->first();
        
        if(count(Pembelian::where('order_id', $id)->where('status', 'Pending')->get()) == 0) {
            return back()->with('error', 'Tidak ditemukan pembelian dengan status Pending');
        } else if(!$request->keterangan) {
            return back()->with('error', 'Mohon untuk mengisi form <b>Keterangan</b>');
        } else {
            if($pembelians->username == null) {
                $UserCheck = null;
                $role = null;
            } else {
                $user = \App\Models\User::where('username', $pembelians->username)->first();
                $UserCheck = true;
                $role = $user->role;
            }
            
            if($UserCheck == true){
                if($role == "Member"){
                    $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider')->first();
                }else if($role == "Reseller" || $role == "Admin"){
                    $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan','harga_reseller AS harga','kategori_id', 'provider_id', 'provider')->first();
                }            
            }else{
                $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider')->first();
            }
            
            
            $kategori = \App\Models\Kategori::where('id', $dataLayanan->kategori_id)->select('kode','nama')->first();
    
            $phone = $formatter->filter_phone('62',$pembelians->nomor);
            $wa = new WhatsappController;
            
            $api = \DB::table('setting_webs')->where('id',1)->first();
            
            if($dataLayanan->provider == "manual"){
                $order['transactionId'] = '';
                $order['sn'] = $request->keterangan;
                $order['status'] = true;        
            }
            if($order['status']){
                    $pesanSukses = 
                        "PESANAN KAMU BERHASIL DIPROSES\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : $request->uid*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n".
                        "❃ ➤ Status : *Lunas*\n".
                        "❃ ➤ Nomor Invoice : *$id*\n\n".
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : $api->nomor_admin\n".
                        "Online 24 Jam";
        
                    $pesanSuksesAdmin = 
                        "PESANAN KAMU BERHASIL DIBUAT\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : $request->uid*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n".
                        "❃ ➤ Status : *Lunas*\n".
                        "❃ ➤ Nomor Invoice : *$id*\n\n".
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : $api->nomor_admin\n".
                        "Online 24 Jam";
    
                $wa->send($phone, $pesanSukses);
                $wa->send($formatter->filter_phone('62',$api->nomor_admin), $pesanSuksesAdmin);
                    
                Pembelian::where('order_id', $id)->update(['provider_order_id' => $order['transactionId'] ? $order['transactionId'] : null, 'status' => 'Success', 'note' => $order['sn'], 'log' => json_encode($order)]);
                
                $log = new LogUser();
                $log->user = Auth::user()->username;
                $log->type = 'system';
                $log->text = 'IP : '.$client_ip.' Melakukan update transaksi status pending #'.$id;
                $log->ip = $client_ip;
                $log->loc = $client_iploc;
                $log->ua = $browser;
                $log->save();
                
                return back()->with('success','Pesanan status pending #'.$id.' berhasil diproses!');
            }else{
                return back()->with('error',isset($msg) ? $msg : 'Terjadi kesalahan');
            }
        }
    }
    
    public function detail_failed($id)
    {
        $data = Pembelian::where('order_id', $id)->first();
        $layanan = \App\Models\Layanan::where('id',$data->id_layanan)->first();
        $kategori = \App\Models\Kategori::where('id',$layanan->kategori_id)->first();
        $pembayaran = \App\Models\Pembayaran::where('order_id',$id)->first();
        
        $zone = $data->zone != null ? "-" . $data->zone : "";

        if ($data->status == "Pending") {
            $label_pesanan = 'warning';
        } else if ($data->status == "Processing") {
            $label_pesanan = 'info';
        } else if ($data->status == "Success") {
            $label_pesanan = 'success';
        } else {
            $label_pesanan = 'danger';
        }
        
        if ($pembayaran->status == "Menunggu Pembayaran") {
            $label_pesananpembayaran = 'warning';
        } else if ($pembayaran->status == "Lunas") {
            $label_pesananpembayaran = 'success';
        } else {
            $label_pesananpembayaran = 'danger';
        }

        $send = '
            <form action="'.route("pesanan.failed.detail.update", [$id]).'" method="POST">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="kategori_id" value="'.$layanan->kategori_id.'">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>OID</th>
                                <td><input type="text" class="form-control" value="'. $data->order_id . '" name="uid" readonly></td>
                            </tr>
                            <tr>
                                <th>POID</th>
                                <td><input type="text" class="form-control" value="'. $data->provider_order_id . '" readonly></td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td><input type="text" class="form-control" value="'. $kategori->nama . '" name="kategori" readonly></td>
                            </tr>
                            <tr>
                                <th>Layanan</th>
                                <td><input type="text" class="form-control" value="'. $data->layanan . '" name="layanan" readonly></td>
                            </tr>
                            <tr>
                                <th>Target</th>
                                <td><input type="text" class="form-control" value="'. $data->user_id . '" name="uid" readonly></td>
                            </tr>
                            <tr>
                                <th>Jumlah/Quantity</th>
                                <td><input type="text" class="form-control" value="'. $data->quantity . '" name="quantity" readonly></td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td>Rp. ' . number_format($data->harga, 0, ',', '.') . '</td>
                            </tr>
                            <tr>
                                <th>Status Transaksi</th>
                                <td><span class="badge bg-' . $label_pesanan . '">' . $data->status . '</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td><span class="badge bg-' . $label_pesananpembayaran . '">' . $pembayaran->status . '</td>
                            </tr>
                            <tr>
                                <th>Transaksi Ke</th>
                                <td><input type="text" class="form-control" placeholder="contoh : 1" name="ke" required></td>
                            </tr>
                            <tr>
                                <th>Tanggal transaksi</th>
                                <td>' . $data->created_at . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>';

        return $send;
    }
    
    public function patch_failed(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'uid' => 'required',
            'kategori' => 'required',
            'layanan' => 'required',
            'quantity' => 'required'
        ]);
        
        $pembelians = \App\Models\Pembelian::where('order_id', $id)->first();
        $pembayaran = Pembayaran::where('order_id', $id)->where('status', 'Lunas')->first();
        
        if(count(Pembelian::where('order_id', $id)->where('status', 'Failed')->get()) == 0) {
            return back()->with('error', 'Tidak ditemukan pembayaran dengan status Menunggu Pembayaran');
        } else if(!$request->ke) {
            return back()->with('error', 'Mohon untuk mengisi form <b>Transaksi Ke</b>');
        } else {
            if($pembelians->username == null) {
                $UserCheck = null;
                $role = null;
            } else {
                $user = \App\Models\User::where('username', $pembelians->username)->first();
                $UserCheck = true;
                $role = $user->role;
            }
            
            if($UserCheck == true){
                if($role == "Member"){
                    $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider')->first();
                }else if($role == "Reseller" || $role == "Admin"){
                    $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan','harga_reseller AS harga','kategori_id', 'provider_id', 'provider')->first();
                }            
            }else{
                $dataLayanan = \App\Models\Layanan::where('id', $pembelians->id_layanan)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider')->first();
            }
            
            
            $kategori = \App\Models\Kategori::where('id', $dataLayanan->kategori_id)->select('kode','nama')->first();
    
            $phone = $formatter->filter_phone('62',$pembelians->nomor);
            $wa = new WhatsappController;
            
            $api = \DB::table('setting_webs')->where('id',1)->first();
            
            if($pembelians->provider_order_id.'-'.$request->ke != $pembelians->provider_order_id.'-'.$request->ke) {
                $poid = $id.'-'.$request->ke;
            } else {
                return back()->with('error', 'Error, Transaksi POID #'.$id.'-'.$request->ke.' sudah ada didatabase!');
            }
            
            if($dataLayanan->provider == "partaisocmed"){
                $partaisocmed = new PartaisocmedController;
                $order = $partaisocmed->order($pembelians->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->keywords, $pembelians->custom_comments, $pembelians->usernames, $pembelians->hashtag, $pembelians->media, $pembelians->answer_number,
                            $pembelians->minimal, $pembelians->maximal, $pembelians->post, $pembelians->old_post, $pembelians->delay, $pembelians->expiry);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "irvankedesmm"){
                $irvankedesmm = new IrvankedesmmController;
                $order = $irvankedesmm->order($pembelians->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->custom_comments, $pembelians->usernames, $pembelians->hashtag, $pembelians->media, $pembelians->answer_number);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "vipmember"){
                $vipmember = new VipMemberController;
                $order = $vipmember->order($pembelians->user_id, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->usernames, $pembelians->answer_number, $pembelians->custom_comments);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "istanamarket"){
                $istanamarket = new IstanaMarketController;
                $order = $istanamarket->order($pembelians->uid, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->custom_comments);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "fanstore"){
                $fanstore = new FanstoreController;
                $order = $fanstore->order($pembelians->uid, $dataLayanan->provider_id, $pembelians->quantity);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }else if($dataLayanan->provider == "rasxmedia"){
                $rasxmedia = new RasxmediaController;
                $order = $rasxmedia->order($pembelians->uid, $dataLayanan->provider_id, $pembelians->quantity, $pembelians->custom_comments);
                    
                if($order['result']){
                    $order['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'];
                }else{
                    $msg = $order['message'];
                    $order['status'] = false;
                }
            }
            if($order['status']){
                    $pesanSukses = 
                        "PESANAN KAMU BERHASIL DIPROSES KEMBALI\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : $request->uid*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n".
                        "❃ ➤ Status : *Lunas*\n".
                        "❃ ➤ Nomor Invoice : *$id*\n\n".
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : $api->nomor_admin\n".
                        "Online 24 Jam";
        
                    $pesanSuksesAdmin = 
                        "PESANAN KAMU BERHASIL DIBUAT\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : $request->uid*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n".
                        "❃ ➤ Status : *Lunas*\n".
                        "❃ ➤ Nomor Invoice : *$id*\n\n".
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : $api->nomor_admin\n".
                        "Online 24 Jam";
    
                $wa->send($phone, $pesanSukses);
                $wa->send($formatter->filter_phone('62',$api->nomor_admin), $pesanSuksesAdmin);
                    
                Pembelian::where('order_id', $id)->update(['provider_order_id' => $order['transactionId'] ? $order['transactionId'] : null, 'status' => 'Pending', 'log' => json_encode($order)]);
                
                $log = new LogUser();
                $log->user = Auth::user()->username;
                $log->type = 'system';
                $log->text = 'IP : '.$client_ip.' Melakukan update transaksi status failed #'.$id;
                $log->ip = $client_ip;
                $log->loc = $client_iploc;
                $log->ua = $browser;
                $log->save();
                
                return back()->with('success','Pesanan status failed #'.$id.' berhasil diproses kembali!');
            }else{
                return back()->with('error',isset($msg) ? $msg : 'Terjadi kesalahan');
            }
        }
    }
}