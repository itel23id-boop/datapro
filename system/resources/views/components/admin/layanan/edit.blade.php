@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.layanan'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Edit Layanan</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/edit-layanan
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
                        <h4 class="mb-3 header-title mt-0">Edit Layanan</h4>
                        <form action="{{ route('layanan.detail.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="provider" value="{{ $data->provider }}">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Kategori</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="kategori" id="kategori">
                                        <option value="0">- Select One -</option>
                                        <option value="{{ $data->id_kategori }}">{{ $data->nama_kategori }} (Selected)</option>
                                        @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Nama</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" value="{{ $data->layanan }}" name="layanan">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Harga Asli (Harga Normal/Harga Awal)</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" value="{{ $data->harga_provider }}" name="harga_provider" id="harga_provider">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Harga Public</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->harga }}" name="harga" id="harga_public" readonly>
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Harga Member</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->harga_member }}" name="harga_member" id="harga_member" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Harga Reseller</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->harga_reseller }}" name="harga_reseller" id="harga_reseller" readonly>
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Harga VIP</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->harga_vip }}" name="harga_vip" id="harga_vip" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Provider ID</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="{{ $data->provider_id }}" name="provider_id">
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Product Logo</label>
                                <div class="col-lg-5">
                                    <select class="custom-select2 form-control" name="product_logo" style="width: 100%; height: 38px" id="logo_produk">
                                        <option value="0">Silahkan pilih kategori!</option>
									</select>
									<img src="{!! $data->product_logo !!}" alt="" style="width:auto;height:55px;">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Minimal Order</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->min }}" name="min">
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Maximal Order</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->max }}" name="max">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Deskripsi Layanan</label>
                                <div class="col-lg-5">
                                    <textarea class="form-control border-radius-0" name="description">{{ $data->description }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Status</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="status">
                                        <option value="{{ $data->status }}"> {{ $data->status }} (Selected)</option>
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="btn-list">
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                                <a href="{{ route('layanan') }}" class="btn btn-danger w-100">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>

<script type="text/javascript">
    $(document).ready(function(){
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
    });
</script>

@endsection