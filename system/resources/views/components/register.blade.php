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
					<div class="card card-auth-register shadow-form">
						<div class="card-body">
							<h6 class="card-title">Registrasi Akun</h6>
							<div class="col-md-6">
    							<form role="form" action="{{ route('post.register') }}" method="POST">
    							    @csrf
    							    <div class="mb-3 row">
    							        <label class="col-lg-1 col-form-label d-block d-sm-none"></label>
    							        <div class="col-lg-5">
        									<input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama" name="name" autocomplete="off">
        								    @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <label class="col-lg-1 col-form-label d-block d-sm-none"></label>
                                        <div class="col-lg-5">
        									<input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" autocomplete="off">
        								    @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
    								</div>
    								<div class="mb-3">
    									<input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username" name="username" autocomplete="off">
    								    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
    								</div>
    								<div class="mb-3">
    								    <div class="input-group">
        								    <select id="select2" name="format_wa">
        									    @foreach($phone_country as $p_c)
        										<option value="{{$p_c->code}},{{$p_c->dial_code}}">{{$p_c->dial_code}}</option>
        										@endforeach
        									</select>
        								    <input type="text" class="form-control @error('no_wa') is-invalid @enderror" placeholder="No. Whatsapp" name="no_wa" autocomplete="off" onkeyup="loadNomor(this.value);">
        								</div>
    								</div>
    								<div class="mb-3">
                                        <div class="relative">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" id="pass" autocomplete="off" name="password">
                                            <div id="mybutton" onclick="change()">
                                                <i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye-slash" style="color:#343434;"></i>
                                            </div>
                                        </div>
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                					</div>
    								<div class="mb-3">
    									<div class="relative">
                                            <input type="password" class="form-control @error('password2') is-invalid @enderror" placeholder="Ulangi Password" id="repass" autocomplete="off" name="password2">
                                            <div id="mybuttonre" onclick="rechange()">
                                                <i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye-slash" style="color:#343434;"></i>
                                            </div>
                                        </div>
    								    @error('password2')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
    								</div>
                                    <div class="mb-3">
                                        <div class="flex">
                                            <input required="" type="checkbox" name="tac" id="tac">
                                            <label for="tac" style="font-size: 10px;" class="form-check-label ms-2">
                                                <i>
                                                I agree to <a href="/page/terms">Terms
                                                and conditions</a> and <a href="/page/privacy-policy">Privacy Policy</a>
                                                </i>
                                            </label>
                                        </div>
    								</div>
    								<div class="mb-3">
    								    <div class="g-recaptcha" id="g-recaptcha" name="grecaptcha" data-sitekey="{{ env('CAPTCHA_SITEKEY') }}"></div>
    								</div>
    								<button class="btn btn-primary w-100" type="submit" name="tombol" value="submit">Submit</button>
    							</form>
    						</div>
							<p class="m-0 text-muted mt-4">Sudah memiliki akun ? <a class="text-decoration-none" href="{{ route('login') }}" style="color:#828282;">Login</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
<script>
function formatState (state) {
    if (!state.id) {
        return state.text;
    }
            
    flag = state.element.value.split(',');
            
    var $state = $('<span><img src="https://flagcdn.com/16x12/' + flag[0] + '.png" class="eniv-flag"> ' + state.text + '</span>');
            
    return $state;
};
$("#select2").select2({
    templateResult: formatState,
    minimumResultsForSearch: -1,
});
$("#select2").on('select2:select', function (e) {
    var flag = e.params.data.id.split(',');
    $(".select2-selection__rendered").html('<img src="https://flagcdn.com/24x18/' + flag[0] + '.png" class="eniv-flag">');
});
setTimeout(function() {
    if ($(".select2-selection__rendered").text() == '+62') {
        $(".select2-selection__rendered").html('<img src="https://flagcdn.com/24x18/id.png" class="eniv-flag">');
    }
}, 300);
function formatNomorHP(nomorHP, kodeNegara) {
    var kodeNegaraNow = nomorHP.split(' ')[0];
    nomorHP = nomorHP.replace(/\D/g, '');
    if (nomorHP.substring(0, 1) == 0) {
        nomorHP = nomorHP.substring(1);
    }
    //var list_flag = ['+62', '+60', '+65', '+673', '+84', '+66', '+95', '+81', '+82', '+86', '+886', '+1'];
            
    if (kodeNegara.includes(kodeNegaraNow) == true) {
        nomorHP = nomorHP.substring(kodeNegaraNow.length - 1);
    }
            
    nomorHP = nomorHP.replace(/(\d{3})(\d{4})(\d{4})/, '$1 $2 $3');
            
    return nomorHP;
}
function loadNomor(nohp) {
    var flag = $("select[name=format_wa]").val().split(',');
    $("input[name=no_wa]").val(flag[1] + ' ' + formatNomorHP(nohp, flag[1]));
}
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
function rechange() {
var x = document.getElementById('repass').type;
if (x == 'password') {
    document.getElementById('repass').type = 'text';
    document.getElementById('mybuttonre').innerHTML = `<i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye" style="color:#343434;"></i>`;
} else {
    document.getElementById('repass').type = 'password';
    document.getElementById('mybuttonre').innerHTML = `<i class="w-5 h-5 absolute cursor-pointer top-[16px] right-6 fa fa-eye-slash" style="color:#343434;"></i>`;
    }
}
</script>
@endsection