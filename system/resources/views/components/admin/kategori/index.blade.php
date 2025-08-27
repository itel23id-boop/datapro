@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.kategori'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Kategori</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/kategori
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
                            <div class="card-body">
                                <div class="flex">
                                    <h4 class="header-title mt-0 mb-1">Semua Kategori</h4>
                                    <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjukModal"></i>
                                </div>
                                <div align="left">
                                    <a class="btn btn-primary mb-2" href="{{ route('kategori.add') }}">
                                        <i class="fa fa-save"></i>  Tambah Kategori
                                    </a>
                                </div>
                                <div class="pb-20">
                                    <div class="table-responsive">
                                        <table class="data-table table stripe hover nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="table-plus datatable-nosort">No</th>
                                                    <th>Foto</th>
                                                    <th>Kategori</th>
                                                    <th>Tipe</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            	<?php $no=1;?>
                                                @foreach( $data as $datas )
                                                <?php 
                                                $kat_tipe = DB::table('kategori_tipes')->where('code',$datas->tipe)->first(); 
                                                ?>
                                                @php
                                                $label_pesanan = '';
                                                if($datas->status == "active"){
                                                $label_pesanan = 'info';
                                                }else if($datas->status == "unactive"){
                                                $label_pesanan = 'warning';
                                                }
                                                @endphp
                                                <tr>
                                                    <th scope="row">{{ $no }}</th>
                                                    <td>
                                                        <img src="{{$datas->thumbnail}}" alt="" style="width:auto;height:55px;">
                                                    </td>
                                                    <td>
                                                        {{$datas->nama}}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                											<a class="btn btn-{{$label_pesanan}} dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                												{{ $kat_tipe->text }} <i class="mdi mdi-chevron-down"></i>
                											</a>
                											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                											    @foreach( $dataTab as $datat )
                												<a class="dropdown-item" href="/kategori/tipe/{{ $datas->id }}/{{$datat->code}}">{{ $datat->text}}</a>
                												@endforeach
                											</div>
                										</div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                											<a class="btn btn-{{$label_pesanan}} dropdown-toggle" href="javascript:void(0);" role="button" data-toggle="dropdown">
                												{{ $datas->status }} <i class="mdi mdi-chevron-down"></i>
                											</a>
                											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                												<a class="dropdown-item" href="/kategori-status/{{ $datas->id }}/active">active</a>
                												<a class="dropdown-item" href="/kategori-status/{{ $datas->id }}/unactive">unactive</a>
                											</div>
                										</div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="javascript:void(0);" role="button" data-toggle="dropdown">
                												<i class="dw dw-more"></i>
                											</a>
                											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                												<a class="dropdown-item" href="javascript:void(0);" onclick="modal_open('edit', '{{ route('kategori.edit', [$datas->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
                												<a class="dropdown-item" href="{{ route('kategori.detail', [$datas->id]) }}"><i class="dw dw-edit1"></i> detail</a>
                												<a class="dropdown-item" href="/kategori/hapus/{{ $datas->id }}"><i class="dw dw-delete-3"></i> Delete</a>
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
                </div>
                <div class="modal fade" id="petunjukModal" tabindex="-1" role="dialog" ria-labelledby="petunjukModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Note : Untuk mencari fitur <b>DETAIL</b> klik pada titik 3 di kolom <b>AKSI</b>
								</p>
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