@extends("main")

@section("content")
    <div class="content-main mt-5">
		<div class="container">
			<div class="row">
				
				<div class="col-md-4">
					<div class="card mb-4 border-0 shadow-form">
						<div class="card-body">
							<h6 class="shadow-text card-title">Akun Saya</h6>
							<div class="users-menu">
								<a href="/user/dashboard" class=""><i class="fa fa-home me-3"></i>Dashboard</a>
								<a href="/user/orders" class=""><i class="fa fa-shopping-basket me-3"></i>Pembelian</a>
								<a href="/user/topup" class="active"><i class="fa fa-wallet me-3"></i>Isi Saldo</a>
								<a href="/user/topup/riwayat" class=""><i class="fa fa-shopping-basket me-3"></i>Riwayat Topup</a>
								<a href="/user/settings" class=""><i class="fa fa-cogs me-3"></i>Pengaturan</a>
								<a href="/user/upgrade" class=""><i class="fa fa-users me-3"></i>Upgrade</a>
								<hr>
								<form action="{{ route('logout') }}" method="POST">
								    @csrf
								    <a href="/logout"><i class="fa fa-sign-out me-3"></i>Logout</a>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="card border-0 shadow-form">
						<div class="card-body">
							<a href="/user/topup/riwayat" class="text-decoration-none float-end mt-2">
								<i class="fa fa-history me-1"></i> Data Riwayat
							</a>
							<h6 class="card-title">Isi Saldo</h6>
							@if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif
							<form action="{{ route('topup.post') }}" method="POST" id="form-topup">
							    <input type="hidden" name="method">
							    @csrf
								<div class="mb-3">
									<input type="number" class="form-control form-control-games" autocomplete="off" name="quantity" placeholder="Jumlah">
									<small class="text-muted">Minimal isi saldo Rp 10.000</small>
								</div>
								<div class="mb-3">
									<div class="accordion mb-2" id="accordionExample">
									    @if(count(DB::table('methods')->where('tipe','bank-transfer')->get()) != 0)
									    <div class="border-0 mb-3">
									        <h2 class="accordion-header" id="heading-6">
									            <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-6" aria-expanded="false" aria-controls="collapse-6">Bank Transfer (Manual Otomatis)</button>
									        </h2>
									        <div id="collapse-6" class="accordion-collapse collapse" aria-labelledby="heading-6" data-bs-parent="#accordionExample">
									            <div class="accordion-body" style="background: var(--warna_4);">
									            	<div class="row">
													    @foreach($pay_method as $p)
                                                        @if($p->tipe == 'bank-transfer')
    													<div class="col-md-4 col-6 col-method">
    														<div class="method mb-3" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    															<h6></h6>
    															<img src="{{$p->images}}" alt="" class="max-h-5" style="max-width: 100%;height: auto;">
    															<hr>
    															<span>{{$p->name}}</span>
    														</div>
    													</div>
    													@endif
                                                        @endforeach
													</div>
									            </div>
									        </div>
								            <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
								                @foreach($pay_method as $p)
                                                @if($p->tipe == 'bank-transfer')
								            	<img src="{{$p->images}}" alt="" height="16" class="me-2">
								                @endif
                                                @endforeach
								            </div>
									    </div>
									    @endif
									    @if(count(DB::table('methods')->where('tipe','qris')->get()) != 0)
									    <div class="border-0 mb-3">
									        <h2 class="accordion-header" id="heading-1">
									            <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false" aria-controls="collapse-1">QRIS (Dicek Otomatis)</button>
									        </h2>
									        <div id="collapse-1" class="accordion-collapse collapse" aria-labelledby="heading-1" data-bs-parent="#accordionExample">
									            <div class="accordion-body" style="background: var(--warna_4);">
									            	<div class="row">
													    @foreach($pay_method as $p)
                                                        @if($p->tipe == 'qris')
    													<div class="col-md-4 col-6 col-method">
    														<div class="method mb-3" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    															<h6></h6>
    															<img src="{{$p->images}}" alt="" class="max-h-5" style="max-width: 100%;height: auto;">
    															<hr>
    															<span>{{$p->name}}</span>
    														</div>
    													</div>
    													@endif
                                                        @endforeach
													</div>
									            </div>
									        </div>
								            <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
								                @foreach($pay_method as $p)
                                                @if($p->tipe == 'qris')
								            	<img src="{{$p->images}}" alt="" height="16" class="me-2">
								                @endif
                                                @endforeach
								            </div>
									    </div>
									    @endif
									    @if(count(DB::table('methods')->where('tipe','e-walet')->get()) != 0)
									    <div class="border-0 mb-3">
									        <h2 class="accordion-header" id="heading-2">
									            <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">E-Wallet (Dicek Otomatis)</button>
									        </h2>
									        <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="heading-2" data-bs-parent="#accordionExample">
									            <div class="accordion-body" style="background: var(--warna_4);">
									            	<div class="row">
													    @foreach($pay_method as $p)
                                                        @if($p->tipe == 'e-walet')
    													<div class="col-md-4 col-6 col-method">
    														<div class="method mb-3" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    															<h6></h6>
    															<img src="{{$p->images}}" alt="" class="max-h-5" style="max-width: 100%;height: auto;">
    															<hr>
    															<span>{{$p->name}}</span>
    														</div>
    													</div>
    													@endif
                                                        @endforeach
													</div>
									            </div>
									        </div>
								            <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
								                @foreach($pay_method as $p)
                                                @if($p->tipe == 'e-walet')
								            	<img src="{{$p->images}}" alt="" height="16" class="me-2">
								                @endif
                                                @endforeach
								            </div>
									    </div>
									    @endif
									    @if(count(DB::table('methods')->where('tipe','virtual-account')->get()) != 0)
										<div class="border-0 mb-3">
									        <h2 class="accordion-header" id="heading-3">
									            <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">Virtual Account (Dicek Otomatis)</button>
									        </h2>
									        <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="heading-3" data-bs-parent="#accordionExample">
									            <div class="accordion-body" style="background: var(--warna_4);">
									            	<div class="row">
														@foreach($pay_method as $p)
                                                        @if($p->tipe == 'virtual-account')
    													<div class="col-md-4 col-6 col-method">
    														<div class="method mb-3" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    															<h6></h6>
    															<img src="{{$p->images}}" alt="" class="max-h-5" style="max-width: 100%;height: auto;">
    															<hr>
    															<span>{{$p->name}}</span>
    														</div>
    													</div>
    													@endif
                                                        @endforeach
													</div>
									            </div>
									        </div>
								            <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
								            	@foreach($pay_method as $p)
                                                @if($p->tipe == 'virtual-account')
								            	<img src="{{$p->images}}" alt="" height="16" class="me-2">
								                @endif
                                                @endforeach
								            </div>
									    </div>
									    @endif
									    @if(count(DB::table('methods')->where('tipe','convenience-store')->get()) != 0)
										<div class="border-0 mb-3">
									        <h2 class="accordion-header" id="heading-4">
									            <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">Retail Outlets (Dicek Otomatis)</button>
									        </h2>
									        <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="heading-4" data-bs-parent="#accordionExample">
									            <div class="accordion-body" style="background: var(--warna_4);">
									            	<div class="row">
														@foreach($pay_method as $p)
                                                        @if($p->tipe == 'convenience-store')
    													<div class="col-md-4 col-6 col-method">
    														<div class="method mb-3" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    															<h6></h6>
    															<img src="{{$p->images}}" alt="" class="max-h-5" style="max-width: 100%;height: auto;">
    															<hr>
    															<span>{{$p->name}}</span>
    														</div>
    													</div>
    													@endif
                                                        @endforeach
													</div>
									            </div>
									        </div>
								            <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
								            	@foreach($pay_method as $p)
                                                @if($p->tipe == 'convenience-store')
    							            	<img src="{{$p->images}}" alt="" height="16" class="me-2">
    							            	@endif
                                                @endforeach
								            </div>
									    </div>
									    @endif
									    @if(count(DB::table('methods')->where('tipe','pulsa')->get()) != 0)
									    <div class="border-0 mb-3">
									        <h2 class="accordion-header" id="heading-5">
									            <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">Pulsa (Dicek Otomatis)</button>
									        </h2>
									        <div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="heading-5" data-bs-parent="#accordionExample">
									            <div class="accordion-body" style="background: var(--warna_4);">
									            	<div class="row">
														@foreach($pay_method as $p)
                                                        @if($p->tipe == 'pulsa')
    													<div class="col-md-4 col-6 col-method">
    														<div class="method mb-3" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    															<h6></h6>
    															<img src="{{$p->images}}" alt="" class="max-h-5" style="max-width: 100%;height: auto;">
    															<hr>
    															<span>{{$p->name}}</span>
    														</div>
    													</div>
    													@endif
                                                        @endforeach
													</div>
									            </div>
									        </div>
								            <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
								            	@foreach($pay_method as $p)
                                                @if($p->tipe == 'pulsa')
    							            	<img src="{{$p->images}}" alt="" height="16" class="me-2">
    							            	@endif
                                                @endforeach
								            </div>
									    </div>
									    @endif
									</div>
							    </div>
								<div class="text-end">
									<input type="hidden" name="tombol" value="submit">
									<button class="btn btn-primary" type="button" id="btn-topup" onclick="topup_process();">Isi Saldo</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
<script type="text/javascript">
    function select_method(id) {
		var quantity = $("input[name=quantity]").val();

		if (!quantity || quantity < 1) {
			toastr.error('Silahkan masukan Jumlah isi saldo dahulu', 'Gagal');
		} else {
			$(".method").removeClass('active');
			$("#method-" + id).addClass('active');

			$("input[name=method]").val(id);
		}
	}
	$("input[name=quantity]").on('keyup', function() {
	        
	    $.ajax({
			url: "<?php echo route('ajax.ptopup') ?>",
			type: "POST",
			data: {
                '_token': '<?php echo csrf_token() ?>',
                'quantity': $(this).val()
            },
			dataType: "JSON",
			success: function(result) {
				for (let price in result) {
					$("#method-" + result[price].id + ' h6').text(result[price].price);
					$("#method-" + result[price].id).removeClass('disabled');
    				if (result[price].status == 'Dis') {
    					$("#method-" + result[price].id).addClass('disabled');
    				}
			    }
			}
		});
	});
    function topup_process() {
        var quantity = $("input[name=quantity]").val();
		var method = $("input[name=method]").val();
		if (quantity < 10000) {
			toastr.error('Isi saldo minimal Rp 10.000', 'Gagal');
		} else if (!method) {
			toastr.error('Silahkan pilih metode pembayaran', 'Gagal');
		} else {
			$("#btn-topup").text('Loading...').attr('disabled', 'disabled');
			setTimeout(function() {
				$("#form-topup").submit();
			}, 1500);
		}
	}
</script>
@endsection