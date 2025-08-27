@extends('main-admin', ['activeMenu' => 'mutasi', 'activeSubMenu' => 'mutasi.ovo'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Setelan OVO</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										ovo
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
                                <h4 class="header-title">OVO Settings</h4>
								<form class="form-horizontal" method="POST" id="myForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nomor OVO</label>
                                        <div class="row">
                                            <div class="col-9">
                                                <input type="number" name="nomor" class="form-control" id="nomor" aria-describedby="emailHelp" placeholder="+62xxxx">
                                                <small id="result_getotp" class="form-text text-muted"></small>
                                            </div>
                                            <div class="col-2">
                                                <p class="btn btn-info" onclick="getOTP()" id="get_otp">Get OTP</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ref ID</label>
                                        <input type="text" name="refID" class="form-control" id="refID" placeholder="Ref ID" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>OTP</label>
                                        <div class="row">
                                            <div class="col-9">
                                                <input type="text" name="otp" class="form-control" id="otp_ovo" placeholder="Masukkan OTP">
                                                <small id="result_validOTP" class="form-text text-muted"></small>
                                            </div>
                                            <div class="col-2">
                                                <p class="btn btn-info" onclick="validasiOTP()" id="validasi_otp">Validasi OTP</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Update Access Token</label>
                                        <input type="text" name="update_token" class="form-control" id="update_token" placeholder="Update Token" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>PIN</label>
                                        <div class="row">
                                            <div class="col-9">
                                                <input type="text" name="pin" class="form-control" id="pin_ovo" placeholder="Masukkan PIN">
                                                <small id="result_validPIN" class="form-text text-muted"></small>
                                            </div>
                                            <div class="col-2">
                                                <p class="btn btn-info" onclick="validasiPIN()" id="validasi_pin">Validasi PIN</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Auth Token</label>
                                        <input type="text" name="auth_token" class="form-control" id="auth_token" placeholder="Auth Token" readonly>
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
                                <h4 class="header-title mt-0 mb-1">Riwayat Saldo</h4>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="sub-header">
                                            Riwayat saldo ovo keluar dan masuk.
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-success mb-2" href="/Ovo-Transaksi">Ambil Data Baru!</a>
                                    </div>
                                </div>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">#</th>
                                                <th>Tanggal</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Tipe Transaksi</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $transaksi as $wallet)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $wallet->tanggal_transaksi }}</td>
                                                <td>{{ $wallet->jumlah_transaksi }}</td>
                                                <td>{{ $wallet->tipe_transaksi }}</td>
                                                <td>{{ $wallet->keterangan }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                
                    </div>
                </div>
<!-- end row -->
<script>
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
    function getOTP() {
        var nomor = document.getElementById("nomor").value;

        $.getJSON("/ovo/Get-OTP/" + nomor, function(result) {
            if (result['status'] === "True") {
                $("#result_getotp").append("<span class='text-success'>Mengirim OTP Sukses</span>");
                $("#refID").val(result['refID']);
            } else {
                $("#result_getotp").append("<span class='text-danger'>Gagal Mengirim OTP</span>");
            }
        });

    };

    function validasiOTP() {
        var nomor = document.getElementById("nomor").value;
        var refID = document.getElementById("refID").value;
        var otp = document.getElementById("otp_ovo").value;
        var formData = $("form").serialize();

        $.ajax({
            method: "POST",
            url: "/ovo/Validasi-OTP",
            data: formData,
            success: function(res) {
                if (res['status'] === "True") {
                    $("#update_token").val(res['updateToken']);
                    $("#result_validOTP").append("<span class='text-succes'>Berhasil Validasi OTP</span>");
                } else {
                    $("#result_validOTP").append("<span class='text-success'>Gagal Vaidasi OTP</span>");
                }
            }
        });
    };

    function validasiPIN() {
        var nomor = document.getElementById("nomor").value;
        var refID = document.getElementById("refID").value;
        var otp = document.getElementById("otp_ovo").value;
        var update_token = document.getElementById("update_token");
        var formData = $("form").serialize();

        $.ajax({
            method: "POST",
            url: "/ovo/Validasi-PIN",
            data: formData,
            success: function(res) {
                if (res['status'] === "True") {
                    $("#auth_token").val(res['auth_token']);
                    $("#exp_token").val(res['expired']);
                    $("#result_validPIN").append("<span class='text-succes'>Berhasil Validasi PIN</span>");
                } else {
                    $("#result_validPIN").append("<span class='text-success'>Gagal Vaidasi PIN</span>");
                }
            }
        });
    };
</script>
@endsection