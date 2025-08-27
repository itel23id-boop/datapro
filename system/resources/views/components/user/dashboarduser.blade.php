@extends("main")

@section("content")
<style>
    .card-waves {
		margin-top: -28px;
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
								<a href="/user/dashboard" class="active"><i class="fa fa-home me-3"></i>Dashboard</a>
								<a href="/user/orders" class=""><i class="fa fa-shopping-basket me-3"></i>Pembelian</a>
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
					<div class="card">
						<div class="card-body pb-0">
							<i class="fa-solid fa-circle-user fa-2xl" style="color: var(--warna_2);"></i>
							<a href="/user/settings">
								<i class="fa fa-edit float-end fs-18 mt-1 text-white"></i>
							</a>
							<b class="ms-2">{{Str::title(Auth()->user()->name)}}</b>
							<hr>
							<a href="/user/topup" class="btn btn-primary btn-sm float-end">Isi Saldo</a>
							<p class="mb-0">Sisa Saldo</p>
							<h6 class="mb-0">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</h6>
							<hr>
							<a href="/user/upgrade" class="btn btn-primary btn-sm float-end">Upgrade</a>
							<p class="mb-0">Role</p>
							<h6 class="mb-0">{{Auth()->user()->role}}</h6>
						</div>
					</div>
					<div class="card-waves">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="var(--warna_4)" fill-opacity="1" d="M0,224L1440,64L1440,0L0,0Z"></path></svg>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
@endsection