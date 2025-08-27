@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.kategori'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Tambah Kategori</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/kategori/tambah
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
                <div class="card-box mb-30">
                    <div class="card-body">
                        <div class="flex">
                            <h4 class="mb-3 header-title mt-0">Tambah Kategori</h4>
                            <i class="fa fa-question-circle cursor-pointer ms-1" data-toggle="modal" data-target="#petunjukModal"></i>
                        </div>
                        <form action="{{ route('kategori.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="alert alert-info mb-2 mt-2">
                                Fitur ini hanya untuk tambah <b>KATEGORI LAYANAN/PRODUK</b>, Jika sudah ditambahkan silahkan ke fitur <b>LAYANAN</b> untuk menambahkan <b>LAYANAN/PRODUK</b> anda<br>
                                Note : Silahkan klik detail kategori anda jika sudah menambahkan kategori untuk menambahkan form <b>ORDER/EXTRA</b>, <b>THUMBNAIL & DESCRIPTION</b>, <b>BANNER & PETUNJUK</b>, dll
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Tipe</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="tipe"> 
                                        @foreach( $dataTab as $datat )
                                        <option value='{{ $datat->code }}'>{{ $datat->text }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Provider</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="provider">                
                                        <option value='manual'>Manual</option>
                                        <option value='partaisocmed'>PartaiSocmed</option>
                                        <option value='irvankedesmm'>IrvanKedeSMM</option>
                                        <option value='vipmember'>Vipmember</option>
                                        <option value='istanamarket'>Istanamarket</option>
                                        <option value='fanstore'>Fanstore</option>
                                        <option value='rasxmedia'>Rasxmedia</option>
                                    </select>
                                    @error('provider')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Nama</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama" placeholder="example: Mobile Legends">
                                    @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Sub Nama</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('sub_nama') is-invalid @enderror" value="{{ old('sub_nama') }}" name="sub_nama" placeholder="example: Moonton">
                                    @error('sub_nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Text 1</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('text_1') is-invalid @enderror" value="{{ old('text_1') }}" name="text_1" placeholder="example: Pengiriman instant">
                                    @error('text_1')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Text 2</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('text_2') is-invalid @enderror" value="{{ old('text_2') }}" name="text_2" placeholder="example: Indonesia / Global">
                                    @error('text_2')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Text 3</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('text_3') is-invalid @enderror" value="{{ old('text_3') }}" name="text_3" placeholder="example: Pembayaran yang aman">
                                    @error('text_3')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Text 4</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('text_4') is-invalid @enderror" value="{{ old('text_4') }}" name="text_4" placeholder="example: Jaminan layanan">
                                    @error('text_4')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Text 5</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('text_5') is-invalid @enderror" value="{{ old('text_5') }}" name="text_5" placeholder="example: Layanan Pelanggan">
                                    @error('text_5')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Url</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" name="kode" placeholder="example: mobile-legends">
                                    @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Brand/Kategori Provider [OPTIONAL]</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" name="brand">
                                    <small style="color:red;">Contoh : Mobile Legends / Free Fire (tergantung kategori / brand dari provider tersebut)</small>
                                    @error('brand')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Membutuhkan Server ID?</label>
                                <div class="col-lg-10 mt-1">
                                    <div style="display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;flex-direction: row;">
    									<div class="custom-control custom-radio mb-5">
    										<input type="radio" id="customRadio1" name="serverOption" value="ya" class="custom-control-input">
    										<label class="custom-control-label" for="customRadio1">Ya</label>
    									</div>
    									<div class="custom-control custom-radio mb-5">
    										<input type="radio" id="customRadio2" name="serverOption" value="tidak" class="custom-control-input">
    										<label class="custom-control-label" for="customRadio2">Tidak</label>
    									</div>
									</div>
                                    @error('serverOption')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Deskripsi Game</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control @error('deskripsi_game') is-invalid @enderror" id="deskripsi_game" name="deskripsi_game">{{ old('deskripsi_game') }}</textarea>
                                    @error('deskripsi_game')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Deskripsi Field User ID & Zone ID</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control @error('deskripsi_field') is-invalid @enderror" name="deskripsi_field">{{ old('deskripsi_field') }}</textarea>
                                    @error('deskripsi_field')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Deskripsi Pop Up Game [OPTIONAL]</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control @error('deskripsi_popup') is-invalid @enderror" name="deskripsi_popup" id="deskripsi_popup">{{ old('deskripsi_popup') }}</textarea>
                                    @error('deskripsi_popup')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Membutuhkan Validasi ID?</label>
                                <div class="col-lg-10 mt-1">
                                    <div style="display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;flex-direction: row;">
    									<div class="custom-control custom-radio mb-5">
    										<input type="radio" id="customRadio3" name="validasi" value="ya" class="custom-control-input">
    										<label class="custom-control-label" for="customRadio3">Ya</label>
    									</div>
    									<div class="custom-control custom-radio mb-5">
    										<input type="radio" id="customRadio4" name="validasi" value="tidak" class="custom-control-input">
    										<label class="custom-control-label" for="customRadio4">Tidak</label>
    									</div>
									</div>
                                    @error('validasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div id="form_validasi"></div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Thumbnail</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="thumbnail">
                                    @error('thumbnail')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Banner</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="banner">
                                    @error('banner')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Petunjuk [OPTIONAL]</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="petunjuk">
                                    @error('petunjuk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Popular</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="popular">                
                                        <option value='No'>No</option>
                                        <option value='Yes'>Yes</option>
                                    </select>
                                    @error('popular')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="btn-list">
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                                <a href="{{ route('kategori') }}" class="btn btn-danger w-100">Kembali</a>
                            </div>
                        </form>
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
								    Fitur ini hanya untuk tambah <b>KATEGORI LAYANAN/PRODUK</b>, Jika sudah ditambahkan silahkan ke fitur <b>LAYANAN</b> untuk menambahkan <b>LAYANAN/PRODUK</b> anda<br>
                                    Note : Silahkan klik detail kategori anda jika sudah menambahkan kategori untuk menambahkan form <b>ORDER/EXTRA</b>, <b>THUMBNAIL & DESCRIPTION</b>, <b>BANNER & PETUNJUK</b>, dll
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
        $("input[type=radio][name=validasi]").on("click", function() {
            var validasi = $("input[name='validasi']:checked").val();
            $.ajax({
                url: "{{ route('kategori.validasi') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "validasi": validasi
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#form_validasi").html(res.data);
                    } else {
                        $("#form_validasi").html(res.data);
                    }
                }
            })
        });
    });
</script>
@endsection