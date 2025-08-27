@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.voucher'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Voucher</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/voucher
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
                <div class="card-box mb-30">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Tambah Voucher</h4>
                        <form action="{{ route('voucher.post') }}" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Global?</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="globals" id="global">
                                        <option value="" disabled selected>--Pilih Global--</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row d-none" id="d-none">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kategori</label>
                                <div class="col-lg-10">
                                    <select class="form-control " name="kategori_id" id="form_global">
                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kode</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" name="kode">
                                    @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Persenan Promo</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control @error('promo') is-invalid @enderror" value="{{ old('promo') }}" name="promo" placeholder="10">
                                    @error('promo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kuota</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" name="stock" placeholder="10">
                                    @error('stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Minimal Transaksi</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control @error('min_transaksi') is-invalid @enderror" value="{{ old('min_transaksi') }}" name="min_transaksi" placeholder="100000">
                                    @error('min_transaksi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Max Potongan</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control @error('max_potongan') is-invalid @enderror" value="{{ old('max_potongan') }}" name="max_potongan" placeholder="100000">
                                    @error('max_potongan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Voucher Versi</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="versi" onchange="get_versi(this.value)">
                                        <option value="" disabled selected>--Pilih versi--</option>
                                        <option value="public">Public</option>
                                        <option value="login">Login</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3 row d-none" id="versi-batasi">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Limit Voucher Login</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control @error('limit_voucher_login') is-invalid @enderror" value="{{ old('limit_voucher_login') }}" name="limit_voucher_login" placeholder="10">
                                    @error('limit_voucher_login')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Role</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="role">
                                        <option value="" disabled selected>--Pilih Role--</option>
                                        <option value="Member">Member</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="VIP">VIP</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Expired</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control datetimepicker @error('expired') is-invalid @enderror" value="{{ old('expired') }}" name="expired" placeholder="Choose Date and time">
                                    @error('expired')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="tab">
                                <ul class="nav nav-tabs" role="tablist">
                        			<li class="nav-item">
                        				<a class="nav-link active text-blue" data-toggle="tab" href="#riwayat-voucher" role="tab" aria-selected="true">Riwayat Voucher</a>
                        			</li>
                        			<li class="nav-item">
                        				<a class="nav-link text-blue" data-toggle="tab" href="#riwayat-voucher-login" role="tab" aria-selected="false">Riwayat Voucher Limit (LOGIN)</a>
                        			</li>
                        		</ul>
                        		<div class="tab-content">
                    				<div class="tab-pane fade show active" id="riwayat-voucher" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Semua Voucher</h4>
                                            <div class="pb-20">
                                                <div class="alert alert-info mb-2 mt-2">
                                                    - Kolom warna <span class="badge badge-success">HIJAU</span> = Not Expired / Belum kadaluarsa<br>
                                                    - Kolom warna <span class="badge badge-danger">MERAH</span> = Expired / Sudah kadaluarsa
                                                </div>
                                                <table class="data-table table stripe hover nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th class="table-plus datatable-nosort">#</th>
                                                            <th>Tanggal</th>
                                                            <th>Global</th>
                                                            <th>Kategori</th>
                                                            <th>Kode</th>
                                                            <th>Potongan Persenan</th>
                                                            <th>Kuota</th>
                                                            <th>Min Transaksi</th>
                                                            <th>Max Potongan</th>
                                                            <th>Voucher Versi</th>
                                                            <th>Limit Voucher Login</th>
                                                            <th>Role</th>
                                                            <th>Expired</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $vouchers as $data )
                                                        <?php $kategori = DB::table('kategoris')->where('id',$data->kategori_id)->first(); ?>
                                                        @php
                                                        if($data->globals == 1) {
                                                            $badge = 'success';
                                                        } else {
                                                            $badge = 'danger';
                                                        }
                                                        if($data->versi == "login") {
                                                            $badges = 'success';
                                                        } else {
                                                            $badges = 'danger';
                                                        }
                                                        $label_pesanan = '';
                                                        if(date('Y-m-d H:i:s') > $data->expired){
                                                            $label_pesanan = 'danger';
                                                        }else{
                                                            $label_pesanan = 'success';
                                                        }
                                                        $ymdhis = explode(' ',$data->created_at);
                                                        $month = [
                                                            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                            'Juli','Agustus','September','Oktober','November','Desember'
                                                        ];
                                                        $explode = explode('-', $ymdhis[0]);
                                                        $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                        @endphp
                                                        <tr class="table-{{ $label_pesanan }}">
                                                            <th scope="row">{{ $data->id }}</th>
                                                            <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                            <td><span class="badge badge-{{$badge}}">{{ $data->globals == 1 ? "Ya" : "Tidak" }}</span></td>
                                                            <td>{{ $data->kategori_id == null ? '-' : $kategori->nama }}</td>
                                                            <td>{{ $data->kode }}</td>
                                                            <td>{{ $data->promo }} %</td>
                                                            <td>{{ $data->stock }}</td>
                                                            <td>{{ number_format($data->min_transaksi) }}</td>
                                                            <td>{{ number_format($data->max_potongan) }}</td>
                                                            <td><span class="badge badge-{{$badges}}">{{ $data->versi == "login" ? "Login" : "Public" }}</span></td>
                                                            <td>{{ $data->limit_voucher_login == null ? '0' : $data->limit_voucher_login }}</td>
                                                            <td>{{ $data->role == null ? '-' : $data->role }}</td>
                                                            <td>{{ $data->expired == null ? '-' : $data->expired }}</td>
                                                            <td>
                                                                <div class="dropdown">
                        											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        												<i class="dw dw-more"></i>
                        											</a>
                        											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        												<a class="dropdown-item" href="{{ route('voucher.detail', [$data->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
                        												<a class="dropdown-item" href="{{ route('voucher.delete', [$data->id]) }}"><i class="dw dw-delete-3"></i> Delete</a>
                        											</div>
                        										</div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="riwayat-voucher-login" role="tabpanel">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-1">Semua Voucher Limit (LOGIN)</h4>
                                            <div class="pb-20">
                                                <div class="alert alert-info mb-2 mt-2">
                                                    - Kolom warna <span class="badge badge-success">HIJAU</span> = Voucher belum limit<br>
                                                    - Kolom warna <span class="badge badge-danger">MERAH</span> = Voucher sudah limit
                                                </div>
                                                <table class="data-table table stripe hover nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th class="table-plus datatable-nosort">#</th>
                                                            <th>Tanggal</th>
                                                            <th>Username</th>
                                                            <th>Kode Voucher</th>
                                                            <th>Limit</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $voucherlist as $data )
                                                        <?php 
                                                        $user = DB::table('users')->where('id',$data->user_id)->first(); 
                                                        $vouchers = DB::table('vouchers')->where('kode',$data->kode)->first();
                                                        ?>
                                                        @php
                                                        $label_pesanan = '';
                                                        if($data->limit >= $vouchers->limit_voucher_login){
                                                            $label_pesanan = 'danger';
                                                        }else{
                                                            $label_pesanan = 'success';
                                                        }
                                                        $ymdhis = explode(' ',$data->created_at);
                                                        $month = [
                                                            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                            'Juli','Agustus','September','Oktober','November','Desember'
                                                        ];
                                                        $explode = explode('-', $ymdhis[0]);
                                                        $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                        @endphp
                                                        <tr class="table-{{ $label_pesanan }}">
                                                            <th scope="row">{{ $data->id }}</th>
                                                            <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                            <td>{{ $user->username }}</td>
                                                            <td>{{ $data->kode }}</td>
                                                            <td>{{ $data->limit }}</td>
                                                            <td>
                                                                <div class="dropdown">
                        											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        												<i class="dw dw-more"></i>
                        											</a>
                        											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        												<a class="dropdown-item" href="{{ route('voucher-list.detail', [$data->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
                        												<a class="dropdown-item" href="{{ route('voucher-list.delete', [$data->id]) }}"><i class="dw dw-delete-3"></i> Delete</a>
                        											</div>
                        										</div>
                                                            </td>
                                                        </tr>
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
<script type="text/javascript">
    function get_versi(val_versi) {
        var values = val_versi;
        if(values == "login") {
            $("#versi-batasi").removeClass('d-none');
        } else {
            $("#versi-batasi").addClass('d-none');
        }
    }
    $(document).ready(function(){
        $('.data-table').DataTable({
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
    	$("#global").change(function() {
            $("#d-none").addClass('d-none');
            var global = $("#global").val();
            $.ajax({
                url: "{{ route('voucher.global') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "globals": global
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#d-none").removeClass('d-none');
                        $("#form_global").html(res.data);
                    } else {
                        
                        $("#form_global").html(res.data);
                    }
                }
            });
        });
        
    });
</script>
@endsection