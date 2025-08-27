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
							<h6 class="card-title">Akun Saya</h6>
							<div class="users-menu">
								<a href="/user/dashboard" class=""><i class="fa fa-home me-3"></i>Dashboard</a>
								<a href="/user/orders" class=""><i class="fa fa-shopping-basket me-3"></i>Pembelian</a>
								<a href="/user/topup" class=""><i class="fa fa-wallet me-3"></i>Isi Saldo</a>
								<a href="/user/topup/riwayat" class="active"><i class="fa fa-shopping-basket me-3"></i>Riwayat Topup</a>
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
							<h6 class="card-title mb-4">Riwayat Isi Saldo</h6>
						</div>
						<div class="table-responsive">
							<table class="table border-top mb-0">
							    <thead>
    								<tr>
    									<th>Tanggal</th>
    									<th>Topup ID</th>
    									<th>Jumlah</th>
    									<th>Metode</th>
    									<th width="10">Status</th>
    								</tr>
								</thead>
    							<tbody>
    							@forelse($data as $pesanan)
								@php
                                    $zone = $pesanan->zone != null ? "-".$pesanan->zone : "";
                                    $label_pesanan = '';
                                    if($pesanan->status == "Menunggu Pembayaran" || $pesanan->status == "Belum Lunas"){
                                    $label_pesanan = 'warning';
                                    }else if($pesanan->status == "Lunas"){
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
									<td>{{ $formatted.' '.substr($ymdhis[1],0,5).' WIB' }}</td>
									<td>
										<a href="/topup/invoice/{{ $pesanan->order_id }}" class="text-decoration-none">
											#{{ $pesanan->order_id }}
										</a>
									</td>
									<td>Rp {{ number_format($pesanan->harga, 0, ',','.') }}</td>
									<td>{{ $pesanan->metode }}</td>
									<td>
										<span class="badge bg-{{ $label_pesanan }}">{{ $pesanan->status }}</span>
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

@endsection