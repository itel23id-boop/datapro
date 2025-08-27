@extends('main-admin', ['activeMenu' => 'artikel', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Artikel</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/artikel-admin
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="card-body">
                                <div align="left">
                                    <a class="btn btn-primary mb-2" href="{{ route('artikel-admin.add') }}">
                                        <i class="fa fa-save"></i>  Tambah Artikel
                                    </a>
                                </div>
                                <h4 class="header-title mt-0 mb-1">Semua Artikel</h4>
                                <div class="pb-20">
                                    <div class="table-responsive">
                                        <table class="data-table table stripe hover nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="table-plus datatable-nosort">#</th>
                                                    <th>Title</th>
                                                    <th>Banner</th>
                                                    <th>Konten</th>
                                                    <th>Tags</th>
                                                    <th>Author</th>
                                                    <th>Kategori</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1;?>
                                                @foreach( $datas as $data )
                                                @php
                                                $optionsArray = explode(',', $data->tags);
                                                $optionsArray = array_map('trim', $optionsArray);
                                                $optionsArray = array_filter($optionsArray);
                                                $jsonOptions = json_encode($optionsArray);
                                                $dropdownValues = json_decode($jsonOptions);
                                                
                                                $label_pesanan = '';
                                                if($data->status == "Active"){
                                                $label_pesanan = 'info';
                                                }else if($data->status == "Not Active"){
                                                $label_pesanan = 'warning';
                                                }
                                                
                                                @endphp
                                                <tr>
                                                    <td>{{ $no }}</th>
                                                    <td>{{ $data->title }}</td>
                                                    <td><img src="{{$data->path}}" alt="" style="width:auto;height:55px;"></td>
                                                    <td>{!! $data->content !!}</td>
                                                    <td><div style="display: flex;justify-content: space-evenly;flex-wrap: wrap;">@foreach ($dropdownValues as $dropdownValue)<span class="badge badge-success">{{ $dropdownValue }}</span>@endforeach</div></td>
                                                    <td>{{ $data->author }}</td>
                                                    <td>{{ $data->category }}</td>
                                                    <td>
                                                        <div class="dropdown">
                    										<a class="btn btn-{{$label_pesanan}} dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    											{{ $data->status }} <i class="mdi mdi-chevron-down"></i>
                    										</a>
                    										<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    											<a class="dropdown-item" href="/artikel-admin-status/{{ $data->id }}/Active">Active</a>
                    											<a class="dropdown-item" href="/artikel-admin-status/{{ $data->id }}/Not Active">Not active</a>
                    										</div>
                    									</div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                    										<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    											<i class="dw dw-more"></i>
                    										</a>
                    										<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    											<a class="dropdown-item" href="{{ route('artikel-admin.detail', [$data->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
                    											<a class="dropdown-item" href="/artikel-admin/hapus/{{ $data->id }}"><i class="dw dw-delete-3"></i> Delete</a>
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