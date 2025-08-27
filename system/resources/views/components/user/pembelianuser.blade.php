@extends("main")

@section("content")
    <style>
		tr:last-child td {
			border-bottom: none;
		}
	</style>
    <div class="content-main mt-5">
		<div class="container">
			<div class="row">
				
				<div class="col-md-4">
					<div class="card mb-4 border-0 shadow-form">
						<div class="card-body">
							<h6 class="shadow-text card-title">Akun Saya</h6>
							<div class="users-menu">
								<a href="/user/dashboard" class=""><i class="fa fa-home me-3"></i>Dashboard</a>
								<a href="/user/orders" class="active"><i class="fa fa-shopping-basket me-3"></i>Pembelian</a>
								<a href="/user/topup" class=""><i class="fa fa-wallet me-3"></i>Isi Saldo</a>
								<a href="/user/topup/riwayat" class=""><i class="fa fa-shopping-basket me-3"></i>Riwayat Topup</a>
								<a href="/user/settings" class=""><i class="fa fa-cogs me-3"></i>Pengaturan</a>
								<a href="/user/upgrade" class=""><i class="fa fa-users me-3"></i>Upgrade</a>
								<hr>
								<form action="{{ route('logout') }}" method="POST">
								    @csrf
								    <a href="/logout"><i class="fa fa-sign-out me-3"></i>Logout</a>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="card border-0 shadow-form">
						<div class="card-body pb-3">
							<h6 class="card-title">5 Pembelian Terbaru</h6>
							<form action="" method="POST">
							    @csrf
								<div class="row">
									<div class="col-md-5">
										<input type="text" class="form-control form-control-games mb-3" name="from">
									</div>
									<div class="col-md-5">
										<input type="text" class="form-control form-control-games mb-3" name="to">
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary btn-filter w-100-mobile" type="submit" name="tombol" value="submit">Filter</button>
									</div>
								</div>
							</form>
						</div>
						<div class="table-responsive">
							<table class="table border-top mb-0">
							    <thead>
    								<tr>
    									<th>Tanggal</th>
    									<th>Order ID</th>
    									<th>Produk</th>
    									<th>Tujuan</th>
    									<th width="10">Status</th>
    								</tr>
    							</thead>
    							<tbody>
								@forelse($data as $pesanan)
								@php
                                    $label_pesanan = '';
                                    if($pesanan->status == "Pending" || $pesanan->status == "Menunggu Pembayaran" || $pesanan->status == "Waiting"){
                                    $label_pesanan = 'warning';
                                    }else if($pesanan->status == "Processing" || $pesanan->status == 'Proses'){
                                    $label_pesanan = 'info';
                                    }else if($pesanan->status == "Success" || $pesanan->status == 'Sukses'){
                                    $label_pesanan = 'success';
                                    }else{
                                    $label_pesanan = 'danger';
                                    }
                                $ymdhis = explode(' ',$pesanan->created_at);
                                $month = [
                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                    'Juli','Agustus','September','Oktober','November','Desember'
                                ];
                                $explode = explode('-', $ymdhis[0]);
                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                @endphp 
								<tr>
									<td><small>{{ $formatted.' '.substr($ymdhis[1],0,5).' WIB' }}</small></td>
									<td>
										<small><a href="/pembelian/invoice/{{ $pesanan->order_id }}" class="text-decoration-none" style="color:white;">
											#{{ $pesanan->order_id }}
										</a></small>
									</td>
									<td><small>{{ $pesanan->layanan }}</small></td>
									<td><small>{{ $pesanan->user_id }}</small></td>
									<td>
										<small><span class="badge bg-{{ $label_pesanan }}">{{ $pesanan->status }}</small></span>
									</td>
								</tr>
								@empty
                                <tr>
                                    <td align="center" colspan="5">Tidak ada riwayat</td>
                                </tr>
								@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
    <script>
		$('input[name="from"]').daterangepicker({
		    singleDatePicker: true,
			startDate: '{{ $from }}'
		});
		$('input[name="to"]').daterangepicker({
		    singleDatePicker: true,
			endDate: '{{ $to }}'
		});
	</script>
@endsection