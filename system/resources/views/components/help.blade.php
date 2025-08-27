@extends('../main')

@section('css')

@endsection

@section('content')
<div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<h4 class="fw-700 mb-3">Bantuan</h4>
					<div class="row">
						<div class="col-md-5 d-none-sm">
							<img src="/assets/images/bantuan.png" alt="" class="w-100 mb-4 mt-4">
						</div>
						<div class="col-md-7">
							<div class="card mb-4 shadow-form">
								<div class="card-body">
									<div class="mb-4">
									    <h2 class="mb-2" dir="rtl" style="text-align:left"><span style="color:#ffffff"><strong>{{ !$config ? '' : $config->judul_web }}</strong></span></h2>

<address><span style="font-size:14px">{{ !$config ? '' : $config->alamat_web }}</span></address>

<address><span style="font-size:14px"><img alt="" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/512px-WhatsApp.svg.png" style="height:25px; width:25px" />&nbsp;&nbsp;<a href="http://Wa.me/{{ !$config ? '' : $config->nomor_admin }}">{{ !$config ? '' : $config->nomor_admin }}</a></span></address>

<address><span style="font-size:14px">&nbsp;<img alt="" src="https://cdn4.iconfinder.com/data/icons/social-media-logos-6/512/112-gmail_email_mail-512.png" style="height:25px; width:25px" />&nbsp;</span><span style="font-size:14px"><a href="mailto:{{ !$config ? '' : $config->email_admin }}">{{ !$config ? '' : $config->email_admin }}</a></span></address>

<hr />
<p>Jika ada pertanyaan atau masalah dalam transaksi, Silahkan kirim pesan melalui form dibawah. Kami akan segera membalas pesan yang kamu kirimkan.</p>
									</div>
									<form action="{{ route('post.pesan') }}" method="POST">
									    @csrf
										<div class="mb-3">
											<input type="text" class="form-control form-control-games" placeholder="Nama Lengkap" name="name" autocomplete="off">
										</div>
										<div class="mb-3">
											<input type="number" class="form-control form-control-games" placeholder="No. Whatsapp" name="wa" autocomplete="off">
										</div>
										<div class="mb-3">
											<textarea cols="30" rows="4" class="form-control form-control-games" placeholder="Pesan" name="message"></textarea>
										</div>
										<div class="text-end">
											<button class="btn btn-primary" type="submit" name="tombol" value="submit">Kirim Pesan</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@section('js')
<script>
    @if(session('success'))
	    toastr.success("{{ session('success') }}", "Success");
    @endif
    @if(session('error'))
	    toastr.error("{{ session('error') }}", "Error");
    @endif
</script>
@endsection
@endsection