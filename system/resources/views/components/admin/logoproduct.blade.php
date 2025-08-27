@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.logo'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Logo Produk</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/logo-product
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
                        <h4 class="mb-3 header-title mt-0">Tambah Logo Produk</h4>
                        <form action="{{ route('logo-product.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Kategori</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="category_id">
                                        @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Nama</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Product Logo</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="product_logo">
                                    @error('product_logo')
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
                                <h4 class="header-title mt-0 mb-1">Semua Logo Produk</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">#</th>
                                                <th>Kategori</th>
                                                <th>Nama</th>
                                                <th>Logo</th>
                                                <th>Aksi</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?php $no=1;?>
                                            @foreach( $data as $datas )
                                            <?php 
                                            $cat = DB::table('kategoris')->where('id',$datas->category_id)->first(); 
                                            ?>
                                            <tr>
                                                <th scope="row">{{ $no }}</th>
                                                <td>{{ $cat->nama }}</td>
                                                <td>{{ $datas->name }}</td>
                                                <td><img src="{{$datas->path}}" alt="" style="width:auto;height:55px;"></td>
                                                <td>
                                                    <div class="dropdown">
            											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            												<i class="dw dw-more"></i>
            											</a>
            											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            												<a class="dropdown-item" href="javascript:;" onclick="modal_open('edit', '{{ route('logo-product.detail', [$datas->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
            												<a class="dropdown-item" href="/logo-product/hapus/{{ $datas->id }}"><i class="dw dw-delete-3"></i> Delete</a>
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