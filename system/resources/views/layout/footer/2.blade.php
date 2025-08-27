<footer class="relative overflow-hidden" aria-labelledby="footer-heading" style="background:var(--warna_3);">
	    <div class="altumcode altumcode-bottom_left altumcode-clickable on-fadeIn" data-position="bottom_left" data-on-animation="fadeIn" data-off-animation="fadeOut" data-notification-id="323" style="display: block; bottom: 20px;">
            <div role="dialog" class="altumcode-wrapper altumcode-wrapper-rounded altumcode-wrapper-shadow  altumcode-conversions-wrapper" style="background: var(--warna_1);font-family: inherit!important;border-width: 0px;border-color: #000;;">
                <div class="altumcode-conversions-content" id="notif-message">
                </div>
            </div>
        </div>
	    <div class="-z-10">
	        <div class="d-none d-sm-block">
    	        <img class="hidden h-4 w-4 sm:absolute sm:bottom-32 sm:left-14 sm:block sm:h-20 sm:w-20" src="/assets/images/ilustrations/triangle-gray.svg" alt="triangle">
    	        <img class="hidden h-4 w-4 -rotate-3 sm:absolute sm:bottom-4 sm:left-36 sm:block sm:h-20 sm:w-20" src="/assets/images/ilustrations/plus-gray.svg" alt="plus">
    	        <img class="hidden h-12 w-12 sm:absolute sm:bottom-2 sm:block sm:h-32 sm:w-32" src="/assets/images/ilustrations/cubic-gray.svg" alt="cubic">
	        </div>
	        <img class="absolute bottom-72 right-6 h-16 w-16 rotate-12 sm:bottom-16 sm:right-40 sm:h-16 sm:w-16" src="/assets/images/ilustrations/plus-gray.svg" alt="plus">
	        <img class="absolute bottom-44 right-4 h-24 w-24 sm:bottom-40 sm:right-20 sm:h-20 sm:w-20" src="/assets/images/ilustrations/cubic-gray.svg" alt="cubic">
	        <img class="absolute bottom-60 right-24 h-20 w-20 -rotate-12 text-gray-500 sm:-bottom-4 sm:right-0 sm:h-40 sm:w-40" src="/assets/images/ilustrations/triangle-gray.svg" alt="triangle">
	    </div>
	    <h2 id="footer-heading" class="sr-only">Footer</h2>
	    <div class="relative z-20 mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-16">
	        <div class="flex flex-col flex-wrap gap-7 md:flex-row lg:justify-between">
	            <div class="basis-2/5 space-y-8">
	                <img class="h-28 w-auto" src="{{ !$config ? '' : $config->logo_footer }}" alt="{{ env('APP_NAME') }}">
	                <p class="z-10 text-base text-white">{{ !$config ? '' : $config->deskripsi_web }}</p>
	            </div>
	            <div class="grid auto-rows-min grid-cols-2 gap-6">
	                <div>
	                    <h3 class="font-extrabold uppercase tracking-wider text-white" style="margin-bottom: -1rem;">MENU CEPAT</h3>
	                    <ul role="list" class="mt-4 space-y-4">
	                        <li>
	                            <a href="{{ route('home') }}" class="text-gray-100 hover:text-white">Beranda</a>
	                        </li>
	                        <li>
	                            <a href="{{ route('cari') }}" class="text-gray-100 hover:text-white">Lacak Pesan</a>
	                        </li>
	                        <li>
	                            <a href="{{ route('price') }}" class="text-gray-100 hover:text-white">Daftar Harga</a>
	                        </li>
	                    </ul>
	                </div>
	                <div>
	                    <h3 class="font-extrabold uppercase tracking-wider text-white" style="margin-bottom: -1rem;">HALAMAN AKUN</h3>
	                    <ul role="list" class="mt-4 space-y-4">
	                        <li>
	                            <a href="{{ route('login') }}" class="text-gray-100 hover:text-white">Masuk Akun</a>
	                        </li>
	                        <li>
	                            <a href="{{ route('register') }}" class="text-gray-100 hover:text-white">Daftar Akun</a>
	                        </li>
	                        <li>
	                            <a href="/forgot-password" class="text-gray-100 hover:text-white">Lupa Password</a>
	                        </li>
	                    </ul>
	                </div>
	                <div>
	                    <h3 class="font-extrabold uppercase tracking-wider text-white" style="margin-bottom: -1rem;">PERUSAHAAN</h3>
	                    <ul role="list" class="mt-4 space-y-4">
	                        <li>
	                            <a href="/page/terms" class="text-gray-100 hover:text-white">Syarat &amp; Ketentuan</a>
	                        </li>
	                        <li>
	                            <a href="/page/privacy-policy" class="text-gray-100 hover:text-white">Kebijakan Privasi</a>
	                        </li>
	                        <li>
	                            <a href="/page/help" class="text-gray-100 hover:text-white">Tentang Kami</a>
	                        </li>
	                        <li>
	                            <a href="/page/question" class="text-gray-100 hover:text-white">Pertanyaan Umum</a>
	                        </li>
	                    </ul>
	                </div>
	                <div>
	                    <h3 class="font-extrabold uppercase tracking-wider text-white" style="margin-bottom: -1rem;">HUBUNGI KAMI</h3>
	                    <ul role="list" class="mt-4 space-y-4">
	                        <li>
	                            <a target="" href="{{ route('help') }}" class="text-gray-100 hover:text-white" rel="noreferrer">Bantuan</a>
	                        </li>
	                        <li>
	                            <a target="_blank" href="{{ !$config ? '' : $config->url_wa }}" class="text-gray-100 hover:text-white" rel="noreferrer">Buat Bisnis Topup</a>
	                        </li>
	                        <li>
	                            <a target="_blank" href="{{ !$config ? '' : $config->url_wa }}" class="text-gray-100 hover:text-white" rel="noreferrer">Gabung Reseller</a>
	                        </li>
	                    </ul>
	                </div>
	            </div>
	            <div>
	                <div>
	                    <h3 class="font-extrabold uppercase tracking-wider text-white" style="margin-bottom: -0.2rem;">PARTNERSHIP</h3>
	                    <div class="flex gap-3">
	                        <a href="mailto:{{ !$config ? '' : $config->email_admin }}">
	                            {{ !$config ? '' : $config->email_admin }}
	                        </a>
	                    </div>
	                </div>
	                <div class="pt-7">
	                    <h3 class="font-extrabold uppercase tracking-wider text-white" style="margin-bottom: -1.5rem;">IKUTI KAMI</h3>
	                    <div class="flex space-x-6 pt-4">
	                        <a target="_blank" href="{{ !$config ? '' : $config->url_fb }}" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">Facebook</span>
	                            <svg fill="currentColor" viewBox="0 0 24 24" class="h-6 w-6" aria-hidden="true">
	                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
	                            </svg>
	                        </a>
	                        <a target="_blank" href="{{ !$config ? '' : $config->url_ig }}" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">Instagram</span>
	                            <svg fill="currentColor" viewBox="0 0 24 24" class="h-6 w-6" aria-hidden="true">
	                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
	                            </svg>
	                        </a>
	                        <a target="_blank" href="{{ !$config ? '' : $config->url_tiktok }}" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">Tiktok</span>
	                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
	                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 12a4 4 0 1 0 4 4v-12a5 5 0 0 0 5 5"></path>
	                            </svg>
	                        </a>
	                        <a target="_blank" href="{{ !$config ? '' : $config->url_wa }}" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">WhatsApp</span>
	                            <svg class="h-6 w-6" aria-hidden="true" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
	                                <path fill="#fff" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
	                            </svg>
	                        </a>
	                        <a target="_blank" href="{{ !$config ? '' : $config->url_youtube }}" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">Youtube</span>
	                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96.875 96.875" class="h-6 w-6" aria-hidden="true">
	                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
	                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
	                                <g id="SVGRepo_iconCarrier"><g>
	                                <path d="M95.201,25.538c-1.186-5.152-5.4-8.953-10.473-9.52c-12.013-1.341-24.172-1.348-36.275-1.341 c-12.105-0.007-24.266,0-36.279,1.341c-5.07,0.567-9.281,4.368-10.467,9.52C0.019,32.875,0,40.884,0,48.438 C0,55.992,0,64,1.688,71.336c1.184,5.151,5.396,8.952,10.469,9.52c12.012,1.342,24.172,1.349,36.277,1.342 c12.107,0.007,24.264,0,36.275-1.342c5.07-0.567,9.285-4.368,10.471-9.52c1.689-7.337,1.695-15.345,1.695-22.898 C96.875,40.884,96.889,32.875,95.201,25.538z M35.936,63.474c0-10.716,0-21.32,0-32.037c10.267,5.357,20.466,10.678,30.798,16.068 C56.434,52.847,46.23,58.136,35.936,63.474z"></path>
	                                </g></g>
	                            </svg>
	                        </a>
	                        <a target="_blank" href="mailto:{{ !$config ? '' : $config->email_admin }}" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">E-mail</span>
	                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff" class="h-6 w-6" aria-hidden="true">
	                                <path d="M4 7.00005L10.2 11.65C11.2667 12.45 12.7333 12.45 13.8 11.65L20 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
	                                <rect x="3" y="5" width="18" height="14" rx="2" stroke="#ffffff" stroke-width="2" stroke-linecap="round"></rect>
	                            </svg>
	                        </a>
	                        <a target="_blank" href="https://twitter.com/" class="text-white hover:text-white" rel="noreferrer">
	                            <span class="sr-only">Twitter</span>
	                            <svg class="h-6 w-6" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
	                                <path d="M19.83 8.00001C19.83 8.17001 19.83 8.35001 19.83 8.52001C19.8393 10.0302 19.5487 11.5272 18.9751 12.9242C18.4014 14.3212 17.5562 15.5904 16.4883 16.6583C15.4204 17.7262 14.1512 18.5714 12.7542 19.1451C11.3572 19.7187 9.86017 20.0093 8.34999 20C6.15213 20.0064 3.9992 19.3779 2.14999 18.19C2.47999 18.19 2.78999 18.19 3.14999 18.19C4.96345 18.19 6.72433 17.5808 8.14999 16.46C7.30493 16.4524 6.48397 16.1774 5.80489 15.6744C5.12581 15.1714 4.62349 14.4662 4.36999 13.66C4.62464 13.7006 4.88213 13.7207 5.13999 13.72C5.49714 13.7174 5.85281 13.6738 6.19999 13.59C5.2965 13.4056 4.48448 12.9147 3.90135 12.2003C3.31822 11.486 2.99981 10.5921 2.99999 9.67001C3.55908 9.97841 4.18206 10.153 4.81999 10.18C4.25711 9.80767 3.79593 9.30089 3.47815 8.7055C3.16038 8.11011 2.99604 7.44489 2.99999 6.77001C3.00124 6.06749 3.18749 5.37769 3.53999 4.77001C4.55172 6.01766 5.81423 7.03889 7.24575 7.76757C8.67727 8.49625 10.2459 8.91613 11.85 9.00001C11.7865 8.69737 11.753 8.38922 11.75 8.08001C11.7239 7.25689 11.9526 6.44578 12.4047 5.75746C12.8569 5.06913 13.5104 4.53714 14.2762 4.23411C15.0419 3.93109 15.8826 3.87181 16.6833 4.06437C17.484 4.25693 18.2057 4.69195 18.75 5.31001C19.655 5.12822 20.5214 4.78981 21.31 4.31001C21.0088 5.24317 20.3754 6.0332 19.53 6.53001C20.3337 6.44316 21.1194 6.23408 21.86 5.91001C21.3116 6.71097 20.6361 7.41694 19.86 8.00001H19.83Z" fill="#ffffff"></path>
	                            </svg>
	                        </a>
	                    </div>
	                </div>
	                
	                <div class="pt-7">
	                    <h3 class="font-extrabold uppercase tracking-wider text-white">MENDUKUNG PEMBAYARAN</h3>
	                    <div class="flex gap-3">
	                        <marquee class="mb-2-mobile" style="width:200px;" id="marquee-method">
        					    @foreach($pay_method as $p)
        					    <img src="{{ $p->images }}" height="22" class="me-3">
        					    @endforeach
        					</marquee>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <hr>
	        <div class="border-t border-gray-200 pt-4 md:mb-0">
	            <p class="text-base text-white xl:text-center">© 2023 {{ env('APP_NAME') }}</p>
	        </div>
	    </div>
	</footer>