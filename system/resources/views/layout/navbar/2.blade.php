    <header class="bg-navbar-background text-navbar-foreground">
	    <div class="fixed top-0 z-50 w-full">
	        <div class="navbar-head bg-navbar-background backdrop-blur-xl">
	            <div class="flex h-12 w-full gap-x-3 md:h-14 md:gap-x-8 xl:gap-x-12 mx-auto max-w-7xl px-4">
	                <div class="relative flex shrink-0">
	                    <div class="flex flex-shrink-0 items-center">
	                        <a class="flex max-w-[12rem] items-center py-2 h-full !max-w-[6rem] !py-[0.3rem] md:!max-w-[12rem] md:!py-2" href="/">
	                            <img alt="logo" fetchpriority="high" width="0" height="0" decoding="async" data-nimg="1" class="aspect-auto h-full w-auto max-w-full object-contain" src="{{ !$config ? '' : $config->logo_header }}" style="color: transparent;">
	                        </a>
	                    </div>
	                </div>
	                <div class="flex h-full flex-1 items-center">
	                    <div class="flex h-3-4 w-full items-center justify-end space-x-4 text-sm">
	                        <div class="relative hidden h-full w-full md:block">
	                            <div class="navbar-search relative ml-auto flex h-full items-center justify-end w-full">
	                                <div class="absolute right-4 top-1/2 -translate-y-1/2 transform cursor-pointer">
    	                                <input class="rounded-full border-none outline-none transition-[width] delay-100 searcheyes" placeholder="" type="text" value="" name="searchInput" id="searchInput" /> 
    	                                <div class="close">
                                            <span class="front"></span>
                                            <span class="back"></span>
                                        </div>
	                                </div>
	                            </div>
	                            <div class="absolute inset-x-0 rounded-lg rounded-t-none bg-primary shadow-md md:rounded-t-lg hidden md:block animate-fade-enter" style="max-height: 600px;overflow:auto;"></div>
	                        </div>
	                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search text-2xl md:hidden" id="showSearchButton" type="button"  aria-hidden="true">
	                            <circle cx="11" cy="11" r="8"></circle>
	                            <path d="m21 21-4.3-4.3"></path>
	                        </svg>
	                        <button class="navbar-toggler border-0 lg:hidden" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
    	                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart2 -rotate-90 text-2xl">
    	                            <line x1="18" x2="18" y1="20" y2="10"></line>
    	                            <line x1="12" x2="12" y1="20" y2="4"></line>
    	                            <line x1="6" x2="6" y1="20" y2="14"></line>
    	                        </svg>
    	                    </button>
    	                    <div data-orientation="vertical" role="none" class="shrink-0 bg-primary-foreground h-full w-[1px] hidden xl:block"></div>
    	                    @if(Auth::check())
    	                    <div class="hidden lg:block">
    	                        <div class="flex w-64 items-center justify-between">
    	                            <div class="flex items-center gap-x-3">
    	                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-general p-2">
    	                                    <img alt="credit" loading="lazy" width="0" height="0" decoding="async" data-nimg="1" class="aspect-auto h-auto w-full max-w-full object-contain" src="{{ !$config ? '' : $config->logo_header }}" style="color: transparent;">
    	                                </div>
    	                                <div>
    	                                    <div class="mb-1 text-xs leading-none">Balance</div>
    	                                    <div class="line-clamp-1 text-xs font-bold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }},-</div>
    	                                </div>
    	                            </div>
    	                            <a class="" href="/user/topup">
    	                                <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-semibold ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-x-3 border border-button bg-background text-primary-foreground h-10 px-4 py-2 !h-8 rounded-full !text-xs">Deposit</button>
    	                            </a>
    	                        </div>
    	                    </div>
    	                    <div data-orientation="vertical" role="none" class="shrink-0 bg-primary-foreground h-full w-[1px] hidden lg:block"></div>
    	                    <div class="hidden lg:block h-8">
    	                        <div class="menu-right">
        	                        <div class="dropdown d-inline-block">
            							<button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true" style="margin-top: -0.1rem;color:white;">
            	                            <span class="inline-block h-8 w-8 overflow-hidden rounded-lg text-primary-foreground !bg-general !text-primary-foreground">
            	                                <span class="flex h-full w-full items-center justify-center text-xs">PFL</span>
            	                            </span>
            	                        </button>
            							<ul class="dropdown-menu">
            							    <li>
            									<a class="dropdown-item" href="/user/dashboard">
            										<i class="fa-solid fa-circle-user fa-2xl" style="color: white;"></i>
            										Profil
            									</a>
            								</li>
            								@if(Auth()->user()->role == 'Admin')
            								<li>
            									<a class="dropdown-item" href="/dashboard">
            										<i class="fa fa-light fa-rocket"></i>
            										Halaman Admin
            									</a>
            								</li>
            								@endif
            								<li>
            									<hr>
            								</li>
            								<li>
            									<div class="dropdown-auth">
            										<div class="row">
            											<div class="col-6">
            												<a href="{{ route('logout') }}" class="text-decoration-none text-white">
            													<i class="fa fa-sign-in me-2"></i>
            													Logout
            												</a>
            											</div>
            										</div>
            									</div>
            								</li>
            							</ul>
        	                        </div>
        	                    </div>
    	                    </div>
    	                    @else
    	                    <div class="hidden lg:block">
    	                        <a href="{{ route('login') }}">
    	                            <div class="flex items-center gap-x-3">
    	                                <div class="flex h-full w-10 items-center justify-center rounded-xl bg-primary py-1">
    	                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in text-2xl text-primary-foreground">
    	                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
    	                                        <polyline points="10 17 15 12 10 7"></polyline>
    	                                        <line x1="15" x2="3" y1="12" y2="12"></line>
    	                                    </svg>
    	                                </div>
    	                                <span class="text-sm">@lang('page_home.masuk_daftar')</span>
    	                            </div>
    	                        </a>
    	                    </div>
    	                    @endif
    	                </div>
    	            </div>
    	        </div>
    	        <div class="container transition-all duration-300 ease-in-out d-lg-none" id="searchContainer">
    	            <div class="h-full">
    	                <input class="h-full w-full rounded-full border-none bg-input-background px-3 py-1 text-input-foreground outline-none" placeholder="Cari ..." type="search" value="" name="searchInputs" id="searchInputs">
    	            </div>
    	            <div class="absolute inset-x-0 rounded-lg rounded-t-none bg-primary shadow-md md:rounded-t-lg" style="max-height: 600px;overflow:auto;margin: 1rem;" id="live_search"></div>
    	        </div>
    	    </div>
        </div>
        <div class="navbar-menus lg:border-t lg:border-[#e5e7eb] lg:py-2 mt-12 md:mt-14">
            <div class="mx-auto max-w-7xl px-4">
                <nav class="hidden w-full overflow-auto lg:flex lg:space-x-6 lg:py-2 xl:space-x-8" style="cursor: auto;">
                    <a href="{{ route('home') }}" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home ">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>@lang('page_home.beranda')
                        </div>
                    </a>
                    <a href="{{ route('cari') }}" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search ">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>@lang('page_home.lacak_pesanan')
                        </div>
                    </a>
                    <a href="{{ route('price') }}" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt ">
                                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"></path>
                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                <path d="M12 17V7"></path>
                            </svg>@lang('page_home.daftar_harga')
                        </div>
                    </a>
                    <!--<a href="/calculator/mlbb" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calculator ">
                                <rect width="16" height="20" x="4" y="2" rx="2"></rect>
                                <line x1="8" x2="16" y1="6" y2="6"></line>
                                <line x1="16" x2="16" y1="14" y2="18"></line>
                                <path d="M16 10h.01"></path>
                                <path d="M12 10h.01"></path>
                                <path d="M8 10h.01"></path>
                                <path d="M12 14h.01"></path>
                                <path d="M8 14h.01"></path>
                                <path d="M12 18h.01"></path>
                                <path d="M8 18h.01"></path>
                            </svg>@lang('page_home.kalkulator_mlbb')
                        </div>
                    </a>-->
                    <a href="/reviews" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text ">
                                <path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4"></path>
                                <path d="M19 17V5a2 2 0 0 0-2-2H4"></path>
                                <path d="M15 8h-5"></path>
                                <path d="M15 12h-5"></path>
                            </svg>@lang('page_home.ulasan')
                        </div>
                    </a>
                    <a href="/artikel" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper ">
                                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                <path d="M18 14h-8"></path>
                                <path d="M15 18h-5"></path>
                                <path d="M10 6h8v4h-8V6Z"></path>
                            </svg>@lang('page_home.berita')
                        </div>
                    </a>
                    @if(Auth::check())
                    <a href="/user/orders" style="cursor: pointer;">
                        <div class="flex w-max items-center gap-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper ">
                                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                <path d="M18 14h-8"></path>
                                <path d="M15 18h-5"></path>
                                <path d="M10 6h8v4h-8V6Z"></path>
                            </svg>Pembelian
                        </div>
                    </a>
                    @endif
                </nav>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel"  visibility: visible; aria-modal="true" role="dialog">
            <div class="content absolute inset-y-0 right-0 flex w-3/5 bg-navbar-background text-navbar-foreground backdrop-blur transition-all duration-300 ease-in-out md:w-2/5 translate-x-full">
                <div class="flex w-full flex-col justify-between overflow-y-auto px-4 py-6">
                    <div class="flex flex-col justify-between">
                        <div class="mb-5" style="display: flex;flex-direction: column-reverse;justify-content: center;">
                        <button type="button" class="fixed rounded-full text-gray-400 hover:text-btnPurple focus:outline-none focus:ring-2 focus:ring-white" data-bs-dismiss="offcanvas" aria-label="Close" style="left:-2rem;">
                        <span class="sr-only">Close panel</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" aria-hidden="true" class="bg-general rounded-full h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        </button>
                        <a class="flex h-auto max-w-[12rem] items-center py-2 !max-w-[4rem]" href="/">
                            <img alt="logo" fetchpriority="high" width="0" height="0" decoding="async" data-nimg="1" class="aspect-auto h-full w-auto max-w-full object-contain" src="{{ !$config ? '' : $config->logo_header }}" style="color: transparent;">
                        </a>
                        </div>
                        @if(Auth::check())
                        <hr>
                        <a href="/user/dashboard">
                            <div class="flex items-center">
                                <div class="flex flex-1 items-center gap-x-3">
                                    <span class="inline-block h-8 w-8 overflow-hidden rounded-lg text-primary-foreground bg-general">
                                        <span class="flex h-full w-full items-center justify-center text-xs">PFL</span>
                                    </span>
                                    <span class="line-clamp-1 text-sm">{{Str::title(Auth()->user()->name)}}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </div>
                        </a>
                        <hr>
                        @if(Auth()->user()->role == 'Admin')
                        <a href="{{ route('dashboard') }}">
                            <div class="flex items-center">
                                <div class="flex flex-1 items-center gap-x-3">
                                    <span class="line-clamp-1 text-sm">Dashboard Admin</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </div>
                        </a>
                        @endif
                        <hr>
            			@endif
                        <div class="mt-8 flex flex-col gap-y-5">
                            <a href="{{ route('home') }}">
                                <div class="flex w-max items-center gap-x-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home ">
                                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>@lang('page_home.beranda')
                                </div>
                            </a>
                            <a href="{{ route('cari') }}">
                                <div class="flex w-max items-center gap-x-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search ">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </svg>@lang('page_home.lacak_pesanan')
                                </div>
                            </a>
                            <a href="{{ route('price') }}">
                                <div class="flex w-max items-center gap-x-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt ">
                                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"></path><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                        <path d="M12 17V7"></path>
                                    </svg>@lang('page_home.daftar_harga')
                                </div>
                            </a>
                            <!--<a href="/calculator/mlbb">
                                <div class="flex w-max items-center gap-x-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calculator ">
                                        <rect width="16" height="20" x="4" y="2" rx="2"></rect>
                                        <line x1="8" x2="16" y1="6" y2="6"></line>
                                        <line x1="16" x2="16" y1="14" y2="18"></line>
                                        <path d="M16 10h.01"></path>
                                        <path d="M12 10h.01"></path>
                                        <path d="M8 10h.01"></path>
                                        <path d="M12 14h.01"></path>
                                        <path d="M8 14h.01"></path>
                                        <path d="M12 18h.01"></path>
                                        <path d="M8 18h.01"></path>
                                    </svg>@lang('page_home.kalkulator_mlbb')
                                </div>
                            </a>-->
                            <a href="/reviews">
                                <div class="flex w-max items-center gap-x-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text ">
                                        <path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4"></path>
                                        <path d="M19 17V5a2 2 0 0 0-2-2H4"></path>
                                        <path d="M15 8h-5"></path>
                                        <path d="M15 12h-5"></path>
                                    </svg>@lang('page_home.ulasan')
                                </div>
                            </a>
                            <a href="/artikel">
                                <div class="flex w-max items-center gap-x-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper ">
                                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                        <path d="M18 14h-8"></path>
                                        <path d="M15 18h-5"></path>
                                        <path d="M10 6h8v4h-8V6Z"></path>
                                    </svg>@lang('page_home.berita')
                                </div>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div data-orientation="horizontal" role="none" class="shrink-0 bg-primary-foreground h-[1px] w-full my-5"></div>
                        <div class="flex flex-col gap-y-8">
                            @if(Auth::check())
                            <div class="flex w-64 items-center justify-between !w-full">
                                <div class="flex items-center gap-x-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-general p-2">
                                        <img alt="credit" loading="lazy" width="0" height="0" decoding="async" data-nimg="1" class="aspect-auto h-auto w-full max-w-full object-contain" src="https://static-src.vocagame.com/rozezshop/LOGO-DEPOSIT-5de2.webp" style="color: transparent;">
                                    </div>
                                    <div>
                                        <div class="mb-1 text-xs leading-none">Balance</div>
                                        <div class="line-clamp-1 text-xs font-bold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }},-</div>
                                    </div>
                                </div>
                                <a class="" href="/user/topup">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-semibold ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-x-3 border border-button bg-background text-primary-foreground h-10 px-4 py-2 !h-8 rounded-full !text-xs">Deposit</button>
                                </a>
                            </div>
                            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md font-semibold ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-x-3 hover:bg-accent h-10 px-4 py-2 !h-8 !rounded-full border border-danger-400 text-xs text-danger-400" type="button">Log Out</button>
                            @else
                            <a href="{{ route('login') }}">
                                <div class="flex items-center gap-x-3">
                                    <div class="flex h-full w-10 items-center justify-center rounded-xl bg-primary py-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in text-2xl text-primary-foreground">
                                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                            <polyline points="10 17 15 12 10 7"></polyline>
                                            <line x1="15" x2="3" y1="12" y2="12"></line>
                                        </svg>
                                    </div>
                                    <span class="text-sm">@lang('page_home.masuk_daftar')</span>
                                </div>
                            </a>
                            @endif
                            <p class="mt-64 text-center text-primary-foreground md:hidden !mt-0 text-xs !text-navbar-foreground">© 2023 {{ env('APP_NAME') }} . All rights reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>