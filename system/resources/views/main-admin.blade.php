<!DOCTYPE html>
<!--
* INGIN BUAT WEBSITE SEPERTI INI?
* CONTACT ME ON : 0813-1188-3274
-->
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>{{ !$config ? '' : $config->judul_web }}</title>
		<!-- Site favicon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ url('') }}{{ !$config ? '' : $config->logo_favicon }}" />
		<link rel="icon" type="image/png" sizes="32x32" href="{{ url('') }}{{ !$config ? '' : $config->logo_favicon }}" />
		<link rel="icon" type="image/png" sizes="16x16" href="{{ url('') }}{{ !$config ? '' : $config->logo_favicon }}" />

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/assets/vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="/assets/vendors/styles/icon-font.min.css" />
		<link rel="stylesheet" type="text/css" href="/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
		<link rel="stylesheet" type="text/css" href="/assets/vendors/styles/style.css?v=<?= time() ?>"/>
		<link rel="stylesheet" type="text/css" href="/assets/vendors/styles/styles.css?v=<?= time() ?>"/>
		<link rel="stylesheet" type="text/css" href="/assets/src/plugins/switchery/switchery.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
		<link rel="stylesheet" type="text/css" href="/assets/src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.css" />
        
        <!-- MORRIS CHARTS -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258" crossorigin="anonymous"></script>
		
		<script type="text/javascript">
            function modal_open(type, url) {
    			$('#modal').modal('show');
    			if (type == 'add') {
    				$('#modal-title').html('<i class="fa fa-plus-square"></i> Tambah Data');
    			} else if (type == 'edit') {
    				$('#modal-title').html('<i class="fa fa-edit"></i> Ubah Data');
    			} else if (type == 'delete') {
    				$('#modal-title').html('<i class="fa fa-trash"></i> Hapus Data');
    			} else if (type == 'detail') {
    				$('#modal-title').html('<i class="fa fa-search"></i> Detail Data');
    			} else {
    				$('#modal-title').html('Empty');
    			}
            	$.ajax({
            		type: "GET",
            		url: url,
            		beforeSend: function() {
            			$('#modal-detail-body').html('Sedang memuat...');
            		},
            		success: function(result) {
            			$('#modal-detail-body').html(result);
            		},
            		error: function() {
            			$('#modal-detail-body').html('Terjadi kesalahan.');
            		}
            	});
            	$('#modal-detail').modal();
            }
        </script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
	</head>

    <body>
        <div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="{{ url('') }}{{ !$config ? '' : $config->logo_header }}" alt="" style="width:auto;height:35px;"/>
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div>
		<div class="header">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
							<i class="dw dw-settings2"></i>
						</a>
					</div>
				</div>
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
							<span class="user-icon">
								<img src="/assets/vendors/images/photo1.jpg" alt="" />
							</span>
							<span class="user-name">Hallo Admin 🙂</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
							<form action="{{ route('logout') }}" method="POST">
							    @csrf
							    <a type="submit" class="dropdown-item" href="/logout"><i class="dw dw-logout"></i> Keluar</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="right-sidebar">
    		<div class="sidebar-title">
    			<h3 class="weight-600 font-16 text-blue">
    				Layout Settings
    				<span class="btn-block font-weight-400 font-12">User Interface Settings</span>
    			</h3>
    			<div class="close-sidebar" data-toggle="right-sidebar-close">
    				<i class="icon-copy ion-close-round"></i>
    			</div>
    		</div>
    		<div class="right-sidebar-body customscroll">
    			<div class="right-sidebar-body-content">
    				<h4 class="weight-600 font-18 pb-10">Header Background</h4>
    				<div class="sidebar-btn-group pb-30 mb-10">
    					<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
    					<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
    				</div>
    
    				<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
    				<div class="sidebar-btn-group pb-30 mb-10">
    					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light ">White</a>
    					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
    				</div>
    
    				<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
    				<div class="sidebar-radio-group pb-10 mb-10">
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked="">
    						<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2">
    						<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3">
    						<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
    					</div>
    				</div>
    
    				<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
    				<div class="sidebar-radio-group pb-30 mb-10">
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked="">
    						<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2">
    						<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3">
    						<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked="">
    						<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5">
    						<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
    					</div>
    					<div class="custom-control custom-radio custom-control-inline">
    						<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6">
    						<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
    					</div>
    				</div>
    
    				<div class="reset-options pt-30 text-center">
    					<button class="btn btn-danger" id="reset-settings">Reset Settings</button>
    				</div>
    			</div>
    		</div>
    	</div>
    	<div class="left-side-bar">
			<div class="brand-logo">
				<a href="javascript:void(0);">
					<img src="{{ url('') }}{{ !$config ? '' : $config->logo_header }}" alt="" class="dark-logo" onclick="window.location='{{ route('dashboard') }}'"/>
					<img src="{{ url('') }}{{ !$config ? '' : $config->logo_header }}" alt="" class="light-logo" onclick="window.location='{{ route('dashboard') }}'"/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			
			<div class="menu-block customscroll">
    			<div class="sidebar-menu">
    				<ul id="accordion-menu">
    				    @guest
    				    <li>
    						<a href="{{ route('home') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'home' ? 'active' : '' }}">
    							<span class="micon dw dw-house-1"></span><span class="mtext">Halaman Utama</span>
    						</a>
    					</li>
    					@endguest
    					@auth
    					<li>
    						<a href="{{ route('home') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'home' ? 'active' : '' }}">
    							<span class="micon dw dw-house-1"></span><span class="mtext">Halaman Utama</span>
    						</a>
    					</li>
                        <li>
    						<div class="sidebar-small-cap">Main Menu Admin</div>
    					</li>
    					<li>
    						<a href="{{ route('dashboard') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
    							<span class="micon dw dw-house-1"></span>
    							<span class="mtext">Dashboard</span>
    						</a>
    					</li>
    					<li>
    						<a href="{{ route('artikel-admin') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'artikel' ? 'active' : '' }}">
    							<span class="micon dw dw-newspaper"></span>
    							<span class="mtext">Artikel</span>
    						</a>
    					</li>
    					<li>
    						<a href="{{ route('log-website') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'log-website' ? 'active' : '' }}">
    							<span class="micon dw dw-diagonal-arrow-4"></span>
    							<span class="mtext">Log Website</span>
    						</a>
    					</li>
    					<li>
    						<a href="{{ route('top-ranking') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'top-ranking' ? 'active' : '' }}">
    							<i class="micon fa fa-trophy"></i>
    							<span class="mtext">Top Ranking</span>
    						</a>
    					</li>
    					<li class="dropdown">
    						<a href="javascript:;" class="dropdown-toggle {{ $activeMenu == 'produk' ? 'active' : '' }}">
    							<span class="micon dw dw-shopping-basket"></span><span class="mtext">Produk</span>
    						</a>
    						<ul class="submenu">
    						    <li><a href="{{ route('logo-product') }}" class="{{ $activeSubMenu == 'produk.logo' ? 'active' : '' }}">Logo Produk</a></li>
    						    <li><a href="{{ route('kategori-tipe') }}" class="{{ $activeSubMenu == 'produk.kategori-tipe' ? 'active' : '' }}">Kategori Tipe</a></li>
    						    <li><a href="{{ route('kategori') }}" class="{{ $activeSubMenu == 'produk.kategori' ? 'active' : '' }}">Kategori</a></li>
    							<li><a href="{{ route('layanan') }}" class="{{ $activeSubMenu == 'produk.layanan' ? 'active' : '' }}">Layanan</a></li>
    							<li><a href="{{ route('profit') }}" class="{{ $activeSubMenu == 'produk.profit' ? 'active' : '' }}">Profit</a></li>
    							<li><a href="{{ route('paket.index') }}" class="{{ $activeSubMenu == 'produk.paket' ? 'active' : '' }}">Paket</a></li>
    							<li><a href="{{ route('flashsale') }}" class="{{ $activeSubMenu == 'produk.flashsale' ? 'active' : '' }}">Flash Sale</a></li>
    							<li><a href="{{ route('voucher') }}" class="{{ $activeSubMenu == 'produk.voucher' ? 'active' : '' }}">Voucher</a></li>
    						</ul>
    					</li>
    					<li>
    						<a href="{{ route('member') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'member' ? 'active' : '' }}">
    							<span class="micon dw dw-user"></span>
    							<span class="mtext">Pengguna</span>
    						</a>
    					</li>
    					<li>
    						<a href="{{ route('laporan') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'laporan' ? 'active' : '' }}">
    							<span class="micon dw dw-analytics-11"></span>
    							<span class="mtext">Laporan Transaksi</span>
    						</a>
    					</li>
    					<li class="dropdown">
    						<a href="javascript:;" class="dropdown-toggle {{ $activeMenu == 'pesanan' ? 'active' : '' }}">
    							<span class="micon dw dw-shopping-cart"></span><span class="mtext">Pesanan</span>
    						</a>
    						<ul class="submenu">
    							<li><a href="{{ route('pesanan') }}" class="{{ $activeSubMenu == 'pesanan.otomatis' ? 'active' : '' }}">Proses Otomatis</a></li>
    							<li><a href="{{ url('/pesanan/manual') }}" class="{{ $activeSubMenu == 'pesanan.manual' ? 'active' : '' }}">Proses Manual</a></li>
    						</ul>
    					</li>
    					<li>
    						<a href="{{ route('history') }}" class="dropdown-toggle no-arrow {{ $activeMenu == 'deposit' ? 'active' : '' }}">
    							<span class="micon dw dw-money"></span>
    							<span class="mtext">History Pembayaran</span>
    						</a>
    					</li>
    					<li class="dropdown">
    						<a href="javascript:;" class="dropdown-toggle {{ $activeMenu == 'mutasi' ? 'active' : '' }}">
    							<span class="micon dw dw-book"></span><span class="mtext">Mutasi</span>
    						</a>
    						<ul class="submenu">
    							<li><a href="{{ route('ovo') }}" class="{{ $activeSubMenu == 'mutasi.ovo' ? 'active' : '' }}">Mutasi OVO</a></li>
    							<li><a href="{{ route('gopay') }}" class="{{ $activeSubMenu == 'mutasi.gopay' ? 'active' : '' }}">Mutasi GOPAY</a></li>
    						</ul>
    					</li>
    					<li class="dropdown">
    						<a href="javascript:;" class="dropdown-toggle {{ $activeMenu == 'konfigurasi' ? 'active' : '' }}">
    							<span class="micon dw dw-settings"></span><span class="mtext">Konfigurasi</span>
    						</a>
    						<ul class="submenu">
    						    <li><a href="{{ url('/pertanyaan-umum') }}" class="{{ $activeSubMenu == 'konfigurasi.pertanyaan-umum' ? 'active' : '' }}">Pertanyaan Umum</a></li>
    							<li><a href="{{ route('berita') }}" class="{{ $activeSubMenu == 'konfigurasi.berita' ? 'active' : '' }}">Slide / Banner</a></li>
    							<li><a href="{{ route('method') }}" class="{{ $activeSubMenu == 'konfigurasi.payment' ? 'active' : '' }}">Konfigurasi Payment</a></li>
    							<li><a href="{{ url('/setting/web') }}" class="{{ $activeSubMenu == 'konfigurasi.settingweb' ? 'active' : '' }}">Website</a></li>
    							<li><a href="{{ route('phone-country') }}" class="{{ $activeSubMenu == 'konfigurasi.phone-country' ? 'active' : '' }}">Konfigurasi Telepon Negara</a></li>
    						</ul>
    					</li>
    					@endauth
    				</ul>
    			</div>
    		</div>
		</div>
		<div class="mobile-menu-overlay"></div>
    		<div class="modal fade" id="modal" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="modal-header">
						    <h4 class="modal-title" id="modal-title"></h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
						</div>
						<div class="modal-body" id="modal-detail-body">
						</div>
					</div>
				</div>
			</div>
        <div class="main-container">
            <div class="xs-pd-20-10 pd-ltr-20">
                @yield('content')
                
                <div class="footer-wrap pd-20 mb-20 card-box mt-1">
    				<script>
                        document.write(new Date().getFullYear())
                    </script> &copy; {{ ENV('APP_NAME') }}
    			</div>
			</div>
        </div>
    <!-- END container -->
    <!-- js -->
    <script src="/assets/vendors/scripts/core.js"></script>
	<script src="/assets/vendors/scripts/script.js" type="text/javascript"></script>
	<script src="/assets/vendors/scripts/process.js"></script>
	<script src="/assets/vendors/scripts/layout-settings.js"></script>
	<script src="/assets/src/plugins/switchery/switchery.min.js"></script>
	<script src="/assets/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="/assets/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	<script src="/assets/src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
	<script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	@yield('js')
	<script>
    	@if(session('success'))
    	    toastr.success("{{ session('success') }}", "Success");
        @endif
        @if(session('error'))
    	    toastr.error("{{ session('error') }}", "Error");
        @endif
        CKEDITOR.replace( 'editor' );
        CKEDITOR.replace( 'deskripsi_web' );
        CKEDITOR.replace( 'description' );
        CKEDITOR.replace( 'alamat_web' );
        CKEDITOR.replace( 'snk-editor' );
        CKEDITOR.replace( 'privacy-editor' );
        CKEDITOR.replace('deskripsi_game');
        CKEDITOR.replace('deskripsi_popup');
        CKEDITOR.replace('pertanyaan_umum');
        CKEDITOR.replace('content');
    </script>
</body>

</html>