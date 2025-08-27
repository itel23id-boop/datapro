@extends('main-admin', ['activeMenu' => 'konfigurasi', 'activeSubMenu' => 'konfigurasi.berita'])

@section('content')
<!-- start page title -->
            <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/Berita
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
                                <h4 class="header-title">Tambah Gambar</h4>
								<form action="{{ route('berita.post') }}" method="POST" enctype="multipart/form-data" id="berita">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                    <label>Foto Banner</label>
                                        <input type="file" class="form-control" name="banner">
                                    </div>
                                    <div class="form-group">
                                    <label>Deskripsi</label>
                                        <textarea class="form-control" id="editor" name="deskripsi"></textarea>
                                    </div>
                                    <div class="form-group">
                                    <label>Tipe</label>
                                        <select class="form-control" name="tipe">
                                        	<option value="0">Pilih...</option>
                                        	<option value="banner">Banner</option>
                                        	<option value="popup">Popup</option>
                                        </select>
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
                                <h4 class="header-title mt-0 mb-1">Semua Gambar</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">No</th>
                                                <th>Path</th>
                                                <th>Tipe</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=1;?>
                                            @foreach( $berita as $data)
                                            <tr>
                                                <th scope="row">{{ $no }}</th>
                                                <td><img src="{{$data->path}}" alt="" style="width:auto;height:50px;"></td>
                                                <td>{{ $data->tipe }}</td>
                                                <td>{{ $data->created_at }}</td>
                                                <td><a class="btn btn-danger" href="/berita/hapus/{{ $data->id }}">Hapus</a></td>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('.data-table').DataTable({
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