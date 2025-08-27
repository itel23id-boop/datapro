@extends("main-admin", ['activeMenu' => 'deposit', 'activeSubMenu' => ''])

@section("content")
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Riwayat Pembayaran</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/history-pembayaran
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @elseif(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card-box mb-30">
                            <div class="tab">
                                <ul class="nav nav-tabs" role="tablist">
            						<li class="nav-item">
            							<a class="nav-link active text-blue" data-toggle="tab" href="#deposit" role="tab" aria-selected="true">Deposit</a>
            						</li>
            						<li class="nav-item">
            							<a class="nav-link text-blue" data-toggle="tab" href="#upgrade" role="tab" aria-selected="true">Upgrade</a>
            						</li>
            					</ul>
            					<div class="tab-content">
            					    <div class="tab-pane fade show active" id="deposit" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="page-title text-dark">Riwayat deposit</h4>
                                            <div class="alert alert-info mb-2 mt-2">
                                                Fitur ini hanya untuk transaksi <b>DEPOSIT MANUAL</b>
                                            </div>
                                            <div class="pb-20">
                                                <table class="data-table table stripe hover nowrap" id="deposits">
                                                    <thead>
                                                        <tr>
                                                            <th class="table-plus datatable-nosort">ID</th>
                                                            <th>Tanggal</th>
                                                            <th>Username</th>
                                                            <th>Jumlah</th>
                                                            <th>Metode</th>
                                                            <th>No Pembayaran</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data as $data_pesanan)
                                                        @php
                                                        $label_pesanan = '';
                                                        if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                        }else if($data_pesanan->status == "Success"){
                                                            $label_pesanan = 'success';
                                                        }else{
                                                            $label_pesanan = 'danger';
                                                        }
                                                        @endphp
                                                        @if($data_pesanan->type == "deposit")
                                                        <tr class="table-{{ $label_pesanan }}">
                                                            <th scope="row">{{ $data_pesanan->id }}</th>
                                                            <td>{{ $data_pesanan->created_at }}</td>
                                                            <td>{{ $data_pesanan->username }}</td>
                                                            <td>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}</td>
                                                            <th>{{ $data_pesanan->metode }}</th>
                                                            @if($data_pesanan->metode_tipe == "qris")
                                                            <td><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{ $data_pesanan->no_pembayaran }}&choe=UTF-8" width="200" class="rounded-3"></td>
                                                            @elseif($data_pesanan->metode_code == "DANA")
                                                            <td><a class="btn btn-warning btn-sm" href="{{ $data_pesanan->no_pembayaran }}" target="blank_"> Bayar Sekarang</a></td>
                                                            @else
                                                            <td>{{ $data_pesanan->no_pembayaran }}</td>
                                                            @endif
                                                            <td>{{ $data_pesanan->status }}</td>
                                                            <td>
                                                                <a href="{{ route('confirm.history', [$data_pesanan->id,'Lunas']) }}" class="btn btn-success">Lunas</a><br>
                                                                <a href="{{ route('confirm.history', [$data_pesanan->id,'Expired']) }}" class="btn btn-danger mt-1">Expired</a>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="upgrade" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="page-title text-dark">Riwayat upgrade</h4>
                                            <div class="alert alert-info mb-2 mt-2">
                                                Fitur ini hanya untuk transaksi <b>UPGRADE USER / UPGRADE LEVEL USER</b>
                                            </div>
                                            <div class="pb-20">
                                                <table class="data-table table stripe hover nowrap" id="upgrades">
                                                    <thead>
                                                        <tr>
                                                            <th class="table-plus datatable-nosort">ID</th>
                                                            <th>Tanggal</th>
                                                            <th>Username</th>
                                                            <th>Req. Role</th>
                                                            <th>Jumlah</th>
                                                            <th>Metode</th>
                                                            <th>No Pembayaran</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data as $data_pesanan)
                                                        @php
                                                        $label_pesanan = '';
                                                        if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                        }else if($data_pesanan->status == "Success"){
                                                            $label_pesanan = 'success';
                                                        }else{
                                                            $label_pesanan = 'danger';
                                                        }
                                                        @endphp
                                                        @if($data_pesanan->type == "upgrade")
                                                        <tr class="table-{{ $label_pesanan }}">
                                                            <th scope="row">{{ $data_pesanan->id }}</th>
                                                            <td>{{ $data_pesanan->created_at }}</td>
                                                            <td>{{ $data_pesanan->username }}</td>
                                                            <td>{{ $data_pesanan->role }}</td>
                                                            <td>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}</td>
                                                            <th>{{ $data_pesanan->metode }}</th>
                                                            @if($data_pesanan->metode_tipe == "qris")
                                                            <td><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{ $data_pesanan->no_pembayaran }}&choe=UTF-8" width="200" class="rounded-3"></td>
                                                            @elseif($data_pesanan->metode_code == "DANA")
                                                            <td><a class="btn btn-warning btn-sm" href="{{ $data_pesanan->no_pembayaran }}" target="blank_"> Bayar Sekarang</a></td>
                                                            @else
                                                            <td>{{ $data_pesanan->no_pembayaran }}</td>
                                                            @endif
                                                            <td>{{ $data_pesanan->status }}</td>
                                                            <td>
                                                                <a href="{{ route('confirm.history', [$data_pesanan->id,'Lunas']) }}" class="btn btn-success">Lunas</a><br>
                                                                <a href="{{ route('confirm.history', [$data_pesanan->id,'Expired']) }}" class="btn btn-danger mt-1">Expired</a>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<script>
    $(document).ready(function(){
        $('#deposits').DataTable({
            order: [[0, 'desc']],
    		scrollCollapse: true,
    		autoWidth: false,
    		responsive: true,
    		columnDefs: [{
    			targets: "datatable-nosort",
    			orderable: false,
    		}],
    		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    		"language": {
    			"info": "_START_-_END_ of _TOTAL_ entries",
    			searchPlaceholder: "Search",
    			paginate: {
    				next: '<i class="ion-chevron-right"></i>',
    				previous: '<i class="ion-chevron-left"></i>'  
    			}
    		},
    	});
    });
    $(document).ready(function(){
        $("#upgrades").DataTable({
            order: [[0, 'desc']],
    		scrollCollapse: true,
    		autoWidth: false,
    		responsive: true,
    		columnDefs: [{
    			targets: "datatable-nosort",
    			orderable: false,
    		}],
    		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    		"language": {
    			"info": "_START_-_END_ of _TOTAL_ entries",
    			searchPlaceholder: "Search",
    			paginate: {
    				next: '<i class="ion-chevron-right"></i>',
    				previous: '<i class="ion-chevron-left"></i>'  
    			}
    		},
    	});
    });
</script>
@endsection