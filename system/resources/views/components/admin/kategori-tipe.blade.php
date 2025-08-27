@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.kategori-tipe'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Kategori Tipe</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/kategori-tipe
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
                                <h4 class="mb-3 header-title mt-0">Tambah Kategori Tipe</h4>
                                <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjukModal"></i>
                            </div>
                        <form action="{{ route('kategori-tipe.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Name</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('text') is-invalid @enderror" value="{{ old('text') }}" name="text">
                                    @error('text')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Status</label>
                                <div class="col-lg-10 mt-1">
                                    <div style="display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;flex-direction: row;">
    									<div class="custom-control custom-radio mb-5">
    										<input type="radio" id="customRadio1" name="status" value="ON" class="custom-control-input">
    										<label class="custom-control-label" for="customRadio1">On</label>
    									</div>
    									<div class="custom-control custom-radio mb-5">
    										<input type="radio" id="customRadio2" name="status" value="OFF" class="custom-control-input">
    										<label class="custom-control-label" for="customRadio2">Off</label>
    									</div>
									</div>
                                    @error('status')
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
                                <h4 class="header-title mt-0 mb-1">Semua Kategori Tipe</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">No</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?php $no=1;?>
                                            @foreach( $data as $datas )
                                            @php
                                            if($datas->status == 'ON') {
                                                $color = 'success';
                                            } else {
                                                $color = 'danger';
                                            }
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $no }}</th>
                                                <td>{{ $datas->text }}</td>
                                                <td><span class="badge badge-{{$color}}">{{ $datas->status }}</span></td>
                                                <td>
                                                    <div class="dropdown">
            											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            												<i class="dw dw-more"></i>
            											</a>
            											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            												<a class="dropdown-item" href="javascript:;" onclick="modal_open('edit', '{{ route('kategori-tipe.detail', [$datas->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
            												<a class="dropdown-item" href="/kategori-tipe/hapus/{{ $datas->id }}"><i class="dw dw-delete-3"></i> Delete</a>
            											</div>
            										</div>
                                                </td>
                                                <td>{{ $datas->created_at }}</td>
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
                <div class="modal fade" id="petunjukModal" tabindex="-1" role="dialog" ria-labelledby="petunjukModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk menambahkan <b>KATEGORI TIPE</b> pada tampilan website contoh :
								</p>
								<img src="/assets/fitur_website/Screenshot.png" alt="" style="width:auto;height:50%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
<script type="text/javascript">
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
    });
</script>

@endsection