@extends("main")

@section("content")
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
								<a href="/user/topup/riwayat" class=""><i class="fa fa-shopping-basket me-3"></i>Riwayat Topup</a>
								<a href="/user/settings" class=""><i class="fa fa-cogs me-3"></i>Pengaturan</a>
								<a href="/user/upgrade" class="active"><i class="fa fa-users me-3"></i>Upgrade</a>
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
						<div class="card-body">
							<h6 class="card-title">Upgrade #{{ $data->order_id }}</h6>
							<a href="/user/upgrade/riwayat" class="text-decoration-none mb-2 d-block">
								<i class="fa fa-arrow-left"></i> Kembali
							</a>
							@if($data->status == "Menunggu Pembayaran")
							<div class="alert text-center" style="background: var(--warna_4);">
								<b class="d-block">Harap segera melakukan pembayaran sebelum</b>
								<h6 class="mb-0 fw-700 text-danger"><span class="h6 text-danger" id="countdown"></span></h6>
							</div>
							@elseif($data->status == "Lunas")
							<div class="alert alert-success text-center">
								<b class="d-block">Pembayaran Telah Lunas!</b>
							</div>
							@elseif($data->status == "Expired")
							<div class="alert alert-primary text-center">
								<b class="d-block">Pembayaran Telah Kadaluarsa !</b>
							</div>
							@endif
							<div class="table-responsive">
								<table class="table">
									<tr>
										<th>Tanggal</th>
										@php
                                        $ymdhis = explode(' ',$data->created_at);
                                        $month = [
                                            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                            'Juli','Agustus','September','Oktober','November','Desember'
                                        ];
                                        $explode = explode('-', $ymdhis[0]);
                                        $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                        @endphp
										<td align="right">{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
									</tr>
									<tr>
										<th>Metode</th>
										<td align="right">{{ $data->metode }}</td>
									</tr>
									<tr>
										<th>Status</th>
										<td align="right">
										    @php
										    if($data->status == "Menunggu Pembayaran") {
										        $color = 'warning';
										    }elseif($data->status == "Lunas") {
										        $color = 'success';
										    }else{
										        $color = 'danger';
										    } 
										    @endphp
											<span class="badge bg-{{$color}}">{{ $data->status }}</span>
										</td>
									</tr>
									<tr>
										<th>Role</th>
										<td align="right">Rp {{ $data->role }}</td>
									</tr>
									<tr>
										<th>Total</th>
										<td align="right">Rp {{ number_format($data->harga, 0, ',','.') }}</td>
									</tr>
								</table>
								@if($data->status == "Menunggu Pembayaran")
								<div class="">
									<h6 class="fw-700 mb-0">Pembayaran</h6>
									@if($data->metode_tipe == "qris" AND in_array($data->provider, ["linkqu","tripay","ipaymu","duitku","tokopay"]))
									<p class="mb-3">Silahkan scan QR berikut untuk melakukan pembayaran</p>
									<img src="https://quickchart.io/qr?text={{ $data->no_pembayaran }}" width="200" class="rounded-3">
									@elseif($data->metode_tipe == "qris" AND $data->provider == "manual")
									<p class="mb-3">Silahkan scan QR berikut untuk melakukan pembayaran</p>
									<img src="{{ $data->no_pembayaran }}" width="200" class="rounded-3">
									@elseif($data->metode_tipe == "e-walet" AND in_array($data->provider, ["linkqu","tripay","ipaymu","duitku","tokopay"]))
									<p class="mb-3">Silahkan Klik Link berikut untuk melakukan pembayaran</p>
									<a href="{{ $data->checkout_url }}" target="blank_"> Klik Disini</a>
									@elseif($data->metode_tipe == "e-walet" AND $data->provider == "manual")
									<p class="mb-0">Silahkan melakukan pembayaran ke nomor pembayaran berikut</p>
									Kode Pembayaran : <b class="text-primary">{{ $data->no_pembayaran }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->no_pembayaran }}', 'Kode Pembayaran berhasil disalin');"></i></b>
									@else
									<p class="mb-0">Silahkan melakukan pembayaran ke nomor pembayaran berikut</p>
									Kode Pembayaran : <b class="text-primary">{{ $data->no_pembayaran }} <i class="fa fa-copy ms-1" onclick="salin('{{ $data->no_pembayaran }}', 'Kode Pembayaran berhasil disalin');"></i></b>
									@endif
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
@endsection
@section('js')
<script>
CountDownTimer('{{$expired}}', 'countdown');
function CountDownTimer(dt, id) {
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
@endsection