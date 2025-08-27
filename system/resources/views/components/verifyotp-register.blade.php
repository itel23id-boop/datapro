@extends('main')

@section('content')
    <div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card shadow-form">
						<div class="card-body">
							<h6 class="card-title">Check Your Email for OTP Code!</h6>
							<form role="form" action="{{url('/register/otp/verify')}}" method="POST">
							    @csrf
							    <div class="mb-3 row">
            						<div class="col-2"><input type="text" autocomplete="off" class="form-control text-center" id="otp-1" name="otp[]" onkeyup="input_otp('1', this.value);" placeholder="" required></div>
            						<div class="col-2"><input type="text" autocomplete="off" class="form-control text-center" id="otp-2" name="otp[]" onkeyup="input_otp('2', this.value);" placeholder="" required></div>
            						<div class="col-2"><input type="text" autocomplete="off" class="form-control text-center" id="otp-3" name="otp[]" onkeyup="input_otp('3', this.value);" placeholder="" required></div>
            						<div class="col-2"><input type="text" autocomplete="off" class="form-control text-center" id="otp-4" name="otp[]" onkeyup="input_otp('4', this.value);" placeholder="" required></div>
            						<div class="col-2"><input type="text" autocomplete="off" class="form-control text-center" id="otp-5" name="otp[]" onkeyup="input_otp('5', this.value);" placeholder="" required></div>
            						<div class="col-2"><input type="text" autocomplete="off" class="form-control text-center" id="otp-6" name="otp[]" onkeyup="input_otp('6', this.value);" placeholder="" required></div>
            					</div>
								<button class="btn btn-primary w-100" type="submit" name="tombol" value="submit">Confirm</button>
							</form>
							<p class="m-0 text-muted mt-4"><a class="text-decoration-none" href="{{ route('register') }}" style="color:#828282;">Kembali ke Register</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
<script>
	function input_otp(ke, otp) {
		if (ke < 6) {
			if ($("#otp-" + ke).val().length == 1) {
				var next = parseInt(ke) + 1;
				$("#otp-" + next).focus();
			}
		} else {
			if ($("#otp-6").val().length > 1) {
				$("#otp-6").val(otp.slice(1, 2));
			}
		}
	}
</script>
@endsection