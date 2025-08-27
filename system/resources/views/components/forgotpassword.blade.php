@extends('main')

@section('content')
    <div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card shadow-form">
						<div class="card-body">
							<h6 class="card-title">Lupa password Akun</h6>
							<form role="form" action="{{url('/forgot-password')}}" method="POST">
							    @csrf
							    <div class="mb-3">
									<input type="text" class="form-control @error('users') is-invalid @enderror" placeholder="No.Whatsapp / Username / Email" name="users" autocomplete="off">
								    @error('users')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
								</div>
								<button class="btn btn-primary w-100" type="submit" name="tombol" value="submit">Request reset</button>
							</form>
							<p class="m-0 text-muted mt-4"><a class="text-decoration-none" href="{{ route('login') }}" style="color:#828282;">Kembali ke login</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
@endsection