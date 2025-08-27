@extends('main-admin', ['activeMenu' => 'konfigurasi', 'activeSubMenu' => 'konfigurasi.payment'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Setelan Payment</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/Payment
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
                <div class="row mt-sm-4 justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box">
                                <h4 class="header-title">Tambah Payment</h4>
								<form action="{{ route('method.post') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                    <label>Provider</label>
                                        <select class="form-control" name="provider" id="provider">
                                        	<option value="0">Pilih...</option>
                                        	<option value="manual">Manual</option>
                                            <option value="tripay">Tripay</option>
                                            <option value="ipaymu">Ipaymu</option>
                                            <option value="duitku">Duitku</option>
                                            <option value="tokopay">TokoPay</option>
                                            <option value="linkqu">LinkQU</option>
                                            <option value="xendit">Xendit</option>
                                            <option value="paydisini">Paydisini</option>
                                        </select>
                                        @error('provider')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div id="code"></div>
                                    </div>
                                    <div class="form-group">
                                    <label>Nama Payment</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name">
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!--<div class="form-group">
                                    <label>Kode</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" name="code">
                                        @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>-->
                                    <div class="form-group">
                                        <div id="keterangan"></div>
                                    </div>
                                    <div class="form-group">
                                    <label>Tipe</label>
                                        <select class="form-control" name="tipe">
                                        	<option value="0">Pilih...</option>
                                        	<option value="bank-transfer">Bank Transfer</option>
                                        	<option value="qris">QRIS</option>
                                            <option value="e-walet">E-Wallet</option>
                                            <option value="virtual-account">Virtual Account</option>
                                            <option value="convenience-store">Convenience Store</option>
                                            <option value="pulsa">Pulsa</option>
                                        </select>
                                        @error('tipe')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                    <label>Images</label>
                                        <input type="file" class="form-control" name="images">
                                        @error('images')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                    <label>Percent</label>
                                        <select class="form-control" name="percent">
                                        	<option value="0">Pilih...</option>
                                        	<option value="%">%</option>
                                            <option value="+">+</option>
                                        </select>
                                        @error('percent')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                    <label>Biaya Admin</label>
                                        <input type="text" class="form-control @error('biaya_admin') is-invalid @enderror" value="{{ old('biaya_admin') }}" name="biaya_admin">
                                        @error('biaya_admin')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                    <label>Status Biaya Admin</label>
                                        <select class="form-control" name="status">
                                        	<option value="0">Pilih...</option>
                                        	<option value="ON">ON</option>
                                            <option value="OFF">OFF</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
						</div>
					</div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="card-body">
                                <h4 class="header-title mt-0 mb-1">Semua Payment</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">#</th>
                                                <th>Payment</th>
                                                <th>Biaya Admin</th>
                                                <th>Provider</th>
                                                <th>Images</th>
                                                <th>Aksi</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $datas)
                                            <tr>
                                                <th scope="row">{{ $datas->id }}</th>
                                                <td>
                                                    {{ $datas->name }}<br>
                                                    <small>{{ $datas->code }}</small><br>
                                                    <small>{{ $datas->keterangan }}</small><br>
                                                    <small>{{ $datas->tipe }}</small>
                                                </td>
                                                <td>
                                                    {{ $datas->biaya_admin }}<br>
                                                    <small>{{ $datas->percent }}</small><br>
                                                    <small>{{ $datas->status }}</small>
                                                </td>
                                                <td>{{ $datas->provider }}</td>
                                                <td><img src="{{$datas->images}}" alt="" width="64"></td>
                                                <td>
                                                    <div class="dropdown">
            											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            												<i class="dw dw-more"></i>
            											</a>
            											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            												<a class="dropdown-item" href="javascript:;" onclick="modal_open('edit', '{{ route('method.detail', [$datas->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
            												<a class="dropdown-item" href="/method/hapus/{{ $datas->id }}"><i class="dw dw-delete-3"></i> Delete</a>
            											</div>
            										</div>
                                                </td>
                                                <td>{{ $datas->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                
                        </div>
                
                    </div>
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#provider').change(function() {
            var provider = $('#provider').val();
            $.ajax({
                url: "{{ route('method.get-code') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "provider": provider
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#code").html(res.data);
                        $("#keterangan").html(res.data2);
                    } else {
                        $("#code").html(res.data)
                        $("#keterangan").html(res.data2)
                    }
                }
            })
        });
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
    });
</script>

@endsection