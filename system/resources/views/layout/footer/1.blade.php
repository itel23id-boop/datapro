    <div class="footer d-none-print border-0 shadow-form">
	    <div id="EnternityContainer"></div>
		<div class="container">
			<div class="row justify-content-between">
			    <div class="col-md-4 text-center-mobile mb-1-mobile">
					<img src="{{ !$config ? '' : $config->logo_footer }}" alt="" width="168">
				</div>
				<div class="col-md-6 text-end">
					<marquee class="mb-2-mobile" style="margin-top: 12px;" id="marquee-method">
					    @foreach($pay_method as $p)
					    <img src="{{ $p->images }}" height="22" class="me-3">
					    @endforeach
					</marquee>
				</div>
			</div>
			<hr>
			<div class="row mb-4">
			    <div class="col-md-6 text-center-mobile">
					<p>{{ !$config ? '' : $config->deskripsi_web }}</p>
				</div>
				<div class="col-md-6 text-end pt-3 text-center-mobile mb-2-mobile">
					<a href="{{ !$config ? '' : $config->url_wa }}" class="text-decoration-none me-2" target="_blank">
						<img src="/assets/images/1692816423_f31591f31199bdfc9db2.png" alt="" width="34">
					</a>
					<a href="{{ !$config ? '' : $config->url_tiktok }}" class="text-decoration-none me-2" target="_blank">
						<img src="/assets/images/1692811285_e4273f48f209e7804c14.png" alt="" width="34">
					</a>
					<a href="{{ !$config ? '' : $config->url_ig }}" class="text-decoration-none me-2" target="_blank">
						<img src="/assets/images/1692811166_7d28383174bb294ec733.png" alt="" width="34">
					</a>
					<a href="{{ !$config ? '' : $config->url_youtube }}" class="text-decoration-none me-2" target="_blank">
						<img src="/assets/images/1692810651_78c886f566996362e140.png" alt="" width="34">
					</a>
					<a href="{{ !$config ? '' : $config->url_fb }}" class="text-decoration-none me-2" target="_blank">
						<img src="/assets/images/1692810546_c7376627f25f93f72051.png" alt="" width="34">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<p class="text-center-mobile">Copyright &copy; 2023 {{ env('APP_NAME') }} - All Right Reserved</p>
				</div>
				<div class="col-md-6 text-end text-center-mobile">
					<a href="/page/help" class="text-white text-decoration-none me-2">Bantuan</a>
					<a href="/page/question" class="text-white text-decoration-none me-2">Pertanyaan Umum</a>
					<a href="/page/terms" class="text-white text-decoration-none me-2">Terms and Conditions</a>
					<a href="/page/privacy-policy" class="text-white text-decoration-none me-2">Privacy Policy</a>
				</div>
			</div>
		</div>
	</div>