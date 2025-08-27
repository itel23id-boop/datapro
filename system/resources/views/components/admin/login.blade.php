@extends('main')

@section('content')
<style>
    .top-\[16px\] {
        top: 16px;
    }
    
    .right-6 {
        right: 1rem;
    }
</style>
    <div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="card card-auth shadow-form">
						<div class="card-body">
							<h6 class="card-title">Login ke Akun</h6>
							<div class="col-md-6">
    							<form role="form" action="{{ route('post.login') }}" method="POST">
    							    @csrf
    								<div class="mb-3">
    									<input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off">
    								</div>
    								<div class="mb-3">
                                        <div class="relative">
                                            <input type="password" class="form-control" placeholder="Password" id="pass" autocomplete="off" name="password" required>
                                            <div id="mybutton" onclick="change()">
                                                <i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye-slash" style="color:#343434;"></i>
                                            </div>
                                        </div>
                					</div>
                					<div class="mb-3">
                                        <input type="checkbox" name="tac" id="tac">
                                            <label for="tac" class="form-check-label">
                                            <i>
                                            Remember Me?
                                            </i>
                                        </label>
    								</div>
                					<div class="mb-3">
    								    <div class="g-recaptcha" id="g-recaptcha" name="grecaptcha" data-sitekey="{{ env('CAPTCHA_SITEKEY') }}"></div>
    								</div>
    								<button class="btn btn-primary w-100" type="submit" name="tombol" value="submit">Submit</button>
    							</form>
    						</div>
							<p class="m-0 text-muted mt-4">Belum memiliki akun ? <a class="text-decoration-none" href="{{ route('register') }}" style="color:#828282;">Register</a></p>
							<p class="m-0 text-muted">Lupa password ? <a class="text-decoration-none" href="/forgot-password" style="color:#828282;">Reset</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
<script>
function change() {
var x = document.getElementById('pass').type;
if (x == 'password') {
    document.getElementById('pass').type = 'text';
    document.getElementById('mybutton').innerHTML = `<i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye" style="color:#343434;"></i>`;
} else {
    document.getElementById('pass').type = 'password';
    document.getElementById('mybutton').innerHTML = `<i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye-slash" style="color:#343434;"></i>`;
    }
}
</script>
@endsection