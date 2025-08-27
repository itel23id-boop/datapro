@extends('../../main', ['activeMenu' => 'home', 'activeSubMenu' => ''])
@section('content')
<style>
        .box-games {
          position:relative;
        }
        .ribbon {
           position: absolute;
           right: -5px; top: -5px;
           z-index: 1;
           overflow: hidden;
           width: 75px; height: 75px; 
           text-align: right;
        }
        .ribbon span {
           font-size: 10px;
           color: #fff; 
           text-transform: uppercase; 
           text-align: center;
           font-weight: bold; line-height: 20px;
           transform: rotate(45deg);
           width: 100px; display: block;
           background: #79A70A;
           background: linear-gradient(#9BC90D 0%, #79A70A 100%);
           box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
           position: absolute;
           top: 19px; right: -21px;
        }
        .ribbon span::before {
           content: '';
           position: absolute; 
           left: 0px; top: 100%;
           z-index: -1;
           border-left: 3px solid #79A70A;
           border-right: 3px solid transparent;
           border-bottom: 3px solid transparent;
           border-top: 3px solid #79A70A;
        }
        .ribbon span::after {
           content: '';
           position: absolute; 
           right: 0%; top: 100%;
           z-index: -1;
           border-right: 3px solid #79A70A;
           border-left: 3px solid transparent;
           border-bottom: 3px solid transparent;
           border-top: 3px solid #79A70A;
        }
        .red span {background: linear-gradient(#F70505 0%, #8F0808 100%);}
        .red span::before {border-left-color: #8F0808; border-top-color: #8F0808;}
        .red span::after {border-right-color: #8F0808; border-top-color: #8F0808;}
        
        .blue span {background: linear-gradient(#2989d8 0%, #1e5799 100%);}
        .blue span::before {border-left-color: #1e5799; border-top-color: #1e5799;}
        .blue span::after {border-right-color: #1e5799; border-top-color: #1e5799;}
</style>
<div class="content-mains">
		<div class="mx-auto max-w-7xl px-4">
		    <!--<div class="row justify-content-between">
				<div class="col-md-12">
					<div class=" swiper my-swiper swiper-coverflow swiper-3d swiper-initialized swiper-horizontal swiper-pointer-events swiper-watch-progress swiper-container swiper-container-mobile">
                        <div class="swiper-wrapper">
                            @php $i = 1; @endphp 
                            @foreach($banner as $data) 
                            @php $active = ($i == 1) ? 'active' : ''; @endphp
                            <div class="swiper-slide " data-swiper-slide-index="{{ $i-1 ,}}" role="group" aria-label="{{ $i }} / {{ count($banner) }}">
                              <img src="{{ $data->path }}" alt="" class="d-block w-100 rounded-xl">
                              <div class="swiper-slide-shadow-left" style="opacity: 2; transition-duration: 300ms;"></div>
                              <div class="swiper-slide-shadow-right" style="opacity: 0; transition-duration: 300ms;"></div>
                            </div>
                            @php $i++; @endphp 
                            @endforeach
                        </div>
                    </div>
				</div>
			</div>-->
			<div class="games-area mt-3">
			    <div class="mb-4">
			        <div style="display: flex;flex-direction: row;align-items: baseline;">
					<i class="fa-solid fa-fire fa-beat-fade fa-lg text-warning"></i><h5 class="mb-3" style="margin-left: 8px;">@lang('page_home.populer')</h5>
					</div>
					<div>
						<div class="grid grid-cols-2 gap-2 md:gap-4 lg:grid-cols-3">
						    @foreach($kategori as $category) @if($category->popular == "Yes")
							<a tabindex="0" href="{{ env('APP_URL').'/order/'.$category->kode }}" style="color:transparent;outline: none;">
                                <div class="bg-title-product flex items-center gap-x-1.5 rounded-xl bg-murky-600 p-1.5 duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 md:gap-x-3 md:rounded-2xl md:p-3 bg-murky-800">
                                    <img alt="{{ $category->nama }}" fetchpriority="high" width="56" height="56" decoding="async" data-nimg="1" class="aspect-square-1 h-14 w-14 rounded-lg !object-cover !object-center ring-1 ring-murky-600 md:h-20 md:w-20 md:rounded-xl" sizes="100vw" src="{{ $category->thumbnail }}" style="color: transparent;">
                                    <div class="relative flex w-full flex-col">
                                        <!--<h2 class="w-[100px] truncate text-xxs font-semibold text-murky-200 sm:w-[200px] md:w-[275px] md:text-base text-decoration-none">{{ $category->nama }}</h2>-->
                                        <h2 class="shadow-text w-[100px] text-xxs sm:w-[200px] md:w-[275px] md:text-base text-decoration-none">{{ $category->nama }}</h2>
                                        <p class="text-xxs text-murky-300 md:text-sm text-decoration-none">{{ $category->sub_nama }}</p>
                                    </div>
                                </div>
                            </a>
                            @endif @endforeach
						</div>
					</div>
				</div>
				@if($flashsale->count() > 0)
                <div class="containerFlashSale mt-3 mb-3">
                    <div class="headerFS">
                        <div class="containers">
                            <i class="fas fa-regular fa-bolt-lightning fa-beat-fade fa-lg text-danger"></i>
                            <h5><div class="title-head">@lang('page_home.flashsale')</div></h5>
                            
                               <div class="time" id="expired_time_flash_sale">
                                <div class="days"></div>
                                <div class="dots">:</div>
                                <div class="hours"></div>
                                <div class="dots">:</div>
                                <div class="minutes"></div>
                                <div class="dots">:</div>
                                <div class="seconds"></div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper swiperFlashSale  swiper-containerr swiper-initialized swiper-horizontal container swiper-backface-hidden  ">
                        <div class="swiper-wrapper" id="" aria-live="off"
                            style="">
                            @foreach($flashsale as $fs)
                            <?php 
                                $layanan = DB::table('layanans')->where('id', $fs->id_layanan)->first(); 
                                $kategoriss = DB::table('kategoris')->where('id', $fs->id_kategori)->first(); 
                                if(Auth::check()) {
                                    if(Auth::user()->role == "Member") {
                                        $harga = $layanan->harga_member;
                                    } else if(Auth::user()->role == "Reseller") {
                                        $harga = $layanan->harga_reseller;
                                    } else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin") {
                                        $harga = $layanan->harga_vip;
                                    }
                                } else {
                                    $harga = $layanan->harga;
                                }
                            ?>
                                <div class="swiper-slide flashSale cursor-pointer shadow" role="group" onclick="location.href='{{url('/order')}}/{{$fs->url}}?fs={{$fs->id_layanan}}'">
                                    <img src="{{$fs->thumbnail}}" alt="">
                                    <div class="mask"></div>
                                    <div class="desc bg-title-product">
                                        <div class="title-container">
                                            <div class="titleFs">{{$fs->nama}}</div>
                                            <div class="category">{{ $kategoriss->nama }}</div>
                                        </div>
                                        <div class="price">
                                            <div class="realPrice">
                                                <span class="rp">Rp.</span>{{ number_format($fs->harga_promo, 0, '.', ',') }},-
                                            </div>
                                            <div class="disc">Rp.{{ number_format($harga, 0, '.', ',') }},-</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
				<div class="row">
					<div class="col-md-9">
						<div class="category">
							<ul>
								@foreach($Tab as $data_tab)
								<li class="li-category tab-skeleton-content {{ in_array($data_tab->id,['1']) ? 'active' : '' }}" id="li-{{ $data_tab->code }}">
									<div class="product1 from-murky-700 bg-gradient-to-t" style="background-color: var(--warna_4);" onclick="load_home('{{ $data_tab->code }}');">
									{{ $data_tab->text }}</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
				<div class="">
					<section class="item-skeleton-content" style="">
					@foreach($Tab as $data_tab)
					<div class="row-games mb-3 {{ $data_tab->id != '1' ? 'd-none' : '' }}" id="category-{{ $data_tab->code }}">
						<h5 class="mb-3">{{ $data_tab->text }}</h5>
						<div class="grid grid-cols-3 gap-3 sm:grid-cols-3 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6" id="kategoriContainer">
                            @foreach($kategori as $d)
                            @if($d->tipe == $data_tab->code)
                            <a href="{{url('/order')}}/{{$d->kode}}" class="relative active:opacity-30 p-1 md:p-3 rounded-xl overflow-hidden featured-game-card" style="color:transparent;outline: none;background:var(--warna_4);box-shadow:0px 1px 5px rgba(31, 31, 31, 0.15)">
                                <div class="relative mb-2 w-full aspect-w-1 aspect-h-1 rounded-xl overflow-hidden">
                                    <span style="box-sizing:border-box;display:block;overflow:hidden;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;position:absolute;top:0;left:0;bottom:0;right:0">
                                        <img alt="{{ $d->nama  }}" sizes="180px" srcset="{{ $d->thumbnail  }}" src="{{ $d->thumbnail  }}" decoding="async" data-nimg="fill" class="object-cover hover:scale-125	transition-all" style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%">
                                    </span>
                                </div>
                                <h3 class="text-xs md:text-sm font-medium md:font-bold text-center truncate">{{ $d->nama  }}</h3>
                            </a>
                            @endif
                            @endforeach
                        </div>
                        <div class="text-center {{ $data_tab->code != 'sosmed' ? 'd-none' : '' }} mt-3">
    					    <button class="btn btn-primary" type="button" id="showAllButton">@lang('page_home.tampilkan_lainnya')</button>
    					</div>
					</div>
					@endforeach
					</section>
				</div>
			</div>
			<div class="other-area pt-4">
                <a href="/artikel/" class="float-end text-decoration-none">@lang('page_home.lihat_lainnya')</a>
                <h5 class="fw-600 mb-3">@lang('page_home.berita_terbaru')</h5>
                <div class="row">
                    @foreach($artikel as $datas)
                    @php
                    $excerpt_lengths = 20;
                    $explodes = explode(' ', strip_tags($datas->content));
                    $excerpts = implode(' ', array_slice($explodes, 0, $excerpt_lengths));
                    $ymdhis = explode(' ',$datas->created_at);
                    $month = [
                            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                            'Juli','Agustus','September','Oktober','November','Desember'
                        ];
                    $explode = explode('-', $ymdhis[0]);
                    $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                    @endphp
                    <div class="col-md-4 mb-3">
                        <a href="/artikel/{{ $datas->url }}" class="artikel-item">
                        <img src="{{ $datas->path }}" class="w-100 rounded d-block mb-3">
                            <span class="category mb-2">{{ $datas->category }}</span>
                            <h6 class="fw-600 lh-26">{{ $datas->title }}</h6>
                            <p class="mb-1 text-muted">{{ $excerpts }}...</p>
                            <div class="artikel-data">
                                <span class="author">{{ $datas->author }}</span>
                                <span class="date"><span class="d-inline-block ms-1 me-2">•</span>{{ $formatted.' ('.substr($ymdhis[1],0,5).' WIB)' }}</span>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
		</div>
	</div>
	@if(isset($popup))
	<div class="modal fade" id="modal-popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--warna_4);">
                <div class="modal-body text-white">
                    <img src="{{ $popup->path }}" class="img-fluid mb-3" style="display: block; margin: 0 auto;">
                    <div style="margin: 15px!important;">{!! $popup->deskripsi !!}</div>
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">@lang('page_home.tutup')</button>
                        <!--<button type="button" class="btn btn-primary" onclick="modal__hide();">Saya sudah membaca</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
	@endsection 
	@section('js')
	<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var showAllButton = document.getElementById('showAllButton');
            var kategoriContainer = document.getElementById('kategoriContainer');
            var kategoriCards = kategoriContainer.querySelectorAll('.featured-game-card');
            var start = 12; 
    
            for (var i = start; i < kategoriCards.length; i++) {
                kategoriCards[i].classList.add('hidden');
            }
    
            showAllButton.addEventListener('click', function() {
                for (var i = start; i < start + 12 && i < kategoriCards.length; i++) {
                    kategoriCards[i].classList.remove('hidden');
                }
                start += 12;
    
                if (start >= kategoriCards.length) {
                    showAllButton.style.display = 'none';
                }
            });
        });
    </script>
	<script>
	function load_home(category) {
		$(".li-category").removeClass('active');
		$("#li-" + category).addClass('active');

		if (category == 'game' || category == 'games') {
			$(".row-games").addClass('d-none');
			$("#category-" + category).removeClass('d-none');
		} else {
			$(".row-games").addClass('d-none');
			$("#category-" + category).removeClass('d-none');
		}
	}
    const mySwiper = new Swiper('.my-swiper', {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            speed: 400,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                modifier: 1,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
    
    
        });
    </script>
    <script>
        function updateTimer() {
            @foreach($flashsale as $fs)
                @php
                    $expiredFlashSale = new DateTime($fs->expired_flash_sale);
                    $formattedDate = $expiredFlashSale->format('Y-m-d H:i:s');
                @endphp
            
                var countDownDate = new Date("{{ $formattedDate }}").getTime();
            @endforeach

            function calculateTime() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance > 0) {
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.querySelector(".days").textContent = days;
                    document.querySelector(".hours").textContent = hours;
                    document.querySelector(".minutes").textContent = minutes;
                    document.querySelector(".seconds").textContent = seconds;
                } else {
                    document.getElementById("expired_time_flash_sale").textContent = "Waktu sudah habis!";
                }
            }

            calculateTime();
        }

        document.addEventListener("DOMContentLoaded", function () {
            updateTimer();
            setInterval(updateTimer, 1000); // Update timer every second
        });
    </script>
	<script>
    @if(isset($popup))
	var modal_popup = new bootstrap.Modal(document.getElementById('modal-popup'));
    modal_popup .show();
    @endif
	</script>
    @endsection