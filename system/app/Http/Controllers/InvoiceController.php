<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Berita;
use App\Models\Seting;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Rating;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    public function create($order)
    {
        $data = Pembelian::where('pembayarans.order_id', $order)->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembayarans.status AS status_pembayaran', 'pembayarans.metode AS metode_pembayaran', 'pembayarans.metode_code AS kode_pembayaran', 'pembayarans.metode_tipe AS tipe_pembayaran', 'pembayarans.provider AS provider_pembayaran', 'pembayarans.checkout_url AS checkout_url', 'pembayarans.no_pembayaran', 'pembayarans.reference','pembelians.order_id AS id_pembelian',
                        'user_id', 'custom_comments', 'usernames', 'hashtag', 'expiry', 'delay', 'old_post', 'maximal', 'minimal', 'post', 'media', 'answer_number', 'keywords', 'layanan', 'count', 'remain', 'id_layanan', 'note', 'tipe_transaksi', 'pembayarans.harga AS harga_pembayaran', 'pembelians.created_at AS created_at', 'pembelians.status AS status_pembelian', 'pembayarans.reference')
                ->first();
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $expired = date('Y-m-d H:i:s', strtotime('+'.$api->expired_invoice_hours.' Hours +'.$api->expired_invoice_minutes.' minutes', strtotime($data->created_at)));

        $formatTanggal = Carbon::parse($expired)->format('d F Y, h:i:s');
        $formatOrder = Carbon::parse($data->created_at)->format('d F Y, h:i:s');
        $layanan = Layanan::where('id', $data->id_layanan)->first();
        $kategori = Kategori::where('id', $layanan->kategori_id)->first();
        return view('components.invoice', [
        'rating' => Rating::where('order_id',$order)->select('kategori_id')->get(),
        'kategori' => $kategori,
        'data' => $data, 'tanggal' => $formatOrder, 'fexpired' => $formatTanggal, 'expired' => $expired,
        'data_joki' => \DB::table('data_joki')->where('order_id', $order)->first(),
        'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function getinvoice($order)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $d = Pembelian::where('pembayarans.order_id', $order)->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembayarans.status AS status_pembayaran', 'pembayarans.metode AS metode_pembayaran', 'pembayarans.metode_code AS kode_pembayaran', 'pembayarans.metode_tipe AS tipe_pembayaran', 'pembayarans.provider AS provider_pembayaran', 'pembayarans.checkout_url AS checkout_url', 'pembayarans.no_pembayaran', 'pembayarans.reference','pembelians.order_id AS id_pembelian',
                        'user_id', 'custom_comments', 'usernames', 'hashtag', 'expiry', 'delay', 'old_post', 'maximal', 'minimal', 'post', 'media', 'answer_number', 'keywords', 'layanan', 'count', 'remain', 'id_layanan', 'note', 'tipe_transaksi', 'pembayarans.harga AS harga_pembayaran', 'pembelians.created_at AS created_at', 'pembelians.status AS status_pembelian', 'pembayarans.reference')
                ->first();
        $alert_information = '';
        $status_pembayaran = '';
        $status_pembelian = '';
        $form_rating = '';
        $rating = Rating::where('order_id',$order)->select('kategori_id')->get();
        if($d->status_pembayaran == "Menunggu Pembayaran" AND $d->status_pembelian == "Pending") {
			$alert_information .= '<div class="alert alert-primary text-center">
				<b class="d-block">Harap segera melakukan pembayaran sebelum</b>
				<h6 class="mb-0 fw-700 text-danger"><span class="h6 text-danger" id="countdown"></span></h6>
			</div>';
		}elseif($d->status_pembayaran == "Lunas" AND $d->status_pembelian == "Failed") {
			$alert_information .= '<div class="alert alert-danger text-center">
				<b class="d-block">Pembelian Anda Gagal, Silahkan Hubungi admin!</b>
			</div>';
		}elseif($d->status_pembayaran == "Expired" AND $d->status_pembelian == "Failed") {
			$alert_information .= '<div class="alert alert-danger text-center">
				<b class="d-block">Pembayaran Telah Kadaluarsa !</b>
			</div>';
		}elseif($d->status_pembayaran == "Lunas" AND $d->status_pembelian == "Refund") {
			$alert_information .= '<div class="alert alert-danger text-center">
				<b class="d-block">Pembelian anda telah dikembalikan!</b>
			</div>';
		}elseif($d->status_pembayaran == "Lunas" AND in_array($d->status_pembelian, ["Pending","Processing"])) {
			$alert_information .= '<div class="alert alert-info text-center">
				<b class="d-block">Pembayaran Telah Lunas, Silahkan tunggu proses pesanan anda!</b>
			</div>';
		}elseif($d->status_pembayaran == "Lunas" AND $d->status_pembelian == "Success") {
			$alert_information .= '<div class="alert alert-success text-center">
				<b class="d-block">Pembelian anda telah Sukses!</b>
			</div>';
		}
		if($d->status_pembayaran == "Lunas") {
			$status_pembayaran .= '<th class="ps-0 pb-0">Status Pembayaran</th><td class="ps-0 pb-0 text-end fw-800 text-success">'.$d->status_pembayaran.'</td>';
		}elseif($d->status_pembayaran == "Menunggu Pembayaran"){
		    $status_pembayaran .= '<th class="ps-0 pb-0">Status Pembayaran</th><td class="ps-0 pb-0 text-end fw-800 text-warning">'.$d->status_pembayaran.'</td>';
		}elseif($d->status_pembayaran == "Expired"){
			$status_pembayaran .= '<th class="ps-0 pb-0">Status Pembayaran</th><td class="ps-0 pb-0 text-end fw-800 text-danger">'.$d->status_pembayaran.'</td>';
		}
		
		if($d->status_pembelian == "Success") {
			$status_pembelian .= '<th class="ps-0 pb-0">Status</th><td class="ps-0 pb-0 text-end fw-800 text-success">'.$d->status_pembelian.'</td>';
		}elseif($d->status_pembelian == "Pending") {
			$status_pembelian .= '<th class="ps-0 pb-0">Status</th><td class="ps-0 pb-0 text-end fw-800 text-warning">'.$d->status_pembelian.'</td>';
		}elseif($d->status_pembelian == "Processing") {
		    $status_pembelian .= '<th class="ps-0 pb-0">Status</th><td class="ps-0 pb-0 text-end fw-800 text-info">'.$d->status_pembelian.'</td>';
		}elseif(in_array($d->status_pembelian, ["Failed","Refund"])) {
			$status_pembelian .= '<th class="ps-0 pb-0">Status</th><td class="ps-0 pb-0 text-end fw-800 text-danger">'.$d->status_pembelian.'</td>';
        }
        if($d->status_pembelian == "Success" AND count($rating) == 0) {
			$form_rating = true;
        } else {
            $form_rating = false;
        }
        return ['alert_information' => $alert_information, 'keterangan' => $d->note, 'status_pembayaran' => $status_pembayaran, 'status_pembelian' => $status_pembelian, 'rating' => $form_rating];
    }
    public function ratingCustomer(Request $request, $order) {
        
        $data = Pembelian::where('pembayarans.order_id', $order)->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembayarans.status AS status_pembayaran', 'pembayarans.metode AS metode_pembayaran', 'pembayarans.metode_code AS kode_pembayaran', 'pembayarans.metode_tipe AS tipe_pembayaran', 'pembayarans.provider AS provider_pembayaran', 'pembayarans.checkout_url AS checkout_url', 'pembayarans.no_pembayaran', 'pembayarans.reference','pembelians.order_id AS id_pembelian',
                        'user_id', 'zone', 'nickname', 'layanan', 'id_layanan', 'note', 'tipe_transaksi', 'pembayarans.harga AS harga_pembayaran', 'pembelians.created_at AS created_at', 'pembelians.status AS status_pembelian', 'pembayarans.reference', 'pembayarans.no_pembeli')
                ->first();
        $layanan = Layanan::where('id',$data->id_layanan)->select('kategori_id')->first();
        
        $rating = new Rating();
        $rating->order_id = $order;
        $rating->kategori_id = $layanan->kategori_id;
        $rating->layanan = $data->layanan;
        $rating->no_pembeli = $data->no_pembeli;
        $rating->bintang = $request->star;
        $rating->comment = $request->comment;
        $rating->save();
    }
}
