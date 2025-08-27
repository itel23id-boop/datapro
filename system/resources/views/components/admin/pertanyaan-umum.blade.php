@extends('main-admin', ['activeMenu' => 'konfigurasi', 'activeSubMenu' => 'konfigurasi.pertanyaan-umum'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Pertanyaan Umum</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/pertanyaan-umum
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
                        <h4 class="mb-3 header-title mt-0">Tambah Pertanyaan Umum</h4>
                        <form action="{{ route('pertanyaan-umum.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Judul</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" name="judul">
                                    @error('judul')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Pesan</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control @error('pesan') is-invalid @enderror" value="{{ old('pesan') }}" id="pertanyaan_umum" name="pesan"></textarea>
                                    @error('pesan')
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
                                <h4 class="header-title mt-0 mb-1">Semua Pertanyaan Umum</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">#</th>
                                                <th>Tanggal</th>
                                                <th>Judul</th>
                                                <th>Pesan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=1;?>
                                            @foreach( $data as $datas )
                                            @php
                                            $ymdhis = explode(' ',$datas->created_at);
                                            $month = [
                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                'Juli','Agustus','September','Oktober','November','Desember'
                                            ];
                                            $explode = explode('-', $ymdhis[0]);
                                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                            
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $no }}</th>
                                                <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                <td>{{ $datas->judul }}</td>
                                                <td><?php echo nl2br($datas->pesan) ?></td>
                                                <td>
                                                    <div class="dropdown">
            											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            												<i class="dw dw-more"></i>
            											</a>
            											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            												<a class="dropdown-item" href="javascript:;" onclick="modal_open('edit', '{{ route('pertanyaan-umum.detail', [$datas->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
            												<a class="dropdown-item" href="/pertanyaan-umum/hapus/{{ $datas->id }}"><i class="dw dw-delete-3"></i> Delete</a>
            											</div>
            										</div>
                                                </td>
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