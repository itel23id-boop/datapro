@extends('main-admin', ['activeMenu' => 'pesanan', 'activeSubMenu' => 'pesanan.otomatis'])

@section('content')
<!-- start page title -->
            <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Riwayat Pesanan</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										order
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
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box mb-30">
                            <div class="tab">
                                <ul class="nav nav-tabs" role="tablist">
            						<li class="nav-item">
            							<a class="nav-link active text-blue" data-toggle="tab" href="#success" role="tab" aria-selected="true">Success</a>
            						</li>
            						<li class="nav-item">
            							<a class="nav-link text-blue" data-toggle="tab" href="#processing" role="tab" aria-selected="true">Processing</a>
            						</li>
            						<li class="nav-item">
            							<a class="nav-link text-blue" data-toggle="tab" href="#pending" role="tab" aria-selected="true">Pending</a>
            						</li>
            						<li class="nav-item">
            							<a class="nav-link text-blue" data-toggle="tab" href="#failed" role="tab" aria-selected="true">Failed</a>
            						</li>
            					</ul>
            					<div class="tab-content">
            						<div class="tab-pane fade show active" id="success" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Pesanan Sukses</h4>
                                            <div class="alert alert-info mb-2 mt-2">
                                                Fitur ini hanya untuk transaksi <b>SUKSES</b>, dan transaksi auto via provider / via api
                                            </div>
                                            <div class="pb-20">
                                                <table class="data-table table stripe hover nowrap" id="successs">
                                                    <thead>
                                                        <tr>
                                                            <th class="data-table table-plus datatable-nosort">#</th>
                                                            <th>Tanggal</th>
                                                            <th>TRX ID</th>
                                                            <th>UID</th>
                                                            <th>Username</th>
                                                            <th>No. Whatsapp</th>
                                                            <th>Email</th>
                                                            <th>Produk</th>
                                                            <th>SN/Keterangan</th>
                                                            <th>Status</th>
                                                            <th>Pembayaran</th>
                                                            <th>Log</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no=1;?>
                                                        @foreach($data as $data_pesanan)
                                                        <?php 
                                                        $layanan = DB::table('layanans')->where('id',$data_pesanan->id_layanan)->first();
                                                        $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first();
                                                        ?>
                                                        @php
                                                        $label_pesanan = '';
                                                        if($data_pesanan->status == "Pending"){
                                                        $label_pesanan = 'warning';
                                                        }else if(in_array($data_pesanan->status, ["Processing","Prosess"])){
                                                        $label_pesanan = 'info';
                                                        }else if(in_array($data_pesanan->status, ["Success","Sukses"])){
                                                        $label_pesanan = 'success';
                                                        }else{
                                                        $label_pesanan = 'danger';
                                                        }
                                                        $ymdhis = explode(' ',$data_pesanan->created_at);
                                                        $month = [
                                                            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                            'Juli','Agustus','September','Oktober','November','Desember'
                                                        ];
                                                        $explode = explode('-', $ymdhis[0]);
                                                        $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                        @endphp
                                                        @if($data_pesanan->status == "Success")
                                                        <tr class="table-{{ $label_pesanan }}">
                                                            <th scope="row">{{ $no }}</th>
                                                            <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                            <td>
                                                                OID : #<a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">{{ $data_pesanan->order_id }}</a><br><hr>
                                                                POID : #{{ $data_pesanan->provider_order_id == null ? '-' : $data_pesanan->provider_order_id }}
                                                            </td>
                                                            <td>
                                                                {{ $data_pesanan->user_id }}<br><hr>
                                                            </td>
                                                            <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                            <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                            <td>{{ $data_pesanan->email_pembayaran == $data_pesanan->order_id.'@email.com' ? '-' : $data_pesanan->email_pembayaran }}</td>
                                                            <td>
                                                                {{ $kategori->nama }}<br><hr>
                                                                <small>{{ $data_pesanan->layanan }}</small><br><hr>
                                                                <small>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}</small>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" id="keterangan" value="{{ $data_pesanan->note }}" name="keterangan" class="form-control" disabled>
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text" onclick="copyApi()" title="Copy">
                                                                          <i class="micon dw dw-copy"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <small class="text-success info_copy d-none">SN/Keterangan Berhasil dicopy!</small>
                                                            </td>
                                                            @if(in_array($data_pesanan->status, ["Success","Refund"]))
                                                            <td>No Action</td>
                                                            @else
                                                            <td>
                                                                <div class="dropdown">
                                            						<a class="btn btn-{{$label_pesanan}} btn-sm dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            							{{ $data_pesanan->status }}</i>
                                            						</a>
                                            						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Success"> Success</a>
                                            							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Failed"> Failed</a>
                                            							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Processing"> Processing</a>
                                            							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Pending"> Pending</a>
                                            						</div>
                                            					</div>
                                                            </td>
                                                            @endif
                                                            <td>
                                                                Status : {{ $data_pesanan->status_pembayaran }}<br><hr>
                                                                Metode : {{ $data_pesanan->metode }}
                                                            </td>
                                                            <td><textarea class="form-control" rows="5" readonly>{{ $data_pesanan->log }}</textarea></td>
                                                        </tr>
                                                        @endif
                                                            <?php $no++ ;?>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="processing" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Pesanan Processing</h4>
                                            <div class="alert alert-info mb-2 mt-2">
                                                Fitur ini hanya untuk transaksi <b>PROSES</b>, dan transaksi auto via provider / via api
                                            </div>
                                            <div class="pb-20">
                                                <div class="table-responsive">
                                                    <table class="data-table table stripe hover nowrap" id="processings">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-plus datatable-nosort">#</th>
                                                                <th>Tanggal</th>
                                                                <th>TRX ID</th>
                                                                <th>UID</th>
                                                                <th>Username</th>
                                                                <th>No. Whatsapp</th>
                                                                <th>Email</th>
                                                                <th>Produk</th>
                                                                <th>SN/Keterangan</th>
                                                                <th>Status</th>
                                                                <th>Pembayaran</th>
                                                                <th>Log</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no=1;?>
                                                            @foreach($data as $data_pesanan)
                                                            <?php 
                                                            $layanan = DB::table('layanans')->where('id',$data_pesanan->id_layanan)->first();
                                                            $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first();
                                                            ?>
                                                            @php
                                                            $label_pesanan = '';
                                                            if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                            }else if(in_array($data_pesanan->status, ["Processing","Prosess"])){
                                                            $label_pesanan = 'info';
                                                            }else if(in_array($data_pesanan->status, ["Success","Sukses"])){
                                                            $label_pesanan = 'success';
                                                            }else{
                                                            $label_pesanan = 'danger';
                                                            }
                                                            $ymdhis = explode(' ',$data_pesanan->created_at);
                                                            $month = [
                                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                'Juli','Agustus','September','Oktober','November','Desember'
                                                            ];
                                                            $explode = explode('-', $ymdhis[0]);
                                                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                            @endphp
                                                            @if($data_pesanan->status == "Processing")
                                                            <tr class="table-{{ $label_pesanan }}">
                                                                <th scope="row">{{ $no }}</th>
                                                                <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                                <td>
                                                                    OID : #<a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">{{ $data_pesanan->order_id }}</a><br><hr>
                                                                    POID : #{{ $data_pesanan->provider_order_id == null ? '-' : $data_pesanan->provider_order_id }}
                                                                </td>
                                                                <td>
                                                                    {{ $data_pesanan->user_id }}<br><hr>
                                                                </td>
                                                                <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                                <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                                <td>{{ $data_pesanan->email_pembayaran == $data_pesanan->order_id.'@email.com' ? '-' : $data_pesanan->email_pembayaran }}</td>
                                                                <td>
                                                                    {{ $kategori->nama }}<br><hr>
                                                                    <small>{{ $data_pesanan->layanan }}</small><br><hr>
                                                                    <small>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}</small>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="text" id="keterangan" value="{{ $data_pesanan->note }}" name="keterangan" class="form-control" disabled>
                                                                        <div class="input-group-append">
                                                                            <div class="input-group-text" onclick="copyApi()" title="Copy">
                                                                              <i class="micon dw dw-copy"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <small class="text-success info_copy d-none">SN/Keterangan Berhasil dicopy!</small>
                                                                </td>
                                                                @if(in_array($data_pesanan->status, ["Success","Refund"]))
                                                                <td>No Action</td>
                                                                @else
                                                                <td>
                                                                    <div class="dropdown">
                                                						<a class="btn btn-{{$label_pesanan}} btn-sm dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                							{{ $data_pesanan->status }}</i>
                                                						</a>
                                                						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Success"> Success</a>
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Failed"> Failed</a>
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Processing"> Processing</a>
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Pending"> Pending</a>
                                                						</div>
                                                					</div>
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    Status : {{ $data_pesanan->status_pembayaran }}<br><hr>
                                                                    Metode : {{ $data_pesanan->metode }}
                                                                </td>
                                                                <td><textarea class="form-control" rows="5" readonly>{{ $data_pesanan->log }}</textarea></td>
                                                            </tr>
                                                            @endif
                                                                <?php $no++ ;?>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pending" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Pesanan Pending</h4>
                                            <div class="alert alert-info mb-2 mt-2">
                                                Fitur ini hanya untuk transaksi <b>PENDING</b>, dan transaksi auto via provider / via api
                                            </div>
                                            <div class="pb-20">
                                                <div class="table-responsive">
                                                    <table class="data-table table stripe hover nowrap" id="pendings">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-plus datatable-nosort">#</th>
                                                                <th>Tanggal</th>
                                                                <th>TRX ID</th>
                                                                <th>UID</th>
                                                                <th>Username</th>
                                                                <th>No. Whatsapp</th>
                                                                <th>Email</th>
                                                                <th>Produk</th>
                                                                <th>SN/Keterangan</th>
                                                                <th>Status</th>
                                                                <th>Pembayaran</th>
                                                                <th>Log</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no=1;?>
                                                            @foreach($data as $data_pesanan)
                                                            <?php 
                                                            $layanan = DB::table('layanans')->where('id',$data_pesanan->id_layanan)->first();
                                                            $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first();
                                                            ?>
                                                            @php
                                                            $label_pesanan = '';
                                                            if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                            }else if(in_array($data_pesanan->status, ["Processing","Prosess"])){
                                                            $label_pesanan = 'info';
                                                            }else if(in_array($data_pesanan->status, ["Success","Sukses"])){
                                                            $label_pesanan = 'success';
                                                            }else{
                                                            $label_pesanan = 'danger';
                                                            }
                                                            $ymdhis = explode(' ',$data_pesanan->created_at);
                                                            $month = [
                                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                'Juli','Agustus','September','Oktober','November','Desember'
                                                            ];
                                                            $explode = explode('-', $ymdhis[0]);
                                                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                            @endphp
                                                            @if($data_pesanan->status == "Pending")
                                                            <tr class="table-{{ $label_pesanan }}">
                                                                <th scope="row">{{ $no }}</th>
                                                                <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                                <td>
                                                                    OID : #<a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">{{ $data_pesanan->order_id }}</a><br><hr>
                                                                    POID : #{{ $data_pesanan->provider_order_id == null ? '-' : $data_pesanan->provider_order_id }}
                                                                </td>
                                                                <td>
                                                                    {{ $data_pesanan->user_id }}<br><hr>
                                                                </td>
                                                                <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                                <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                                <td>{{ $data_pesanan->email_pembayaran == $data_pesanan->order_id.'@email.com' ? '-' : $data_pesanan->email_pembayaran }}</td>
                                                                <td>
                                                                    {{ $kategori->nama }}<br><hr>
                                                                    <small>{{ $data_pesanan->layanan }}</small><br><hr>
                                                                    <small>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}</small>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="text" id="keterangan" value="{{ $data_pesanan->note }}" name="keterangan" class="form-control" disabled>
                                                                        <div class="input-group-append">
                                                                            <div class="input-group-text" onclick="copyApi()" title="Copy">
                                                                              <i class="micon dw dw-copy"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <small class="text-success info_copy d-none">SN/Keterangan Berhasil dicopy!</small>
                                                                </td>
                                                                @if(in_array($data_pesanan->status, ["Success","Refund"]))
                                                                <td>No Action</td>
                                                                @else
                                                                <td>
                                                                    <div class="dropdown">
                                                						<a class="btn btn-{{$label_pesanan}} btn-sm dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                							{{ $data_pesanan->status }}</i>
                                                						</a>
                                                						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Success"> Success</a>
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Failed"> Failed</a>
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Processing"> Processing</a>
                                                							<a class="dropdown-item" href="/order-status/{{ $data_pesanan->order_id }}/Pending"> Pending</a>
                                                						</div>
                                                					</div>
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    Status : {{ $data_pesanan->status_pembayaran }}<br><hr>
                                                                    Metode : {{ $data_pesanan->metode }}
                                                                </td>
                                                                <td><textarea class="form-control" rows="5" readonly>{{ $data_pesanan->log }}</textarea></td>
                                                            </tr>
                                                            @endif
                                                                <?php $no++ ;?>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="failed" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Pesanan Failed</h4>
                                            <div class="alert alert-info mb-2 mt-2">
                                                Fitur ini hanya untuk transaksi <b>FAILED/GAGAL</b>, dan transaksi auto via provider / via api
                                            </div>
                                            <div class="pb-20">
                                                <div class="table-responsive">
                                                    <table class="data-table table stripe hover nowrap" id="faileds">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-plus datatable-nosort">#</th>
                                                                <th>Tanggal</th>
                                                                <th>TRX ID</th>
                                                                <th>UID</th>
                                                                <th>Username</th>
                                                                <th>No. Whatsapp</th>
                                                                <th>Email</th>
                                                                <th>Produk</th>
                                                                <th>SN/Keterangan</th>
                                                                <th>Status</th>
                                                                <th>Pembayaran</th>
                                                                <th>Log</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no=1;?>
                                                            @foreach($data as $data_pesanan)
                                                            <?php 
                                                            $layanan = DB::table('layanans')->where('id',$data_pesanan->id_layanan)->first();
                                                            $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first();
                                                            ?>
                                                            @php
                                                            $label_pesanan = '';
                                                            if($data_pesanan->status == "Pending"){
                                                            $label_pesanan = 'warning';
                                                            }else if(in_array($data_pesanan->status, ["Processing","Prosess"])){
                                                            $label_pesanan = 'info';
                                                            }else if(in_array($data_pesanan->status, ["Success","Sukses"])){
                                                            $label_pesanan = 'success';
                                                            }else{
                                                            $label_pesanan = 'danger';
                                                            }
                                                            $ymdhis = explode(' ',$data_pesanan->created_at);
                                                            $month = [
                                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                'Juli','Agustus','September','Oktober','November','Desember'
                                                            ];
                                                            $explode = explode('-', $ymdhis[0]);
                                                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                            @endphp
                                                            @if($data_pesanan->status == "Failed")
                                                            <tr class="table-{{ $label_pesanan }}">
                                                                <th scope="row">{{ $no }}</th>
                                                                <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                                <td>
                                                                    OID : #<a href="{{env('APP_URL')}}/pembelian/invoice/{{ $data_pesanan->order_id }}" target="blank_">{{ $data_pesanan->order_id }}</a><br><hr>
                                                                    POID : #{{ $data_pesanan->provider_order_id == null ? '-' : $data_pesanan->provider_order_id }}
                                                                </td>
                                                                <td>
                                                                    {{ $data_pesanan->user_id }}<br><hr>
                                                                </td>
                                                                <td>{{ $data_pesanan->username ? $data_pesanan->username : 'Guest' }}</td>
                                                                <td>{{ $data_pesanan->no_pembeli_pembayaran }}</td>
                                                                <td>{{ $data_pesanan->email_pembayaran == $data_pesanan->order_id.'@email.com' ? '-' : $data_pesanan->email_pembayaran }}</td>
                                                                <td>
                                                                    {{ $kategori->nama }}<br><hr>
                                                                    <small>{{ $data_pesanan->layanan }}</small><br><hr>
                                                                    <small>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}</small>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="text" id="keterangan" value="{{ $data_pesanan->note }}" name="keterangan" class="form-control" disabled>
                                                                        <div class="input-group-append">
                                                                            <div class="input-group-text" onclick="copyApi()" title="Copy">
                                                                              <i class="micon dw dw-copy"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <small class="text-success info_copy d-none">SN/Keterangan Berhasil dicopy!</small>
                                                                </td>
                                                                @if(in_array($data_pesanan->status, ["Success","Refund"]))
                                                                <td>No Action</td>
                                                                @else
                                                                <td>{{ $data_pesanan->status }}</td>
                                                                @endif
                                                                <td>
                                                                    Status : {{ $data_pesanan->status_pembayaran }}<br><hr>
                                                                    Metode : {{ $data_pesanan->metode }}
                                                                </td>
                                                                <td><textarea class="form-control" rows="5" readonly>{{ $data_pesanan->log }}</textarea></td>
                                                            </tr>
                                                            @endif
                                                                <?php $no++ ;?>
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
<script>
    function copyApi() {
  
  let copyText = document.getElementById("keterangan");
  const info = document.querySelector(".info_copy");

  
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

 
  navigator.clipboard.writeText(copyText.value);
  
  info.classList.remove("d-none");
  
}
</script>
<script>
    $(document).ready(function(){
        $("#successs").DataTable({
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
    	$("#processings").DataTable({
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
    	$("#pendings").DataTable({
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
    	$("#faileds").DataTable({
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