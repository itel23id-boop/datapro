@extends('main-admin', ['activeMenu' => 'pesanan', 'activeSubMenu' => 'pesanan.manual'])

@section('content')
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Pesanan Manual</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/pesanan/manual
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="tab">
                                <ul class="nav nav-tabs" role="tablist">
        							<li class="nav-item">
        								<a class="nav-link active text-blue" data-toggle="tab" href="#manual" role="tab" aria-selected="true">Proses Trx Manual Payment</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#otomatis" role="tab" aria-selected="true">Proses Trx otomatis Failed</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#trxmanual" role="tab" aria-selected="true">Proses Trx Manual</a>
        							</li>
        						</ul>
                                <div class="tab-content">
        							<div class="tab-pane fade show active" id="manual" role="tabpanel">
        							    <div class="alert alert-info mb-2 mt-2">
                                            Fitur ini hanya untuk transaksi <b>OTOMATIS</b> dan pembayaran dengan provider <b>NON PAYMENT GATEWAY/TIDAK MEMAKAI PAYMENT GATEWAY</b>, jika pembeli sudah mengirim <b>BUKTI PEMBAYARAN</b> yang valid silahkan klik / pilih Detail untuk melanjutkan / proses transaksi
                                        </div>
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Riwayat Pesanan Manual</h4>
                                            <div class="pb-20">
                                                <div class="table-responsive">
                                                    <table class="data-table table stripe hover nowrap" id="manuals">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-plus datatable-nosort">OID</th>
                                                                <th>Tujuan</th>
                                                                <th>Username</th>
                                                                <th>No. Whatsapp</th>
                                                                <th>Status Pembayaran</th>
                                                                <th>Status Transaksi</th>
                                                                <th>Tanggal</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($data as $data_pesanan)
                                                            @php
                                                            $label_pesanan = '';
                                                            if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                            }else if($data_pesanan->status == "Processing"){
                                                            $label_pesanan = 'info';
                                                            }else if($data_pesanan->status == "Success"){
                                                            $label_pesanan = 'success';
                                                            }else{
                                                            $label_pesanan = 'danger';
                                                            }
                                                            if($data_pesanan->status_pembayaran == 'Lunas') {
                                                            $color = 'success';
                                                            } else if($data_pesanan->status_pembayaran == 'Menunggu Pembayaran') {
                                                            $color = 'warning';
                                                            } else {
                                                            $color = 'danger';
                                                            }
                                                            @endphp
                                                            <tr class="table-{{ $label_pesanan }}">
                                                                <th scope="row"><a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">#{{ $data_pesanan->order_id }}</a></th>
                                                                <td>{{ $data_pesanan->user_id }}</td>
                                                                <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                                <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{$color}}">{{ $data_pesanan->status_pembayaran }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-{{$label_pesanan}}">{{ $data_pesanan->status }}</span>
                                                                </td>
                                                                <td>{{ $data_pesanan->created_at }}</td>
                                                                @if(in_array($data_pesanan->status_pembayaran, ["Lunas","Expired"]))
                            									<td>Tidak ada aksi untuk pembayaran lunas / expired</td>
                            									@else
                            									<td>
                            										<a href="javascript:;" onclick="modal_open('detail', '{{ route('pesanan.detail', [$data_pesanan->order_id]) }}')" class="btn btn-link"><i class="icon-copy dw dw-eye"></i> Detail</a>
                            									</td>
                            									@endif
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="otomatis" role="tabpanel">
                                        <div class="alert alert-info mb-2 mt-2">
                                            Fitur ini untuk pesanan dengan status <b>FAILED</b> dengan pembayaran <b>LUNAS</b>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Riwayat Pesanan Otomatis</h4>
                                            <div class="pb-20">
                                                <div class="table-responsive">
                                                    <table class="data-table table stripe hover nowrap" id="otomatiss">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-plus datatable-nosort">OID</th>
                                                                <th>Tujuan</th>
                                                                <th>Username</th>
                                                                <th>No. Whatsapp</th>
                                                                <th>Status Pembayaran</th>
                                                                <th>Status Transaksi</th>
                                                                <th>Tanggal</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($data_otomatis as $data_pesanan)
                                                            @php
                                                            $label_pesanan = '';
                                                            if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                            }else if($data_pesanan->status == "Processing"){
                                                            $label_pesanan = 'info';
                                                            }else if($data_pesanan->status == "Success"){
                                                            $label_pesanan = 'success';
                                                            }else{
                                                            $label_pesanan = 'danger';
                                                            }
                                                            if($data_pesanan->status_pembayaran == 'Lunas') {
                                                            $color = 'success';
                                                            } else if($data_pesanan->status_pembayaran == 'Menunggu Pembayaran') {
                                                            $color = 'warning';
                                                            } else {
                                                            $color = 'danger';
                                                            }
                                                            @endphp
                                                            <tr class="table-{{ $label_pesanan }}">
                                                                <th scope="row"><a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">#{{ $data_pesanan->order_id }}</a></th>
                                                                <td>{{ $data_pesanan->user_id }}</td>
                                                                <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                                <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{$color}}">{{ $data_pesanan->status_pembayaran }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-{{$label_pesanan}}">{{ $data_pesanan->status }}</span>
                                                                </td>
                                                                <td>{{ $data_pesanan->created_at }}</td>
                                                                @if(in_array($data_pesanan->status, ["Success","Refund","Pending","Processing"]) || in_array($data_pesanan->status_pembayaran, ["Expired","Menunggu Pembayaran"]))
                            									<td>Tidak ada aksi untuk pembayaran lunas / expired</td>
                            									@else
                            									<td>
                            										<a href="javascript:;" onclick="modal_open('detail', '{{ route('pesanan.failed.detail', [$data_pesanan->order_id]) }}')" class="btn btn-link"><i class="icon-copy dw dw-eye"></i> Detail</a>
                            									</td>
                            									@endif
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="trxmanual" role="tabpanel">
                                        <div class="alert alert-info mb-2 mt-2">
                                            Fitur ini untuk pesanan dengan status <b>Pending</b> dengan pembayaran <b>Belum Lunas</b>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Riwayat Pesanan Otomatis</h4>
                                            <div class="pb-20">
                                                <div class="table-responsive">
                                                    <table class="data-table table stripe hover nowrap" id="manualss">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-plus datatable-nosort">OID</th>
                                                                <th>Tujuan</th>
                                                                <th>Username</th>
                                                                <th>No. Whatsapp</th>
                                                                <th>Status Pembayaran</th>
                                                                <th>Status Transaksi</th>
                                                                <th>Tanggal</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($data_manual as $data_pesanan)
                                                            <?php 
                                                            $layanan = DB::table('layanans')->where('id',$data_pesanan->id_layanan)->first();
                                                            $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first();
                                                            ?>
                                                            @php
                                                            $label_pesanan = '';
                                                            if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                            }else if($data_pesanan->status == "Processing"){
                                                            $label_pesanan = 'info';
                                                            }else if($data_pesanan->status == "Success"){
                                                            $label_pesanan = 'success';
                                                            }else{
                                                            $label_pesanan = 'danger';
                                                            }
                                                            if($data_pesanan->status_pembayaran == 'Lunas') {
                                                            $color = 'success';
                                                            } else if($data_pesanan->status_pembayaran == 'Menunggu Pembayaran') {
                                                            $color = 'warning';
                                                            } else {
                                                            $color = 'danger';
                                                            }
                                                            @endphp
                                                            @if($layanan->provider == "manual")
                                                            <tr class="table-{{ $label_pesanan }}">
                                                                <th scope="row"><a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">#{{ $data_pesanan->order_id }}</a></th>
                                                                <td>{{ $data_pesanan->user_id }}</td>
                                                                <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                                <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{$color}}">{{ $data_pesanan->status_pembayaran }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-{{$label_pesanan}}">{{ $data_pesanan->status }}</span>
                                                                </td>
                                                                <td>{{ $data_pesanan->created_at }}</td>
                                                                @if(in_array($data_pesanan->status, ["Success","Refund"]) || in_array($data_pesanan->status_pembayaran, ["Expired"]))
                            									<td>Tidak ada aksi untuk pembayaran lunas / expired</td>
                            									@else
                            									<td>
                            										<a href="javascript:;" onclick="modal_open('detail', '{{ route('pesanan.pending.detail', [$data_pesanan->order_id]) }}')" class="btn btn-link"><i class="icon-copy dw dw-eye"></i> Detail</a>
                            									</td>
                            									@endif
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
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#otomatiss').DataTable({
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
        $('#manuals').DataTable({
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
        $('#manualss').DataTable({
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
    $('.kategori').change(function(){
         const data = $(this).val();
         $.ajax({
            url: "{{url('/pesanan/manual/ajax/layanan')}}",
            method: "POST",
            data: {data:data,_token:"{{csrf_token()}}"},
            success:function(res){
              $('.layanan').empty();
              $('.layanan').append(res);
            }
         });
    });
    
</script>



@endsection