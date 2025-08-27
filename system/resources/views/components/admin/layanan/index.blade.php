@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.layanan'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Layanan</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/layanan
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
                        <div class="flex">
                            <h4 class="mb-3 header-title mt-0">Tambah Layanan </h4>
                            <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjukModal"></i>
                        </div>
                        <form action="{{ route('layanan.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Kategori</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="kategori" id="kategori">
                                        <option value="0">- Select Category -</option>
                                        @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Nama</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama">
                                    @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Harga Asli (Harga Normal/Harga Awal)</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control @error('harga_provider') is-invalid @enderror" value="{{ old('harga_provider') }}" name="harga_provider" id="harga_provider">
                                    @error('harga_provider')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Harga Public</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" name="harga" id="harga_public" readonly>
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Harga Member</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" name="harga_member" id="harga_member" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Harga Reseller</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" name="harga_reseller" id="harga_reseller" readonly>
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Harga VIP</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" name="harga_vip" id="harga_vip" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Minimal</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control @error('min') is-invalid @enderror" value="{{ old('min') }}" name="min" id="min">
                                    @error('min')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Maximal</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control @error('max') is-invalid @enderror" value="{{ old('max') }}" name="max" id="max">
                                    @error('max')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Provider ID</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control @error('provider_id') is-invalid @enderror" value="{{ old('provider_id') }}" name="provider_id">
                                    @error('provider_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Product Logo</label>
                                <div class="col-lg-5">
                                    <select class="custom-select2 form-control" name="product_logo" style="width: 100%; height: 38px" id="logo_produk">
										<option value="0">Silahkan pilih kategori!</option>
									</select>
                                    @error('product_logo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Deskripsi Layanan</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control border-radius-0" name="description">{{ old('description') }}</textarea>
                                    @error('description')
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
                            <div class="card-body">
                                <form method="POST" action="{{ route('sync.layanan.get.post') }}">
                                    @csrf
                                   <div class="m-2">
                                        <button type="submit" class="btn-secondary inline-flex items-center justify-center rounded-4 px-3 py-2.7 hvrbutton" style="float: right; border-radius:6px;">
                                        <span style="display: flex; align-items: center;">
                                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"></path>
                                        </svg>
                                            Update Harga
                                        </span>
                                    </button>
                                    </div>
                                </form>
                                <h4 class="header-title mt-0 mb-1">Semua Layanan</h4>
                                <div class="pb-20">
                                    <input type="hidden" id="pd_logo">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label">Category</label>
                                        <div class="col-lg-10">
                                            <select class="form-control" name="kategori" id="kategori" onchange="get_kategori(this.value)">
                                                <option value="0">- Select Category -</option>
                                                @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">Product Logo</label>
                                        <div class="col-lg-10">
                                            <select class="custom-select2 form-control" name="product_logo" style="width: 100%; height: 38px" id="logos" onchange="get_prdlogo(this.value)">
            									<option value="0">Silahkan pilih kategori!</option>
            								</select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">View Logo</label>
                                        <div class="col-lg-10">
                                            <div id="tampilin_logo"></div>
                                        </div>
                                    </div>
                                    
                                    <div align="center">
                                        <button class="btn btn-primary mb-2" onclick="updateRecords()">
                                            <i class="fa fa-save"></i>  Multiple Update Logo Product
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="data-table table stripe hover nowrap">
                                            <thead>
                                                <tr>
                                                    <th>
                										<div class="dt-checkbox">
                											<input type="checkbox" name="select_all" value="1" id="example-select-all" />
                											<span class="dt-checkbox-label"></span>
                										</div>
                									</th>
                                                    <th>Kategori</th>
                                                    <th>Layanan</th>
                                                    <th>Provider</th>
                                                    <th>PID</th>
                                                    <th>Produk Logo</th>
                                                    <th>Harga</th>
                                                    <th>Profit</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1;?>
                                                @foreach( $datas as $data )
                                                <?php 
                                                $profit = DB::table('profits')->where('name',$data->provider)->first(); 
                                                    if($profit->percent == "%") {
                                                        $hasil_profit = $data->harga_provider * $profit->profit / 100;
                                                        $hasil_profit_member = $data->harga_provider * $profit->profit_member / 100;
                                                        $hasil_profit_reseller = $data->harga_provider * $profit->profit_reseller / 100;
                                                        $hasil_profit_vip = $data->harga_provider * $profit->profit_vip / 100;
                                                    } else if($profit->percent == "+") {
                                                        $hasil_profit = $data->harga;
                                                        $hasil_profit_member = $data->harga_member;
                                                        $hasil_profit_reseller = $data->harga_reseller;
                                                        $hasil_profit_vip = $data->harga_vip;
                                                    }
                                                $d_logo = DB::table('logo_products')->where('category_id',$data->id_kategori)->get();
                                                ?>
                                                @php
                                                $label_pesanan = '';
                                                if($data->status == "available"){
                                                $label_pesanan = 'info';
                                                }else if($data->status == "unavailable"){
                                                $label_pesanan = 'warning';
                                                }
                                                @endphp
                                                <tr>
                                                    <td>{{ $data->id }}</th>
                                                    <td>{{ $data->nama_kategori }}</td>
                                                    <td>{{ $data->layanan }}</td>
                                                    <td>{{ $data->provider }}</td>
                                                    <td>{{ $data->provider_id }}</td>
                                                    <td>
                                                        <img src="{{$data->product_logo}}" alt="" style="width:auto;height:55px;"><br>
                                                    </td>
                                                    <td>
                                                        <small>Harga Provider :Rp. {{ number_format($data->harga_provider, 0, '.', ',') }}</small><br>
                                                        <small>Harga Public :Rp. {{ number_format($data->harga, 0, '.', ',') }}</small><br>
                                                        <small>Harga Member :Rp. {{ number_format($data->harga_member, 0, '.', ',') }}</small><br>
                                                        <small>Harga Reseller :Rp. {{ number_format($data->harga_reseller, 0, '.', ',') }}</small><br>
                                                        <small>Harga VIP :Rp. {{ number_format($data->harga_vip, 0, '.', ',') }}</small>
                                                    </td>
                                                    <td>
                                                        <small>Profit Public : {{ $profit->profit }}{{ $profit->percent == "%" ? "%" : ""}} (Rp. {{ number_format($hasil_profit, 0, '.', ',') }})</small><br>
                                                        <small>Profit Member : {{ $profit->profit_member }}{{ $profit->percent == "%" ? "%" : ""}} (Rp. {{ number_format($hasil_profit_member, 0, '.', ',') }})</small><br>
                                                        <small>Profit Reseller : {{ $profit->profit_reseller }}{{ $profit->percent == "%" ? "%" : ""}} (Rp. {{ number_format($hasil_profit_reseller, 0, '.', ',') }})</small><br>
                                                        <small>Profit VIP : {{ $profit->profit_vip }}{{ $profit->percent == "%" ? "%" : ""}} (Rp. {{ number_format($hasil_profit_vip, 0, '.', ',') }})</small>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                    										<a class="btn btn-{{$label_pesanan}} dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    											{{ $data->status }} <i class="mdi mdi-chevron-down"></i>
                    										</a>
                    										<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    											<a class="dropdown-item" href="/layanan-status/{{ $data->id }}/available">available</a>
                    											<a class="dropdown-item" href="/layanan-status/{{ $data->id }}/unavailable">unavailable</a>
                    										</div>
                    									</div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                    										<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    											<i class="dw dw-more"></i>
                    										</a>
                    										<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    											<a class="dropdown-item" href="{{ route('layanan.detail', [$data->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
                    											<a class="dropdown-item" href="/layanan/hapus/{{ $data->id }}"><i class="dw dw-delete-3"></i> Delete</a>
                    										</div>
                    									</div>
                                                    </td>
                                                    <td>{{ $data->created_at }}</td>
                                                </tr>
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
                <div class="modal fade" id="petunjukModal" tabindex="-1" role="dialog" ria-labelledby="petunjukModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini hanya untuk tambah <b>LAYANAN/PRODUK</b>, Jika sudah ditambahkan silahkan ke fitur <b>PAKET</b> untuk menampilkan <b>LAYANAN/PRODUK</b> anda<br>
                                    Note : Jika ingin menambahkan <b>LOGO PRODUK</b>, Silahkan ke fitur <b>LOGO PRODUK</b>
								</p>
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
<script type="text/javascript">
    function updateRecords(){
        var ids = [];
        $('[name="id[]"]:checked').each(function(){
            ids.push($(this).val());
        });
        var product_logo = $('#pd_logo').val();
        $.ajax({
            url: "{{route('multiple.update')}}",
            type: 'POST',
            data: {
                "id": ids,
                "product_logo": product_logo,
                "_token": "<?php echo csrf_token(); ?>"
            },
            success: function (data) {
                if(data.status == true) {
                    toastr.success(data.msg, 'Berhasil');
                } else {
                    toastr.error(data.msg, 'Gagal!');
                }
            },
        });    
    }
    function get_prdlogo(id) {
        $('#pd_logo').val(id);
    }
    function get_kategori(id) {
        let produklogo = $('#logos')
        $.ajax({
            url: "{{ route('layanan.get-logo') }}",
            dataType: "json",
            type: "POST",
            data: {
                "_token": "<?php echo csrf_token() ?>",
                "kategori": id
            },
            beforeSend: function() {
                produklogo.html('<option value="">Mengambil Layanan...</option>');
            },
            success: function(res) {
                if (res.status == true) {
                    produklogo.html(res.data);
                } else {
                    produklogo.html(res.data);
                }
            }
        })
    }
    $(document).ready(function(){
        $('#logos').change(function() {
            var logos = $('#logos').val();
            $.ajax({
                url: "{{ route('layanan.get-logo-view') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "id": logos
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#tampilin_logo").html(res.data);
                    } else {
                        $("#tampilin_logo").html(res.data);
                    }
                }
            })
        });
        $('#harga_provider').keyup(function() {
            var kategori = $('#kategori').val();
            var harga_provider = $('#harga_provider').val();
            $.ajax({
                url: "{{ route('layanan.calculate') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "kategori": kategori,
                    "harga_provider": harga_provider
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#harga_public").val(res.harga_public);
                        $("#harga_member").val(res.harga_member);
                        $("#harga_reseller").val(res.harga_reseller);
                        $("#harga_vip").val(res.harga_vip);
                    } else {
                        $("#harga_public").val(res.harga_public);
                        $("#harga_member").val(res.harga_member);
                        $("#harga_reseller").val(res.harga_reseller);
                        $("#harga_vip").val(res.harga_vip);
                    }
                }
            })
        });
        $('#kategori').change(function() {
            var kategori = $('#kategori').val();
            $.ajax({
                url: "{{ route('layanan.get-logo') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "kategori": kategori
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#logo_produk").html(res.data);
                    } else {
                        $("#logo_produk").html(res.data);
                    }
                }
            })
        });
    	var table = $('.data-table').DataTable({
    		'scrollCollapse': true,
    		'autoWidth': false,
    		'responsive': true,
    		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    		"language": {
    			"info": "_START_-_END_ of _TOTAL_ entries",
    			searchPlaceholder: "Search",
    			paginate: {
    				next: '<i class="ion-chevron-right"></i>',
    				previous: '<i class="ion-chevron-left"></i>'  
    			}
    		},
    		'columnDefs': [{
    			'targets': 0,
    			'searchable': false,
    			'orderable': false,
    			'className': 'dt-body-center',
    			'render': function (data, type, full, meta){
    				return '<div class="dt-checkbox"><input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '"><span class="dt-checkbox-label"></span></div>';
    			}
    		}],
    		'order': [[1, 'asc']]
    	});
    	$('#example-select-all').on('click', function(){
    		var rows = table.rows({ 'search': 'applied' }).nodes();
    		$('input[type="checkbox"]', rows).prop('checked', this.checked);
    	});
    	$('.data-table tbody').on('change', 'input[type="checkbox"]', function(){
    		if(!this.checked){
    			var el = $('#example-select-all').get(0);
    			if(el && el.checked && ('indeterminate' in el)){
    				el.indeterminate = true;
    			}
    		}
    	});
    });
</script>

@endsection