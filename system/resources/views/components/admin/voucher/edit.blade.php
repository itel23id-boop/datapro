@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.voucher'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Edit Voucher</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/edit-voucher
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
                        <h4 class="mb-3 header-title mt-0">Edit Voucher</h4>
                        <form action="{{ route('voucher.detail.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Global?</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="globals" id="global">
                                        <option value="" disabled selected>--Pilih Global--</option>
                                        @php
                                        if($data->globals == "1") {
                                            $vals = "ya";
                                            $text = "Ya";
                                        } else {
                                            $vals = "tidak";
                                            $text = "Tidak";
                                        }
                                        @endphp
                                        <option value="{{ $vals }}">{{ $text }} (Selected)</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row d-none" id="d-none">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kategori</label>
                                <div class="col-lg-10">
                                    <select class="form-control " name="kategori_id" id="form_global">
                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                    </select>
                                    @if($data->kategori_id != null)
                                    @php
                                    $kategori = DB::table('kategoris')->where('id',$data->kategori_id)->first(); 
                                    @endphp
                                    <small style="color:red;">Selected => {{ $kategori->nama }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kode</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" value="{{ $data->kode }}" name="kode">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Persenan Promo</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" value="{{ $data->promo }}" name="promo">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kuota</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" value="{{ $data->stock }}" name="stock">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Minimal Transaksi</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" value="{{ $data->min_transaksi }}" name="min_transaksi">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Max Potongan</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" value="{{ $data->max_potongan }}" name="max_potongan">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Voucher Versi</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="versi" onchange="get_versi(this.value)">
                                        <option value="" disabled selected>--Pilih versi--</option>
                                        @php
                                        if($data->versi == "public") {
                                            $text = "Public";
                                        } else {
                                            $text = "Login";
                                        }
                                        @endphp
                                        <option value="{{ $data->versi }}">{{ $text }} (Selected)</option>
                                        <option value="public">Public</option>
                                        <option value="login">Login</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row d-none" id="versi-batasi">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Limit Voucher Login</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" value="{{ $data->limit_voucher_login }}" name="limit_voucher_login" placeholder="10">
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Role</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="role">
                                        <option value="" disabled selected>--Pilih Role--</option>
                                        <option value="{{ $data->role }}">{{ $data->role }} (Selected)</option>
                                        <option value="Member">Member</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="VIP">VIP</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Expired</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control datetimepicker" value="{{ $data->expired }}" name="expired" placeholder="Choose Date and time">
                                </div>
                            </div>
                            <div class="btn-list">
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                                <a href="{{ route('voucher') }}" class="btn btn-danger w-100">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>

<script type="text/javascript">
    function get_versi(val_versi) {
        var values = val_versi;
        if(values == "login") {
            $("#versi-batasi").removeClass('d-none');
        } else {
            $("#versi-batasi").addClass('d-none');
        }
    }
    $(document).ready(function(){
    	$("#global").change(function() {
            $("#d-none").addClass('d-none');
            var global = $("#global").val();
            $.ajax({
                url: "{{ route('voucher.global') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token() ?>",
                    "globals": global
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#d-none").removeClass('d-none');
                        $("#form_global").html(res.data);
                    } else {
                        
                        $("#form_global").html(res.data);
                    }
                }
            });
        });
        
    });
</script>

@endsection