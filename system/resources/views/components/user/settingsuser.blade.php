@extends("main")

@section("content")
    <div class="content-main mt-5">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="card mb-4 border-0 shadow-form">
						<div class="card-body">
							<h6 class="shadow-text card-title">Akun Saya</h6>
							<div class="users-menu">
								<a href="/user/dashboard" class=""><i class="fa fa-home me-3"></i>Dashboard</a>
								<a href="/user/orders" class=""><i class="fa fa-shopping-basket me-3"></i>Pembelian</a>
								<a href="/user/topup" class=""><i class="fa fa-wallet me-3"></i>Isi Saldo</a>
								<a href="/user/topup/riwayat" class=""><i class="fa fa-shopping-basket me-3"></i>Riwayat Topup</a>
								<a href="/user/settings" class="active"><i class="fa fa-cogs me-3"></i>Pengaturan</a>
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
						<div class="card-body">
							<h6 class="card-title">Pengaturan</h6>
							<form action="{{ route('setting') }}" method="POST">
							    @csrf
								<div class="mb-3">
									<input type="text" class="form-control form-control-games" placeholder="Username" readonly="" value="{{ $data->username }}">
								</div>
								<div class="mb-3">
									<input type="text" class="form-control form-control-games" placeholder="No.Whatsapp" readonly="" value="{{ $data->no_wa }}">
									<small class="text-muted">Hubungi admin untuk mengubah Nomor WhatsApp</small>
								</div>
								<div class="mb-3">
									<input type="text" class="form-control form-control-games" placeholder="Nama" value="{{ $data->name }}" name="name">
									@error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
								</div>
								<div class="mb-3">
									<input type="text" class="form-control form-control-games" placeholder="Email" value="{{ $data->email }}" name="email">
									@error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
								</div>
								<div class="mb-3">
									<input type="password" class="form-control form-control-games mb-1" placeholder="Password Baru" name="password">
									<small class="text-muted">Hanya diisi ketika ingin mengganti password akun</small>
								</div>
								<div class="text-end">
									<button class="btn btn-primary" type="submit" name="tombol" value="submit">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
@endsection