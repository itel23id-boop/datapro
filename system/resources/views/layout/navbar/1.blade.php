
<div class="header" role="banner">
	<div class="container d-none-print">
		<div class="menu">
			<div class="row">
				<div class="col-7">
					<a href="{{ route('home') }}">
						<img src="{{ !$config ? '' : $config->logo_header }}" alt="" class="logo-img" width="168">
					</a>
				</div>
				<div class="col-5">
					<div class="menu-right">
					    @if(Auth::check())
						<a href="/user/dashboard" class="text-decoration-none ms-3">
							<i class="fa-solid fa-circle-user fa-2xl" style="color: white;"></i>
						</a>
						<div class="dropdown d-inline-block">
							<div class="dropdown-toggle ms-3" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fa-solid fa-bars fa-2xl" style="color:white;"></i>
							</div>
							<ul class="dropdown-menu">
								<li>
									<a class="dropdown-item" href="{{ route('home') }}">
										<i class="fa fa-gamepad"></i>
										Topup Games
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('cari') }}">
										<i class="fa fa-search"></i>
										Cek Pesanan
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('price') }}">
										<i class="fa fa-tags"></i>
										Daftar Harga
									</a>
								</li>
								<li>
									<hr>
								</li>
								@if(Auth()->user()->role == 'Admin')
								<li>
									<a class="dropdown-item" href="/dashboard">
										<i class="fa fa-light fa-rocket"></i>
										Halaman Admin
									</a>
								</li>
								@endif
							</ul>
						</div>
					    @else
						<div class="dropdown d-inline-block">
							<div class="dropdown-toggle ms-3" data-bs-toggle="dropdown" aria-expanded="false">
								<!-- <img src="/assets/images/icon/menu.png" alt="" width="22"> -->
								<i class="fa-solid fa-bars fa-2xl" style="color:white;"></i>
							</div>
							<ul class="dropdown-menu">
								<li>
									<a class="dropdown-item" href="{{ route('home') }}">
										<i class="fa fa-gamepad"></i>
										Topup Games
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('cari') }}">
										<i class="fa fa-search"></i>
										Cek Pesanan
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('price') }}">
										<i class="fa fa-tags"></i>
										Daftar Harga
									</a>
								</li>
								<li>
									<hr>
								</li>
								<li>
									<div class="dropdown-auth">
										<div class="row">
											<div class="col-6">
												<a href="{{ route('login') }}" class="text-decoration-none text-white">
													<i class="fa fa-sign-in me-2"></i>
													Login
												</a>
											</div>
											<div class="col-6">
												<a href="{{ route('register') }}" class="text-decoration-none text-white">
													<i class="fa fa-user-plus me-2"></i>
													Daftar
												</a>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
