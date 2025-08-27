@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.paket'])

@section('content')
    <!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Setelan Paket</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/paket
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>

                @if (session('success'))
                    <div class="alert alert-success">
            
                        {{ session('success') }}
            
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
            
                        {{ session('error') }}
            
                    </div>
                @endif
                
                <div class="row row-cols-1">
                    <div class="card-box mb-30">
                        <div class="card-body">
                            <div class="flex">
                                <h4 class="mb-3 header-title mt-0">Buat Nama Paket </h4>
                                <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjukModal"></i>
                            </div>
                            <form action="{{ route('paket.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">Nama</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" placeholder="Masukkan Nama Paket" value="{{ old('nama') }}">
                                        </div>
                                        @error('nama')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">Paket Logo</label>
                                        <div class="col-lg-10">
                                            <input type="file" class="form-control" name="paket_logo">
                                            @error('paket_logo')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-box mb-30">
                        <div class="card-body">
                            <div class="flex">
                                <h4 class="mb-3 header-title mt-0">Tambah Layanan Paket </h4>
                                <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjuk1Modal"></i>
                            </div>
                            <form action="{{ route('paket-layanan.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">Paket</label>
                                        <div class="col-lg-10">
                                            <select class="form-control @error('paket_id') is-invalid @enderror" name="paket_id">
                                                <option value="" selected disabled>--Pilih Paket--</option>
                                                @foreach ($pakets as $paket)
                                                    <option value="{{ $paket->id }}"
                                                        {{ old('paket_id') == $paket->id ? 'selected' : '' }}>
                                                        {{ $paket->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('paket_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">Kategori</label>
                                        <div class="col-lg-10">
                                            <select class="form-control" onchange="get_layanan(this.value)" name="kategori_id">
                                                <option value="" disabled selected>--Pilih Kategori--</option>
                                                @foreach ($kategoris as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-form-label" for="example-fileinput">Layanan</label>
                                        <div class="col-lg-10">
                                            <!--<select class="custom-select2 form-control @error('layanan_id') is-invalid @enderror" multiple="multiple" style="width: 100%;" name="layanan_id[]" id="layanan">-->
                                            <select class="selectpicker form-control @error('layanan_id') is-invalid @enderror" data-style="btn-outline-secondary" data-selected-text-format="count" name="layanan_id[]" id="select-layanan" multiple>
                                                <option disabled>Pilih Kategori Terlebih Dahulu</option>
                                            </select>
                                            @error('layanan_id')
                                                <div class="text-danger">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-box mb-30">
                    <div class="card-body">
                        <div class="flex">
                                <h4 class="header-title mt-0 mb-1">Semua Paket</h4>
                                <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjuk2Modal"></i>
                            </div>
                        <div class="table-responsive">
                            <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr>
                                        <th class="table-plus datatable-nosort">#</th>
                                        <th>Paket</th>
                                        <th>Paket Logo</th>
                                        <th>Layanan</th>
                                        <th style="width: 200px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pakets as $paket)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $paket->nama }}</td>
                                            <td><img src="{{$paket->image}}" alt="" style="width:auto;height:50px;"></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#kategoriModal{{ $paket->id }}" type="button">{{ $paket->layanan->count() }} Layanan</button>
                                            </td>
                                            <td class="d-flex gap-2">
                                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModal{{ $paket->id }}">Edit</a>
                                                <form action="{{ route('paket.destroy', $paket->id) }}" method="POST"
                                                    id="deleteForm{{ $paket->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="confirmDelete({{ $paket->id }})">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @foreach ($pakets as $paket)
                    <div class="modal fade" id="editModal{{ $paket->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $paket->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $paket->id }}">Edit
                                        Paket</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form Edit -->
                                    <form action="{{ route('paket.update', $paket->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="editNama{{ $paket->id }}" class="form-label">Nama Paket</label>
                                            <input type="text" class="form-control" id="editNama{{ $paket->id }}"
                                                name="nama" value="{{ $paket->nama }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="editNama{{ $paket->id }}" class="form-label">Paket Logo</label>
                                            <input type="file" class="form-control" name="paket_logo" value="{{ $paket->paket_logo }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade kategoriModal{{ $paket->id }}" id="kategoriModal{{ $paket->id }}" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="kategoriModalLabel">{{ $paket->nama }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">Kategori</th>
                                                <th>Layanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($paket->layanan->groupBy('kategori_id') as $k => $l)
                                                <tr>
                                                <td class="border-end">{{ $l->first()->kategori->nama }}</td>
                                                <td class="border-end">
                                                    <ul>
                                                        @foreach ($l as $item)
                                                        <li class="d-flex justify-content-between">
                                                            <span>{{ $item->layanan }} (<span style="color:deeppink;">{{ $item->provider }}</span>)</span>
                                                            <form action="{{ route('paket-layanan.destroy',$item->id) }}" method="post" id="mitsuki{{ $item->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" onclick="$('#mitsuki{{ $item->id }}').submit()">Hapus</a>
                                                            </form>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="petunjukModal" tabindex="-1" role="dialog" ria-labelledby="petunjukModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk menambahkan <b>PAKET</b> pada tampilan website contoh :
								</p>
								<img src="/assets/fitur_website/Screenshot_2.png" alt="" style="width:auto;height:100%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="petunjuk1Modal" tabindex="-1" role="dialog" ria-labelledby="petunjuk1ModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk menampilkan <b>LAYANAN/PRODUK</b> pada tampilan order website contoh :
								</p>
								<img src="/assets/fitur_website/Screenshot_3.png" alt="" style="width:auto;height:100%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="petunjuk2Modal" tabindex="-1" role="dialog" ria-labelledby="petunjuk2ModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-sm modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk menampilkan <b>SEMUA PAKET/ RIWAYAT PAKET</b>.
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

        function get_layanan(kategori_id) {
            let layanan = $('#select-layanan')
            $('#select-layanan').selectpicker();
            $.ajax({
                url: "{{ route('paket-layanan.get-layanan') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    kategori_id: kategori_id
                },
                success: function(response) {
                    var data = response.data;
                    $.each(data, function(index, item) {
                        layanan.append('<option value="' + item.id + '">' + item.layanan +'</option>').trigger('change');
                    });
                    $('#select-layanan').selectpicker('refresh');
                },
                error: function(response) {
                    let res = JSON.parse(response.responseText)
                    layanan.html('<option disabled>' + res.message + '</option>');
                    $('#select-layanan').selectpicker('refresh');
                }
            });
        }

        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>
@endsection
