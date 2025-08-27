@extends('main-admin', ['activeMenu' => 'dashboard', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Admin Dashboard</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="">Admin</a></li>
					<li class="breadcrumb-item active" aria-current="page">
						/dashboard
					</li>
				</ol>
			</nav>
		</div>
	</div>
</div>
<!-- end page title -->
<!-- stats with icon -->
<div class="text-center">
    <h4 class="page-title">Laporan Hari Ini</h4>
</div>
<div class="row mt-2">
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN HARI INI</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_pembelian, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_pembelian }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-primary" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN BERHASIL HARI INI</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_pembelian_success, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_pembelian_success }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-success" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN PENDING HARI INI</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_pembelian_pending, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_pembelian_pending }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-info" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN BATAL HARI INI</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_pembelian_batal, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_pembelian_batal }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-danger" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH DEPOSIT HARI INI</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_deposit, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_deposit }}x pembayaran</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-primary" data-feather="dollar-sign"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center mt-2">
    <h4 class="page-title">Laporan Keseluruhan</h4>
</div>
<div class="row mt-2">
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN KESELURUHAN</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_keseluruhan_pembelian, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_keseluruhan_pembelian }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-primary" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN BERHASIL KESELURUHAN</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_keseluruhan_pembelian_berhasil, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_keseluruhan_pembelian_berhasil }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-success" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN PENDING KESELURUHAN</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_keseluruhan_pembelian_pending, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_keseluruhan_pembelian_pending }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-info" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH PESANAN BATAL KESELURUHAN</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_keseluruhan_pembelian_batal, '0', '.', ',') }}</h3>
                        <small>Dengan total {{ $banyak_keseluruhan_pembelian_batal }}x pemesanan</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-danger" data-feather="shopping-bag"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-6">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SELURUH DEPOSIT KESELURUHAN</span>
                        <h3 class="mb-0">Rp. {{ number_format($total_keseluruhan_deposit, '0','.',',') }}</h3>
                        <small>Dengan total {{ $banyak_keseluruhan_deposit }}x pembayaran</small>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-warning" data-feather="dollar-sign"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-12">
        <div class="card-box mb-30">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <span class="text-muted text-uppercase fs-12 fw-bold">KEUNTUNGAN BERSIH KESELURUHAN</span>
                        <h3 class="mb-0">Rp. {{ number_format($keuntungan_bersih, '0','.',',') }}</h3>
                    </div>
                    <div class="align-self-center flex-shrink-0">
                        <span class="icon-lg icon-dual-info" data-feather="trending-up"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-box mb-30">
    <div class="card-body">
        <span class="text-muted text-uppercase fs-12 fw-bold">GRAFIK PESANAN 7 HARI TERAKHIR</span>

        <div id="order-chart"></div>
    </div>
</div>
<!-- icon end -->
<script type="text/javascript">
    $(function() {
        new Morris.Area({
            element: 'order-chart',
            data: <?php echo $morris_data; ?>,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Pesanan'],
            lineColors: ['#188ae2'],
            gridLineColor: '#eef0f2',
            pointSize: 0,
            lineWidth: 0,
            resize: true,
            parseTime: false,
        });
    });
</script>
@endsection