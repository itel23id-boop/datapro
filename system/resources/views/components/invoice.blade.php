@extends('../main')

@section('css')
<style>
    @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,700");
    @import url("https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css");

    .cus-accordion {
        transform: translateZ(0);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        /* background: #fff; */
    }

    .bg-primary {
        cursor: pointer;
    }

    .cus-accordion>.accordion-toggle {
        position: absolute;
        opacity: 0;
        display: none;
    }

    .hayutopup-payment-img {
        height: 27px;
        /* width: 80px; */
        background: white;
        border-radius: 7px;
        padding: 3px;
    }

    .hayutopup-payment-imglogo {
        height: 27px;
        /* width: 80px; */
        /* background: white; */
        border-radius: 7px;
        padding: 0px;
    }

    .hayu-qris {
        padding: 5px;
        border-radius: 2px;
        background: white;
        align-items: center;
        width: 200px;
    }

    .cus-accordion>label {
        position: relative;
        display: block;
        height: 50px;
        line-height: 50px;
        padding: 0 20px;
        font-size: 14px;
        font-weight: 700;
        border-top: 1px solid #ddd;
        /* background: #fff; */
        cursor: pointer;
    }

    .cus-accordion>label:after {
        content: '\f078';
        position: absolute;
        top: 0px;
        right: 20px;
        font-family: fontawesome;
        transform: rotate(90deg);
        transition: .3s transform;
    }

    .cus-accordion>section {
        height: 0;
        transition: .3s all;
        overflow: hidden;
    }

    .cus-accordion>.accordion-toggle:checked~label:after {
        transform: rotate(0deg);
    }

    .cus-accordion>.accordion-toggle:checked~section {
        height: 200px;
    }

    .cus-accordion>section p {
        margin: 15px 0;
        padding: 0 20px;
        font-size: 12px;
        line-height: 1.5;
    }

    .hayutopup-petunjuk {
        font-size: 14px;
        font-style: italic;
    }
    .col-bjinv {
    flex: 0 0 auto;
    width: 50%;
    font-size: 14px;
    text-align: right;
    }
    .col-bjinv2 {
    flex: 0 0 auto;
    width: 50%;
    font-size: 14px;
    }
</style>
@endsection

@section('content')
<div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card mb-4 d-none-print shadow-form">
						<div class="card-body">
							<h6 class="card-title">Cek Pesanan</h6>
							<form action="{{ route('cari.post') }}" method="post">
							    @csrf
								<div class="mb-3">
									<div class="input-group">
										<input type="text" class="form-control form-control-games" placeholder="Order ID Pesanan" autocomplete="off" value="{{ $data->id_pembelian }}" name="id" id="id">
										<button class="btn btn-primary">Cek</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="card card-print shadow-form" id="invoice">
						<div class="card-body">
							<h6 class="card-title">Detail Pesanan</h6>
							<div id="alert-status">
							@if($data->status_pembayaran == "Menunggu Pembayaran" AND $data->status_pembelian == "Pending")
							<div class="alert alert-primary text-center">
								<b class="d-block">Harap segera melakukan pembayaran sebelum</b>
								<h6 class="mb-0 fw-700 text-danger"><span class="h6 text-danger" id="countdown"></span></h6>
							</div>
							@elseif($data->status_pembayaran == "Lunas" AND $data->status_pembelian == "Failed")
							<div class="alert alert-danger text-center">
								<b class="d-block">Pembelian Anda Gagal, Silahkan Hubungi admin!</b>
							</div>
							@elseif($data->status_pembayaran == "Expired" AND $data->status_pembelian == "Failed")
							<div class="alert alert-danger text-center">
								<b class="d-block">Pembayaran Telah Kadaluarsa !</b>
							</div>
							@elseif($data->status_pembayaran == "Lunas" AND $data->status_pembelian == "Refund")
							<div class="alert alert-danger text-center">
								<b class="d-block">Pembelian anda telah dikembalikan!</b>
							</div>
							@elseif($data->status_pembayaran == "Lunas" AND in_array($data->status_pembelian, ["Pending","Processing"]))
							<div class="alert alert-info text-center">
								<b class="d-block">Pembayaran Telah Lunas, Silahkan tunggu proses pesanan anda!</b>
							</div>
							@elseif($data->status_pembayaran == "Lunas" AND $data->status_pembelian == "Success")
							<div class="alert alert-success text-center">
								<b class="d-block">Pembelian anda telah Sukses!</b>
							</div>
							@endif
							</div>
							<div class="table-responsive">
								<table class="table mb-4">
									<tr>
										<th class="ps-0 pb-0">Order ID</th>
										<td class="ps-0 pb-0 text-end">#{{ $data->id_pembelian }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->id_pembelian }}', 'Order ID berhasil disalin');"></i></td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Tanggal</th>
										<td class="ps-0 pb-0 text-end">{{ $tanggal }}</td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Kategori</th>
										<td class="ps-0 pb-0 text-end">{{ $kategori->nama }}</td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Produk</th>
										<td class="ps-0 pb-0 text-end">{{ $data->layanan }}</td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Tujuan</th>
										<td class="ps-0 pb-0 text-end">{{ $data->user_id }} </td>
									</tr>
									@if($data->custom_comments !== null)
									<tr>
										<th class="ps-0 pb-0">Custom Comments</th>
										<td class="ps-0 pb-0 text-end">{{ $data->custom_comments }} </td>
									</tr>
									@endif
									@if($data->usernames !== null)
									<tr>
										<th class="ps-0 pb-0">Usernames</th>
										<td class="ps-0 pb-0 text-end">{{ $data->usernames }} </td>
									</tr>
									@endif
									@if($data->hashtag !== null)
									<tr>
										<th class="ps-0 pb-0">Hashtag</th>
										<td class="ps-0 pb-0 text-end">{{ $data->hashtag }} </td>
									</tr>
									@endif
									@if($data->expiry !== null)
									<tr>
										<th class="ps-0 pb-0">Expired</th>
										<td class="ps-0 pb-0 text-end">{{ $data->expiry }} </td>
									</tr>
									@endif
									@if($data->delay !== null)
									<tr>
										<th class="ps-0 pb-0">Delay</th>
										<td class="ps-0 pb-0 text-end">{{ $data->delay }} </td>
									</tr>
									@endif
									@if($data->old_post !== null)
									<tr>
										<th class="ps-0 pb-0">Old Post</th>
										<td class="ps-0 pb-0 text-end">{{ $data->old_post }} </td>
									</tr>
									@endif
									@if($data->maximal !== null)
									<tr>
										<th class="ps-0 pb-0">Maximal</th>
										<td class="ps-0 pb-0 text-end">{{ $data->maximal }} </td>
									</tr>
									@endif
									@if($data->minimal !== null)
									<tr>
										<th class="ps-0 pb-0">Minimal</th>
										<td class="ps-0 pb-0 text-end">{{ $data->minimal }} </td>
									</tr>
									@endif
									@if($data->post !== null)
									<tr>
										<th class="ps-0 pb-0">Post</th>
										<td class="ps-0 pb-0 text-end">{{ $data->post }} </td>
									</tr>
									@endif
									<tr>
										<th class="ps-0 pb-0">Jumlah Awal</th>
										<td class="ps-0 pb-0 text-end">{{ $data->count }}</td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Sisa</th>
										<td class="ps-0 pb-0 text-end">{{ $data->remain }}</td>
									</tr>
									<tr id="status_pembelian">
										<th class="ps-0 pb-0">Status</th>
										@if($data->status_pembelian == "Success")
										<td class="ps-0 pb-0 text-end fw-800 text-success">{{ $data->status_pembelian }}</td>
										@elseif($data->status_pembelian == "Pending")
										<td class="ps-0 pb-0 text-end fw-800 text-warning">{{ $data->status_pembelian }}</td>
										@elseif($data->status_pembelian == "Processing")
										<td class="ps-0 pb-0 text-end fw-800 text-info">{{ $data->status_pembelian }}</td>
										@elseif(in_array($data->status_pembelian, ["Failed","Refund"]))
										<td class="ps-0 pb-0 text-end fw-800 text-danger">{{ $data->status_pembelian }}</td>
										@endif
									</tr>
									<tr id="status_pembayaran">
										<th class="ps-0 pb-0">Status Pembayaran</th>
										@if($data->status_pembayaran == "Lunas")
										<td class="ps-0 pb-0 text-end fw-800 text-success">{{ $data->status_pembayaran }}</td>
										@elseif($data->status_pembayaran == "Menunggu Pembayaran")
										<td class="ps-0 pb-0 text-end fw-800 text-warning">{{ $data->status_pembayaran }}</td>
										@elseif($data->status_pembayaran == "Expired")
										<td class="ps-0 pb-0 text-end fw-800 text-danger">{{ $data->status_pembayaran }}</td>
										@endif
									</tr>
									<tr>
										<th class="ps-0 pb-0">Metode Pembayaran</th>
										<td class="ps-0 pb-0 text-end">{{ $data->metode_pembayaran }}</td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Keterangan</th>
										<td class="ps-0 pb-0 text-end" id="keterangan">{{ $data->note }}</td>
									</tr>
									<tr>
										<th class="ps-0 pb-0">Total</th>
										<td class="ps-0 pb-0 text-end fw-800 text-total">Rp. {{ number_format($data->harga_pembayaran, 0, ',','.') }} <i class="fa fa-copy ms-1" onclick="salin('{{$data->harga_pembayaran}}', 'Total berhasil disalin');"></i></td>
									</tr>
								</table>
								<div class="mb-3">
								    @if($data->metode_pembayaran == "Saldo" || in_array($data->status_pembayaran, ["Lunas","Batal"]))
								    @else
    									<h6 class="fw-700 mb-0">Pembayaran <span style="color:aqua;">{{ $data->metode_pembayaran }}</span></h6>
    									@if(in_array($data->provider_pembayaran, ["linkqu","tokopay","tripay","duitku","ipaymu","xendit"]) AND $data->status_pembayaran == "Menunggu Pembayaran" AND $data->tipe_pembayaran == "qris")
    									    <p class="mb-3">Silahkan scan QR berikut untuk melakukan pembayaran</p>
    									    <img src="https://quickchart.io/qr?text={{ $data->no_pembayaran }}" width="200" class="rounded-3">
    									    
    									@elseif(in_array($data->kode_pembayaran, ["BRIVA","BR","002","bri","BNIVA","I1","009","bni","BCAVA","BC","014","bca"]) AND $data->status_pembayaran == "Menunggu Pembayaran")
    									    <p class="mb-0">Silahkan melakukan pembayaran ke nomor pembayaran berikut</p>
    									    Kode Pembayaran / Kode Virtual Account : <b class="text-primary">{{ $data->no_pembayaran }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->no_pembayaran }}', 'Kode Pembayaran berhasil disalin');"></i></b>
    									    
    									@elseif($data->provider_pembayaran == "ipaymu" AND $data->status_pembayaran == "Menunggu Pembayaran" AND in_array($data->tipe_pembayaran, ["virtual-account","convenience-store","e-walet"]))
    									    <p class="mb-0">Silahkan melakukan pembayaran ke nomor pembayaran berikut</p>
    									    Kode Pembayaran / Kode Virtual Account : <b class="text-primary">{{ $data->no_pembayaran }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->no_pembayaran }}', 'Kode Pembayaran berhasil disalin');"></i></b>
    									    
    									@elseif($data->provider_pembayaran == "xendit" AND $data->status_pembayaran == "Menunggu Pembayaran" AND in_array($data->tipe_pembayaran, ["virtual-account","convenience-store"]))
    									    <p class="mb-0">Silahkan melakukan pembayaran ke nomor pembayaran berikut</p>
    									    Kode Pembayaran / Kode Virtual Account : <b class="text-primary">{{ $data->no_pembayaran }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->no_pembayaran }}', 'Kode Pembayaran berhasil disalin');"></i></b>
    									    
    									@elseif($data->provider_pembayaran == "manual" AND $data->status_pembayaran == "Menunggu Pembayaran" AND in_array($data->tipe_pembayaran, ["bank-transfer","e-walet"]))
    									    <p class="mb-0">Silahkan melakukan pembayaran ke nomor pembayaran berikut</p>
    									    Kode Pembayaran / Kode Virtual Account : <b class="text-primary">{{ $data->no_pembayaran }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->no_pembayaran }}', 'Kode Pembayaran berhasil disalin');"></i></b>
    									    
    									@else
    									    <p class="mb-0">Silahkan melakukan pembayaran berikut</p>
    									    <a class="btn btn-warning btn-sm" href="{{ $data->checkout_url }}" target="blank_"> Bayar Sekarang</a>
    									@endif
									@endif
								</div>
								<div class="mb-3">
								    @if($data->metode_pembayaran != "Saldo" AND $data->status_pembayaran == "Menunggu Pembayaran" AND $data->provider_pembayaran != "manual")
								    <h6 class="fw-700 mb-0">Instruksi Pembayaran</h6>
								        <div class="accordion" id="accordionExample">
								            @if($data->tipe_pembayaran == "qris")
                                                @if($data->status_pembayaran == "Menunggu Pembayaran")
                                            	<div class="border-0 mb-2">
                                                <h2 class="accordion-header" id="heading-1">
                                                <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false" aria-controls="collapse-1"><strong>Cara Bayar QRIS dengan 1 HP</strong></button>
                                                </h2>
                                                    <div id="collapse-1" class="accordion-collapse collapse" aria-labelledby="heading-1" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ul>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li>Download Barcode QRIS diatas</li>
                                                                <li>Buka Aplikasi E-Wallet atau&nbsp; M-Banking dan pilih <strong>Scan Barcode</strong> atau <strong>Scan Bayar QRIS</strong></li>
                                                                <li>Pilih gambar Barcode QRIS&nbsp;yang sudah di download di dalam Galeri anda</li>
                                                                <li>Lalu klik Bayar</li>
                                                                <li>Selesai, Transaksi akan di proses secara otomatis.</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @elseif($data->tipe_pembayaran == "virtual-account")
                                                @if(in_array($data->metode_pembayaran, ["BRI Virtual Account"]))
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-2">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2"><strong>Cara Bayar melalui BRI Mobile (BRIMO)</strong></button>
                                                    </h2>
                                                    <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="heading-2" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li>Buka dan login ke aplikasi BRI Mobile</li>
                                                                <li>Masuk ke Menu <strong>BRIVA</strong></li>
                                                                <li>Klik pembayaran baru dan Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BRI yang ada diatas</li>
                                                                <li>Lakukan konfirmasi kesesuaian data</li>
                                                                <li>Selanjutnya klik <strong>bayar</strong></li>
                                                                <li>Transaksi berhasil dilakukan.</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-3">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3"><strong>Cara Bayar melalui ATM BRI</strong></button>
                                                    </h2>
                                                    <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="heading-3" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Kunjungi mesin ATM BRI terdekat</li>
                                                                <li>Pilih Menu <strong>Transaksi Lain</strong>, kemudian pilih Menu <strong>Pembayaran</strong></li>
                                                                <li>Pilih Menu <strong>Lainnya</strong>, kemudian pilih Menu <strong>BRIVA</strong></li>
                                                                <li>Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BRI yang ada diatas</li>
                                                                <li>Lakukan konfirmasi Pembayaran</li>
                                                                <li>Cetak dan simpan struk bukti Pembayaran. Transaksi berhasil dilakukan.</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-4">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4"><strong>Cara Bayar melalui ATM Bank Lainnya</strong></button>
                                                    </h2>
                                                    <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="heading-4" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Kunjungi mesin ATM terdekat</li>
                                                                <li>Masukkan Kartu ATM dan PIN, kemudian pilih Menu <Strong>Transaksi Lainnya</Strong></li>
                                                                <li>Pilih Menu <strong>Transfer</strong>, kemudian pilih Menu <strong>Ke Rekening Bank Lainnya</strong></li>
                                                                <li>Masukan Kode Bank Tujuan yaitu BRI (<strong>Kode Bank : 002</strong>) lalu klik Benar</li>
                                                                <li>Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BRI yang ada diatas</li>
                                                                <li>Masukkan jumlah pembayaran sesuai dengan <strong>Total Tagihan</strong> diatas, lalu Klik Benar untuk memproses Pembayaran</li>
                                                                <li>Lakukan konfirmasi Pembayaran</li>
                                                                <li>Cetak dan simpan struk bukti Pembayaran. Transaksi berhasil dilakukan.</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-5">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5"><strong>Cara Bayar melalui Mobile Banking Bank Lainnya</strong></button>
                                                    </h2>
                                                    <div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="heading-5" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Buka dan login ke aplikasi M-banking pilihan mu</li>
                                                                <li>Pilih menu Kirim atau Transfer, kemudian pilih Menu <strong>Bank Lain</strong></li>
                                                                <li>Pilih <strong>Bank BRI</strong></li>
                                                                <li>Lalu masukkan nomor rekening berupa <strong>Kode Pembayaran</strong> Nomor Virtual Account BRI yang ada diatas</li>
                                                                <li>Masukkan jumlah yang ingin ditransfer sesuai dengan <strong>Total Tagihan</strong> diatas</li>
                                                                <li>Lakukan Transfer</li>
                                                                <li>Transaksi berhasil dilakukan</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                @elseif(in_array($data->metode_pembayaran, ["BNI Virtual Account"]))
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-6">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-6" aria-expanded="false" aria-controls="collapse-6"><strong>Cara Bayar melalui BNI Mobile</strong></button>
                                                    </h2>
                                                    <div id="collapse-6" class="accordion-collapse collapse" aria-labelledby="heading-6" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li>Buka dan login ke aplikasi BNI Mobile</li>
                                                                <li>Masuk ke Menu <strong>Transfer</strong></li>
                                                                <li>Klik metode transfer Virtual Account dan Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BNI yang ada diatas</li>
                                                                <li>Lakukan konfirmasi kesesuaian data</li>
                                                                <li>Selanjutnya klik <strong>bayar</strong></li>
                                                                <li>Transaksi berhasil dilakukan.</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-7">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-7" aria-expanded="false" aria-controls="collapse-7"><strong>Cara Bayar melalui ATM BNI</strong></button>
                                                    </h2>
                                                    <div id="collapse-7" class="accordion-collapse collapse" aria-labelledby="heading-7" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Kunjungi mesin ATM BNI terdekat</li>
                                                                <li>Pilih Menu <strong>Transaksi Lain</strong>, kemudian pilih Menu <strong>Pembayaran</strong></li>
                                                                <li>Pilih Menu <strong>Lainnya</strong>, kemudian pilih Menu <strong>BRIVA</strong></li>
                                                                <li>Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BRI yang ada diatas</li>
                                                                <li>Lakukan konfirmasi Pembayaran</li>
                                                                <li>Cetak dan simpan struk bukti Pembayaran. Transaksi berhasil dilakukan.</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-8">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-8" aria-expanded="false" aria-controls="collapse-8"><strong>Cara Bayar melalui ATM Bank Lainnya</strong></button>
                                                    </h2>
                                                    <div id="collapse-8" class="accordion-collapse collapse" aria-labelledby="heading-8" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Kunjungi mesin ATM terdekat</li>
                                                                <li>Masukkan Kartu ATM dan PIN, kemudian pilih Menu <Strong>Transaksi Lainnya</Strong></li>
                                                                <li>Pilih Menu <strong>Transfer</strong>, kemudian pilih Menu <strong>Ke Rekening Bank Lainnya</strong></li>
                                                                <li>Masukan Kode Bank Tujuan yaitu BNI (<strong>Kode Bank : 009</strong>) lalu klik Benar</li>
                                                                <li>Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BNI yang ada diatas</li>
                                                                <li>Masukkan jumlah pembayaran sesuai dengan <strong>Total Tagihan</strong> diatas, lalu Klik Benar untuk memproses Pembayaran</li>
                                                                <li>Lakukan konfirmasi Pembayaran</li>
                                                                <li>Cetak dan simpan struk bukti Pembayaran. Transaksi berhasil dilakukan.</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-9">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-9" aria-expanded="false" aria-controls="collapse-9"><strong>Cara Bayar melalui Mobile Banking Bank Lainnya</strong></button>
                                                    </h2>
                                                    <div id="collapse-9" class="accordion-collapse collapse" aria-labelledby="heading-9" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Buka dan login ke aplikasi M-banking pilihan mu</li>
                                                                <li>Pilih menu Kirim atau Transfer, kemudian pilih Menu <strong>Bank Lain</strong></li>
                                                                <li>Pilih <strong>Bank BRI</strong></li>
                                                                <li>Lalu masukkan nomor rekening berupa <strong>Kode Pembayaran</strong> Nomor Virtual Account BNI yang ada diatas</li>
                                                                <li>Masukkan jumlah yang ingin ditransfer sesuai dengan <strong>Total Tagihan</strong> diatas</li>
                                                                <li>Lakukan Transfer</li>
                                                                <li>Transaksi berhasil dilakukan</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                @elseif(in_array($data->metode_pembayaran, ["BCA Virtual Account"]))
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-10">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-10" aria-expanded="false" aria-controls="collapse-10"><strong>Cara Bayar melalui BCA Mobile</strong></button>
                                                    </h2>
                                                    <div id="collapse-10" class="accordion-collapse collapse" aria-labelledby="heading-10" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li>Buka dan login ke aplikasi BCA Mobile</li>
                                                                <li>Masuk ke Menu <strong>Transfer</strong></li>
                                                                <li>Klik metode transfer Virtual Account dan Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BCA yang ada diatas</li>
                                                                <li>Lakukan konfirmasi kesesuaian data</li>
                                                                <li>Selanjutnya klik <strong>bayar</strong></li>
                                                                <li>Transaksi berhasil dilakukan.</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                                <li style="display: block; width: 0px; height: 0px; padding: 0px; border: 0px; margin: 0px; position: absolute; top: 0px; left: -9999px; opacity: 0; overflow: hidden;">&nbsp;</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-11">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-11" aria-expanded="false" aria-controls="collapse-11"><strong>Cara Bayar melalui ATM BCA</strong></button>
                                                    </h2>
                                                    <div id="collapse-11" class="accordion-collapse collapse" aria-labelledby="heading-11" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Kunjungi mesin ATM BCA terdekat</li>
                                                                <li>Pilih Menu <strong>Transaksi Lain</strong>, kemudian pilih Menu <strong>Pembayaran</strong></li>
                                                                <li>Pilih Menu <strong>Lainnya</strong>, kemudian pilih Menu <strong>BCA Virtual Account</strong></li>
                                                                <li>Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BCA yang ada diatas</li>
                                                                <li>Lakukan konfirmasi Pembayaran</li>
                                                                <li>Cetak dan simpan struk bukti Pembayaran. Transaksi berhasil dilakukan.</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-12">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-12" aria-expanded="false" aria-controls="collapse-12"><strong>Cara Bayar melalui ATM Bank Lainnya</strong></button>
                                                    </h2>
                                                    <div id="collapse-12" class="accordion-collapse collapse" aria-labelledby="heading-12" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Kunjungi mesin ATM terdekat</li>
                                                                <li>Masukkan Kartu ATM dan PIN, kemudian pilih Menu <Strong>Transaksi Lainnya</Strong></li>
                                                                <li>Pilih Menu <strong>Transfer</strong>, kemudian pilih Menu <strong>Ke Rekening Bank Lainnya</strong></li>
                                                                <li>Masukan Kode Bank Tujuan yaitu BCA (<strong>Kode Bank : 014</strong>) lalu klik Benar</li>
                                                                <li>Masukkan <strong>Kode Pembayaran</strong> Nomor Virtual Account BCA yang ada diatas</li>
                                                                <li>Masukkan jumlah pembayaran sesuai dengan <strong>Total Tagihan</strong> diatas, lalu Klik Benar untuk memproses Pembayaran</li>
                                                                <li>Lakukan konfirmasi Pembayaran</li>
                                                                <li>Cetak dan simpan struk bukti Pembayaran. Transaksi berhasil dilakukan.</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-13">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-13" aria-expanded="false" aria-controls="collapse-13"><strong>Cara Bayar melalui Mobile Banking Bank Lainnya</strong></button>
                                                    </h2>
                                                    <div id="collapse-13" class="accordion-collapse collapse" aria-labelledby="heading-13" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Buka dan login ke aplikasi M-banking pilihan mu</li>
                                                                <li>Pilih menu Kirim atau Transfer, kemudian pilih Menu <strong>Bank Lain</strong></li>
                                                                <li>Pilih <strong>Bank BCA</strong></li>
                                                                <li>Lalu masukkan nomor rekening berupa <strong>Kode Pembayaran</strong> Nomor Virtual Account BCA yang ada diatas</li>
                                                                <li>Masukkan jumlah yang ingin ditransfer sesuai dengan <strong>Total Tagihan</strong> diatas</li>
                                                                <li>Lakukan Transfer</li>
                                                                <li>Transaksi berhasil dilakukan</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @elseif($data->tipe_pembayaran == "e-walet")
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-14">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-14" aria-expanded="false" aria-controls="collapse-14"><strong>Cara Bayar E-Wallet</strong></button>
                                                    </h2>
                                                    <div id="collapse-14" class="accordion-collapse collapse" aria-labelledby="heading-14" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Klik Link Di atas untuk melanjutkan pembayaran</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($data->tipe_pembayaran == "pulsa")
                                                <div class="border-0 mb-2">
                                                    <h2 class="accordion-header" id="heading-15">
                                                    <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-15" aria-expanded="false" aria-controls="collapse-15"><strong>Cara Bayar Via Pulsa</strong></button>
                                                    </h2>
                                                    <div id="collapse-15" class="accordion-collapse collapse" aria-labelledby="heading-15" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body" style="background: var(--warna_4);">
                                                            <ol>
                                                                <li>Klik Link Di atas untuk melanjutkan pembayaran</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($data->tipe_pembayaran == "e-wallet" AND $data->provider == "manual")
                                            <div class="border-0 mb-2">
                                                <h2 class="accordion-header" id="heading-16">
                                                <button style="background: var(--warna_5);" class="accordion-button text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-16" aria-expanded="false" aria-controls="collapse-16"><strong>Cara Bayar E-Wallet</strong></button>
                                                </h2>
                                                <div id="collapse-16" class="accordion-collapse collapse" aria-labelledby="heading-16" data-bs-parent="#accordionExample" style="">
                                                    <div class="accordion-body" style="background: var(--warna_4);">
                                                        <ol>
                                                            <li>Buka E-Wallet Anda</li>
                                                            <li>Pilih menu <strong>Transfer</strong></li>
                                                            <li>Lakukan transfer ke nomor <strong>({{ $data->no_pembayaran }})</strong>pastikan nominal transfer sesuai dengan total pembayaran</li>
                                                            <li>Klik <strong>Lanjutkan</strong></li>
                                                            <li>Masukkan <strong>PIN anda</strong></li>
                                                            <li>Transaksi berhasil dilakukan</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
								<div class="mb-4 text-center rating d-none" id="ratings">
									<h6 class="fw-700">Beri penilaian untuk transaksi ini!</h6>
									<form id="myForm" action="{{ route('rating.pembelian', ['order' => $data->id_pembelian]) }}" method="POST">
									    @csrf
										<input type="hidden" name="star">
										<div class="mb-4">
											<i onmouseover="hover_star('1');" id="star-1" class="fa fa-star cursor-pointer"></i>
										    <i onmouseover="hover_star('2');" id="star-2" class="fa fa-star cursor-pointer"></i>
											<i onmouseover="hover_star('3');" id="star-3" class="fa fa-star cursor-pointer"></i>
										    <i onmouseover="hover_star('4');" id="star-4" class="fa fa-star cursor-pointer"></i>
											<i onmouseover="hover_star('5');" id="star-5" class="fa fa-star cursor-pointer"></i>					
										</div>
										<div class="mb-3">
											<textarea cols="30" rows="4" class="form-control rounded form-control-games" name="comment" id="comment" placeholder="Pesan"></textarea>
										</div>
										<div class="text-end">
											<button class="btn btn-primary" type="submit" name="tombol" value="submit">Kirim</button>
										</div>
									</form>
								</div>
							    <p class="text-muted fs-12 mt-4 text-center">Terimakasih telah melakukan pembelian di {{ !$config ? '' : $config->judul_web }}, untuk pertanyaan, kritik atau saran bisa di sampaikan langsung melalui halaman Kontak Kami</p>
								<div class="d-none-print text-center">
								    <button class="btn btn-primary" type="button" onclick="print_invoice();"><i class="fa fa-print me-2"></i> Cetak</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@section('js')
	<script>
	    function refreshHistory() {
            $.get('{{ENV("APP_URL")}}/pembelian/invoice/get-invoice/{{ $data->id_pembelian }}', function(data) {
                $("#alert-status").html(data.alert_information);
                $("#keterangan").html(data.keterangan);
                $("#status_pembayaran").html(data.status_pembayaran);
                $("#status_pembelian").html(data.status_pembelian);
                if(data.rating == true) {
                    $("#ratings").removeClass('d-none');
                }
            });
        }setInterval(refreshHistory, 5000);
        function refreshHistorys() {
            $.get('{{ENV("APP_URL")}}/updatepesanan');
        }setInterval(refreshHistorys, 1000);
	</script>
	<script>
	    @if(session('error'))
    	    toastr.error("{{ session('error') }}", "Error");
        @endif
	    const myForm = document.getElementById('myForm');
        myForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(myForm);
            fetch(myForm.action, {
                method: 'POST',
                body: formData
            }).then(function(response) {
                if (response.ok) {
                    toastr.success("Terima kasih telah memberikan testimoni!", "Success", {timeOut: 1000,fadeOut: 1000,onHidden: function () {window.location.reload();}});
                } else {
                    toastr.success("Gagal menyimpan testimoni!", "Error");
                }
            }).catch(function(error) {
                toastr.success("Gagal menyimpan testimoni!", "Error");
            });
        });
	</script>
	<script>
		function hover_star(star) {

			$("input[name=star]").val(star);

			$(".fa.fa-star").removeClass('text-primary');

			for (var i = 1; i <= star; i++) {
				$("#star-" + i).addClass('text-primary');
			}
		}
		const comment = document.getElementById('comment');
        comment.value = "Proses topup nya cepat dan harga nya murah banget!";

        comment.addEventListener('focus', function() {
            if (comment.value === "Proses topup nya cepat dan harga nya murah banget!") {
                comment.value = "";
            }
        });

        comment.addEventListener('blur', function() {
            if (comment.value === "") {
                comment.value = "Proses topup nya cepat dan harga nya murah banget!";
            }
        });
	</script>
            <script>
				CountDownTimer('{{$expired}}', 'countdown');
				function CountDownTimer(dt, id)
				{
					var end = new Date('{{$expired}}');
					var _second = 1000;
					var _minute = _second * 60;
					var _hour = _minute * 60;
					var _day = _hour * 24;
					var timer;
					function showRemaining() {
						var now = new Date();
						var distance = end - now;
						if (distance < 0) {

							clearInterval(timer);
							document.getElementById(id).innerHTML = '<b>Pembayaran Telah Kadaluarsa !</b> ';
							return;
						}
						var days = Math.floor(distance / _day);
						var hours = Math.floor((distance % _day) / _hour);
						var minutes = Math.floor((distance % _hour) / _minute);
						var seconds = Math.floor((distance % _minute) / _second);

						//document.getElementById(id).innerHTML = days + ' days ';
						document.getElementById(id).innerHTML = hours + ' hours ';
						document.getElementById(id).innerHTML += minutes + ' minutes ';
						document.getElementById(id).innerHTML += seconds + ' seconds left';
					}
					timer = setInterval(showRemaining, 1000);
				}
			</script>
<script>
        $(document).ready(function() {
            $("#paycode").tooltip();
            $("#paycode").click(function() {
                copyToClipboard($(this).text().trim().replace(".", ""), $(this));
            })
            $("#paycode").css('cursor', 'pointer');
        })

        function print_invoice() {
            var printContents = document.getElementById('invoice').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.onafterprint = function() {
                location.reload()
            }
        }

        function copyToClipboard2(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text().trim().replace(".", "")).select();
            document.execCommand("copy");
            $temp.remove();
            
        }
    </script>
    @endsection
@endsection