@extends('../../main')

@section("title", $title." -")

@section('css')
<style>
        .gap-3 {
            gap: 0.75rem;
        }
        .pm-carousel .splide__slide.is-active img{
            position:relative;
            transform:scale(1.05);
            z-index:5
        }
        .pm-carousel .splide__slide img{
            transform:scale(.9);
            transition:transform .7s ease-in-out
        }
        .promo.disabled {
	        background: #685c55;
	    }
	    .promo.active {
        }
        .promo.active:before  {
            display:inline-block;
            content: '✔';
            position: absolute;
            top: 0;
            right: 0;
            width: 27px;
            text-align: center;
            border: 0px solid #fff!important;
            background: #399813;
            /*border-radius: 6px 10px 1px 30px;*/
            border-bottom-left-radius: 9999px;
            border-top-right-radius: 3000px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,.3)
        }
	    .method.disabled {
	        background: #685c55;
	    }
	    .product.with-hemat {
	        padding: 10px 16px 34px 10px;
	        position: relative;
	    }
	    .product span.coret {
	        font-size: 12px;
            margin-left: 8px;
            font-weight: 500;
            color: #ff6161;
	    }
	    .product span.hemat {
	        background: var(--warna);
            display: inline-block;
            width: 100%;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 1px;
            font-size: 12px;
            text-align: center;
            border-radius: 0 0 18px 18px;
            color: #333;
            font-weight: 500;
	    }
	    .mystic-games-banner {
	        height: 320px;
	        background-image: url({{ $kategori->banner }});
	        background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
	    }
	    .mystic-games-banner-2 {
	        height: 140px;
            border-bottom: 1px;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgMTkyMCAxMDAwIj48c3R5bGUgdHlwZT0idGV4dC9jc3MiPnBhdGh7b3BhY2l0eTouMTtjbGlwLXBhdGg6dXJsKCNjbGlwUGF0aCk7ZmlsbDp1cmwoI2xpbmVhckdyYWRpZW50KTt9PC9zdHlsZT48Y2xpcFBhdGggaWQ9ImNsaXBQYXRoIj48cmVjdCB3aWR0aD0iMTkyMCIgaGVpZ2h0PSIxMDAwIi8+PC9jbGlwUGF0aD48bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhckdyYWRpZW50IiB4MT0iMCUiIHkxPSIwJSIgeDI9IjkwJSIgeTI9IjAlIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9ImhzbCgwIDAlIDEwMCUvMSkiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9ImhzbCgwIDAlIDEwMCUvMCkiLz48L2xpbmVhckdyYWRpZW50PjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE5MjAsMTAwMClzY2FsZSgtMSwtMSkiPjxwYXRoIGQ9Ik0xMzg0LjUgMzQzLjJMMTkyLjcgMTUzNWwtMjEzLjUtM0wxMzgzIDEyOC4ybDEuNSAyMTV6Ii8+PHBhdGggZD0iTTE5MTkuNyA0NDguM0wxMzU5IDEwMDlsLTEwMC40LTEuNEwxOTE5IDM0Ny4xbC43IDEwMS4yeiIvPjxwYXRoIGQ9Ik0xMTc2LjcgNTE0LjNMNjE2IDEwNzVsLTEwMC40LTEuNEwxMTc2IDQxMy4xbC43IDEwMS4yeiIvPjxwYXRoIGQ9Ik02NDQuNyA0NTcuM0w4NCAxMDE4bC0xMDAuNC0xLjRMNjQ0IDM1Ni4xbC43IDEwMS4yeiIvPjxwYXRoIGQ9Ik0xMzg3LjcgNDQ4LjNMODI3IDEwMDlsLTEwMC40LTEuNEwxMzg3IDM0Ny4xbC43IDEwMS4yeiIvPjxwYXRoIGQ9Ik0xMjUwLjEgNDkzLjhsLTU0NSA1NDUtNTIuNyA0My42IDY0MS45LTY0MS45LTQ0LjIgNTMuM3oiLz48cGF0aCBkPSJNODkxLjEgNjM5LjFMLTc3OCAyMzA4LjNsLTI5OC45LTQuMkw4ODkgMzM4LjFsMi4xIDMwMXoiLz48cGF0aCBkPSJNMTg3MC40IDQxOS44TC0yOC44IDIzMTlsLTM0MC4xLTQuOEwxODY4IDc3LjNsMi40IDM0Mi41eiIvPjxwYXRoIGQ9Ik05MDguNCA0MzYuOEwtOTkwLjggMjMzNmwtMzQwLjEtNC44TDkwNiA5NC4zbDIuNCAzNDIuNXoiLz48cGF0aCBkPSJNMTYzMi40IDUxNS44TC0yNjYuOCAyNDE1bC0zNDAuMS00LjhMMTYzMCAxNzMuM2wyLjQgMzQyLjV6Ii8+PHBhdGggZD0iTTExNzYuMyA1NjcuMUwtMTQ0NS42IDMxODlsLTQ2OS41LTYuNkwxMTczIDk0LjNsMy4zIDQ3Mi44eiIvPjxwYXRoIGQ9Ik0xNDI3LjMgNTgwLjFMLTExOTQuNiAzMjAybC00NjkuNS02LjZMMTQyNCAxMDcuM2wzLjMgNDcyLjh6Ii8+PHBhdGggZD0iTTE2NDkuNSA4ODAuMkw0NTcuNyAyMDcybC0yMTMuNS0zTDE2NDggNjY1LjJsMS41IDIxNXoiLz48cGF0aCBkPSJNNjc1LjggNTIyLjJsLTI2MjEuOSAyNjIxLjktNDY5LjQtNi42TDY3Mi41IDQ5LjRsMy4zIDQ3Mi44eiIvPjxwYXRoIGQ9Ik0yNTk1LjkgNTIyLjJMLTI2IDMxNDQuMWwtNDY5LjUtNi42TDI1OTIuNiA0OS40bDMuMyA0NzIuOHoiLz48L2c+PC9zdmc+);
            background-repeat: repeat-x;
            background-position: top;
            background-size: clamp(60em,100rem,100em) auto,cover;
            margin-bottom: 32px;
            border-radius: 0px 0px 20px 20px;
	    }
	    .mystic-games-banner-2 .games-img {
            perspective: 25em;
            position: absolute;
            margin-top: -116px;
        }
        .mystic-games-banner-2 .games-img img {
            width: 240px;
            height: 240px;
            position: relative;
            border-radius: 1rem;
            transform: rotateY(20deg) rotateX(-4deg)!important;
            transform-origin: left center;
        }
        .mystic-games-banner-2 .games-detail {
            padding-left: 241px;
            padding-top: 14px;
        }
        .mystic-games-banner-2 .games-detail h5 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 0px;
        }
        .mystic-games-banner-2 .games-detail p {
            font-size: 15px;
            margin-bottom: 8px;
        }
        .mystic-games-banner-2 .games-emblem {
            white-space: normal;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            flex-wrap: wrap;
        }
        .mystic-games-banner-2 .emblem-item {
            display: flex;
            margin-right: 12px;
        }
        .mystic-games-banner-2 .emblem-svg {
            display: inline-block;
            width: 22px;
            height: 22px;
            text-align: center;
            line-height: 20px;
            border-radius: 50%;
            margin-top: 2px;
            float: left;
            margin-right: 9px;
        }
        .mystic-games-banner-2 .emblem-svg span {
            font-weight: 600;
        }
        .mystic-games-banner-2 .emblem-svg svg {
            width: 14px;
        }
        .mystic-games-banner-2 .emblem-svg.bg-secondary {
            background: #5e666e !important;
        }
        .mystic-games-banner-2 .emblem-svg.bg-secondary svg {
            fill: #fde047;
        }
        
	    @media screen AND (max-width: 460px) {
	        .content-main {
	            padding-top: 0rem !important;
	        }
	        .mystic-games-banner {
	            height: 135px;
	        }
	        .product span.coret {
	            display: block;
	            margin-left: 0;
	        }
	        .mystic-games-banner-2 {
	            height: 180px;
	        }
	        .mystic-games-banner-2 .games-img {
    	        margin-top: -40px;
    	    }
    	    .mystic-games-banner-2 .games-img img {
                width: 140px;
                height: 140px;
    	    }
    	    .mystic-games-banner-2 .games-detail {
                padding-left: 134px;
                padding-top: 1px;
            }
            .mystic-games-banner-2 .games-detail h5 {
                font-size: 16px;
                margin-bottom: -2px;
	        }
	        .mystic-games-banner-2 .games-detail p {
                font-size: 13px;
                margin-bottom: 8px;
            }
	    }
	    @media screen AND (min-width: 625px) {
            .content-main {
                padding-top: 0px !important;
            }
        }
</style>

@endsection

@section('content')
    <div class="content-main">
        <div class="mystic-games-banner"></div>
	    <div class="mystic-games-banner-2 border-0 shadow-form bg-murky-800">
	        <div class="container">
	            <div class="games-img">
                    <img src="{{ $kategori->thumbnail  }}">
                </div>
                <div class="games-detail">
                    <h5>{{$kategori->nama}}</h5>
                    <p>{{$kategori->sub_nama}}</p>
                    <div class="games-emblem">
                        <div class="emblem-item">
                            <div class="emblem-svg bg-secondary">
                                <img alt="" loading="lazy" width="150" height="150" decoding="async" data-nimg="1" class="size-8" srcset="{{ url('') }}/assets/logo/icon/delivery-fast-svgrepo-com.svg" src="{{ url('') }}/assets/logo/icon/delivery-fast-svgrepo-com.svg" style="color: transparent;">
                            </div>
                            <small>{{$kategori->text_1}}</small>
                        </div>  
                        <div class="emblem-item">
                            <div class="emblem-svg bg-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-3.5 w-3.5 text-yellow-300"><path d="M10 1a6 6 0 00-3.815 10.631C7.237 12.5 8 13.443 8 14.456v.644a.75.75 0 00.572.729 6.016 6.016 0 002.856 0A.75.75 0 0012 15.1v-.644c0-1.013.762-1.957 1.815-2.825A6 6 0 0010 1zM8.863 17.414a.75.75 0 00-.226 1.483 9.066 9.066 0 002.726 0 .75.75 0 00-.226-1.483 7.553 7.553 0 01-2.274 0z"></path></svg>
                            </div>
                            <small>{{$kategori->text_2}}</small>
                        </div>
                        <div class="emblem-item">
                            <div class="emblem-svg bg-secondary">
                                <img alt="" loading="lazy" width="150" height="150" decoding="async" data-nimg="1" class="size-8" srcset="{{ url('') }}/assets/logo/icon/shield-checkmark-svgrepo-com.svg" src="{{ url('') }}/assets/logo/icon/shield-checkmark-svgrepo-com.svg" style="color: transparent;">
                            </div>
                            <small>{{$kategori->text_3}}</small>
                        </div> 
                        <div class="emblem-item">
                            <div class="emblem-svg bg-secondary">
                                <img alt="" loading="lazy" width="150" height="150" decoding="async" data-nimg="1" class="size-8" srcset="{{ url('') }}/assets/logo/icon/secure-payment-svgrepo-com.svg" src="{{ url('') }}/assets/logo/icon/secure-payment-svgrepo-com.svg" style="color: transparent;">
                            </div>
                            <small>{{$kategori->text_4}}</small>
                        </div> 
                        <div class="emblem-item">
                            <div class="emblem-svg bg-secondary">
                                <img alt="" loading="lazy" width="150" height="150" decoding="async" data-nimg="1" class="size-8" srcset="{{ url('') }}/assets/logo/icon/customer-service-svgrepo-com.svg" src="{{ url('') }}/assets/logo/icon/customer-service-svgrepo-com.svg" style="color: transparent;">
                            </div>
                            <small>{{$kategori->text_5}}</small>
                        </div> 
                    </div>
                </div>
	        </div>
	    </div>
		<div class="container grid grid-cols-3 gap-8">
		    <div class="col-span-3 md:col-span-1">
		        <div class="sticky top-24 flex flex-col space-y-8">
		            <!--<div class="rounded-xl bg-title-product shadow-2xl">
                        <div class="prose prose-sm px-4 py-2 pb-8 text-xs text-white sm:px-6">
                            <div>
                                 {!! htmlspecialchars_decode($kategori->deskripsi_game) !!}
                            </div>
                        </div>
                        <div class="col px-3 mb-4" style="display: flex; align-items: center;">
                            <img src="/assets/images/success.gif" class="gradient-corona-img img-x" alt="" width="50">
                            <marquee width="100%" direction="left" style="flex-grow: 1;">
                                {!! $kategori->margin_text !!}
                            </marquee>
                        </div>
                    </div>-->
                    <button
                        class="flex w-full justify-between rounded-lg px-4 py-2 text-left text-sm font-medium text-white duration-200 ease-in-out  focus:outline-none bg-title-product"
                         id="toggleButton"
                        type="button"
                        aria-expanded="true"
                        data-headlessui-state="open"
                        aria-controls="headlessui-disclosure-panel-:r6r:"
                    >
                        <span>Deskripsi dan cara melakukan transaksi</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="rotate-180 transform h-5 w-5 text-white">
                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    
                    <div id="dropdownContent" class="pt-1 text-sm text-murky-200 hidden">
                        <div class="rounded-lg bg-title-product">
                            <div class="prose prose-sm px-4 py-2 pb-8 text-xs text-white sm:px-6">
                                {!! htmlspecialchars_decode($kategori->deskripsi_game) !!}
                            </div>
                        </div>
                    </div>
        			<div class="rounded-xl bg-title-product shadow-2xl d-none d-sm-block">
        			    <div class="flex border-b border-murky-600">
                            <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-murky-700-linear to-primary-600 px-4 py-2 text-xl font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                    <path
                                        fill-rule="evenodd"
                                        d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b to-murky-800 px-2 text-base font-semibold leading-6 text-white sm:px-4" style="padding-top: .9rem !important;padding-bottom: .5rem !important;">Testimoni</h3>
                        </div>
        			    @if(count($rating) == 0)
        			    <div class="px-2 py-2">
            			    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                            <strong>Information!</strong> Produk belum ada penilaian.</div>
                        </div>
    				    @else
    				    @php
                        $result = DB::table('ratings')->where('kategori_id',$kategori->id)->get();
                        $average_rating = 0;
                        $total_review = 0;
                        $five_star_review = 0;
                        $four_star_review = 0;
                        $three_star_review = 0;
                        $two_star_review = 0;
                        $one_star_review = 0;
                        $total_user_rating = 0;
                        foreach($result as $row) {
                            if($row->bintang == '5') {
                            	$five_star_review++;
                            }
                            if($row->bintang == '4') {
                            	$four_star_review++;
                            }
                            if($row->bintang == '3') {
                            	$three_star_review++;
                            }
                            if($row->bintang == '2') {
                            	$two_star_review++;
                            }
                            if($row->bintang == '1') {
                                $one_star_review++;
                            }
                            $total_review++;
                            $total_user_rating = $total_user_rating + $row->bintang;
                        }
                        $width_five_star = $five_star_review / $total_review * 100;
                        $width_four_star = $four_star_review / $total_review * 100;
                        $width_three_star = $three_star_review / $total_review * 100;
                        $width_two_star = $two_star_review / $total_review * 100;
                        $width_one_star = $one_star_review / $total_review * 100;
                        $average_rating = $total_user_rating / $total_review;
                        @endphp
                        <div class="flex flex-col space-y-8">
            	            <div class="">
            					<div class="">
            					    <div class="">
            					        <div class="flow-root p-6">
                                            <div class="-my-6">
                                                <div>
                                                    <h2 class="sr-only">Testimoni Pembeli</h2>
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="mt-1 flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-10 w-10 flex-shrink-0 text-yellow-400">
                                                                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <div>
                                                                <span class="text-5xl font-semibold text-text-color">{{number_format($average_rating, 1)}}</span>
                                                                <sub class="text-lg font-semibold text-text-color">/5.0</sub>
                                                            </div>
                                                        </div>
                                                        <p class="pt-2 text-center text-sm">Pelanggan merasa puas dengan produk ini. <br> Dari <span class="font-semibold">{{$total_review}}</span> ulasan.</p>
                                                    </div>
                                                    <div class="mt-6">
                                                        <h3 class="sr-only">Review data</h3>
                                                        <dl class="space-y-3">
                                                            <div class="flex items-center text-sm">
                                                                <dt class="flex flex-1 items-center">
                                                                    <p class="w-3 font-medium text-text-color">5<span class="sr-only"> star reviews</span></p>
                                                                    <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        <div class="relative ml-3 flex-1">
                                                                            <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                            @if($width_five_star != 0)
                                                                            <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_five_star}}%;"></div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </dt>
                                                                <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$five_star_review}}<span class="sr-only"> reviews</span></dd>
                                                            </div>
                                                            <div class="flex items-center text-sm">
                                                                <dt class="flex flex-1 items-center">
                                                                    <p class="w-3 font-medium text-text-color">4<span class="sr-only"> star reviews</span></p>
                                                                    <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        <div class="relative ml-3 flex-1">
                                                                            <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                            @if($width_four_star != 0)
                                                                            <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_four_star}}%;"></div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </dt>
                                                                <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$four_star_review}}<span class="sr-only"> reviews</span></dd>
                                                            </div>
                                                            <div class="flex items-center text-sm">
                                                                <dt class="flex flex-1 items-center">
                                                                    <p class="w-3 font-medium text-text-color">3<span class="sr-only"> star reviews</span></p>
                                                                    <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        <div class="relative ml-3 flex-1">
                                                                            <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                            @if($width_three_star != 0)
                                                                            <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_three_star}}%;"></div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </dt>
                                                                <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$three_star_review}}<span class="sr-only"> reviews</span></dd>
                                                            </div>
                                                            <div class="flex items-center text-sm">
                                                                <dt class="flex flex-1 items-center">
                                                                    <p class="w-3 font-medium text-text-color">2<span class="sr-only"> star reviews</span></p>
                                                                    <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        <div class="relative ml-3 flex-1">
                                                                            <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                            @if($width_two_star != 0)
                                                                            <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_two_star}}%;"></div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </dt>
                                                                <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$two_star_review}}<span class="sr-only"> reviews</span></dd>
                                                            </div>
                                                            <div class="flex items-center text-sm">
                                                                <dt class="flex flex-1 items-center">
                                                                    <p class="w-3 font-medium text-text-color">1<span class="sr-only"> star reviews</span></p>
                                                                    <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        <div class="relative ml-3 flex-1">
                                                                            <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                            @if($width_one_star != 0)
                                                                            <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_one_star}}%;"></div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </dt>
                                                                <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$one_star_review}}<span class="sr-only"> reviews</span></dd>
                                                            </div>
                                                        </dl>
                                                    </div>
                                                    <div class="mt-6">
                                                        <h3 class="text-lg font-medium text-text-color">Bagikan pengalamanmu!</h3>
                                                        <p class="mt-1 text-sm text-text-color/75">Apakah kamu menyukai produk ini? Beri tahu kami dan calon pembeli lainnya tentang pengalamanmu.</p>
                                                    </div>
                                                </div><hr>
                					        @foreach($rating as $ratings)
                					        <div class="py-0">
                                                <div class="flex items-center">
                                                    <div class="w-full">
                                                        <div class="flex items-start justify-between">
                                                            @php
                                                                $ymdhis = explode(' ',$ratings->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                            @endphp
                                                            <div><h4 class="mt-0.5 text-xs font-bold text-white">62{{substr($ratings->no_pembeli,0,-15).'********'.substr($ratings->no_pembeli, -2)}}</h4></div>
                                                            <div class="flex items-center">
                                                                @for($i=1; $i<=5; $i++) 
                                                                    @if($i <= $ratings->bintang)
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-4 w-4 flex-shrink-0">
                                                                            <path
                                                                                fill-rule="evenodd"
                                                                                d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                                                                clip-rule="evenodd"
                                                                            ></path>
                                                                        </svg>
                                                                    @else
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white" aria-hidden="true" class="text-yellow-400 h-4 w-4 flex-shrink-0">
                                                                            <path
                                                                                fill-rule="evenodd"
                                                                                d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                                                                clip-rule="evenodd"
                                                                            ></path>
                                                                        </svg>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <p class="sr-only">5 dari 5 bintang</p>
                                                    </div>
                                                </div>
                                                <div class="flex w-full justify-between text-xxs"><span>{{ $ratings->layanan }}</span><span>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</span></div>
                                                <div class="text-murky-20 mt-1 space-y-6 text-xs italic">“{{ $ratings->comment }}”</div>
                                            </div><hr>
                					        @endforeach
            					            </div>
            					        </div>
            					        <div class="px-4 pb-4 sm:px-6 sm:pb-6">
                                            <a class="inline-flex items-center gap-x-2 text-sm" href="/reviews" style="color:white;outline: none;">
                                                <span>Lihat Semua</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                                        clip-rule="evenodd"
                                                    ></path>
                                                </svg>
                                            </a>
                                        </div>
            					    </div>
            					</div>
            				</div>
                        </div>
                        @endif
        			</div>
		        </div>
		    </div>
		    <ul class="col-span-3 flex flex-col space-y-8 md:col-span-2">
		        <div class="rounded-xl bg-title-product shadow-2xl" id="section-item">
		            <form action="" method="POST" id="form-order">
		            <input type="hidden" name="product" id="product">
					<input type="hidden" name="method" id="method">
					<input type="hidden" name="voc" id="voc">
					<input type="hidden" id="ktg_tipe" value="{{$kategori->tipe}}">
					<input type="hidden" name="ip" id="ip" value="{{$formatter->client_ip()}}">
		            <div class="flex border-b border-murky-600 mb-3">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-murky-700-linear px-4 py-2 text-xl font-semibold">1</div>
                        <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b to-murky-800 px-2 text-base font-semibold leading-6 text-white sm:px-4" style="padding-top: .9rem !important;padding-bottom: .5rem !important;">Pilih Nominal yang Ingin Anda Beli </h3>
                    </div>
        			<div class="product-category px-3">
						<div class="product-category-item from-murky-700 bg-gradient-to-t active" id="category-all" onclick="show_category('all');">
							<img src="{{ !$config ? '' : $config->logo_footer }}" style="height:auto;width:50px;">
							<span>Semua</span>
						</div>
						@foreach($pakets as $paket)
						<div class="product-category-item from-murky-700 bg-gradient-to-t" id="category-{{ $paket['id'] }}" onclick="show_category('{{ $paket['id'] }}');">
							<img src="{!! $paket['image'] !!}" style="height:auto;width:50px;">
							<span>{{ $paket['nama'] }}</span>
						</div>
						@endforeach
					</div>
					@if(count($nominal) == 0 || count($paket_layanans) == 0)
        			<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <strong>Information!</strong> Produk sedang tidak tersedia.</div>
        			@else
        			@foreach($pakets as $paket)
    				<div class="row row-category px-3 py-3" id="row-{{ $paket['id'] }}">
    					<h6 class="card-title">{{ $paket['nama'] }}</h6>
        				<div class="grid grid-cols-2 gap-3 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 col-product" role="none">
                            @foreach(collect($paket['layanan'])->sortBy('harga') as $nom)
                            <div class="bg-product product-list relative showdescservice from-murky-700 bg-gradient-to-t flex cursor-pointer rounded-4.5 border-murky border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-2 justify-between
                                    {{ Request::get('fs') == $nom['id'] ? 'active' : '' }}"
                                    id="product-{{$nom['id']}}" 
                                    product-id="{{$nom['id']}}" onclick="select_product('{{$nom['id']}}');">
                                <span class="flex flex-1">
                                    <span class="flex flex-col justify-between">
                                        <h10><span class="trunc block text-xs font-semibold text-murky-800" id="namalayanan">{{$nom['layanan']}}</span></h10>
                                        <div class="flex justify-between">
                                            @if($nom['is_flash_sale'] == "Yes" && $nom['expired_flash_sale'] >= date('Y-m-d H:i:s'))
                                            <span class="flex items-center text-xxs font-semibold text-murky-600" style="text-decoration: line-through; color: red;">Rp&nbsp;{{ number_format($nom['harga']) }}</span>
                                            <span class="flex items-center text-xxs font-semibold text-murky-600 harga">Rp&nbsp;{{ number_format($nom['harga_flash_sale']) }}</span>
                                            @else
                                            <span class="flex items-center text-xxs font-semibold italic text-murky-600 decoration-murky-500 harga">Rp&nbsp;{{ number_format($nom['harga']) }}</span>
                                            @endif
                                        </div>
                                    </span>
                                </span>
                                @if($nom['product_logo'])
                                <div class="flex aspect-square w-8 items-center">
                                    <img alt="{{ $nom['layanan'] }}" fetchpriority="high" width="300" height="300" class="max-image object-right object-contain" sizes="80vh" src="{{ $nom['product_logo'] }}" style="color: transparent;">
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
    				</div>
					@endforeach
        			@endif
		        </div>
		        <div class="rounded-xl bg-title-product shadow-2xl" id="section-order">
		            <div class="flex border-b border-murky-600">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-murky-700-linear  px-4 py-2 text-xl font-semibold">2</div>
                        <div class="card-header flex align-items-center">
                            <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b to-murky-800 px-2 text-base font-semibold leading-6 text-white sm:px-4" style="padding-top: .9rem !important;padding-bottom: .5rem !important;">Masukkan Data Akun Anda </h3>
                            <i class="fa fa-question-circle cursor-pointer ms-1" data-bs-toggle="modal" data-bs-target="<?php echo $kategori->petunjuk == null ? "#" :"#petunjukModal" ?>"></i>
                        </div>
                    </div>
					<div class="grid gap-4 p-4 sm:px-6 sm:pb-4">
                    @foreach ($kategori->kategori_input as $item)
                        @if($item->server_id == 'seo')
                        <div>
                            <label for="id" class="block text-xs font-medium text-white pb-2">Target</label>
                            <div class="flex flex-col items-start">
                                <input type="text" class="form-control form-control-games games-input" id="user_id" name="target[]" placeholder="Target" autocomplete="off" >
                            </div>
                        </div>
                        <div>
                            <label for="id" class="block text-xs font-medium text-white pb-2">Keywords</label>
                            <div class="flex flex-col items-start">
                                <textarea class="form-control form-control-games games-input" id="keywords"></textarea>
                            </div>
                        </div>
                        <div>
                            <label for="qty" class="block text-xs font-medium text-white pb-2">Jumlah/Quantity <span class="badge bg-info" id="infoMin">Min: 0</span>|<span class="badge bg-info" id="infoMax">Max: 0</span></label>
                            <div class="flex flex-col items-start">
                                <input type="number" name="qty" id="qty" class="form-control" value="1" required min="1" {{in_array($item->server_id,['custom_comments','comment_replies']) ? 'readonly' : ''}}>
                            </div>
                        </div>
                        @elseif($item->server_id == 'subscription')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="id" class="block text-xs font-medium text-white pb-2">Username</label>
                                <div class="flex flex-col items-start">
                                    <input type="text" class="form-control form-control-games games-input" id="user_id" name="target[]" placeholder="Username" autocomplete="off" >
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="post" class="block text-xs font-medium text-white pb-2">Post [OPTIONAL]</label>
                                <div class="flex flex-col items-start">
                                    <input type="text" class="form-control form-control-games games-input" id="post" placeholder="Post [OPTIONAL]" autocomplete="off" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="minimal" class="block text-xs font-medium text-white pb-2">Minimal</label>
                                <div class="flex flex-col items-start">
                                    <input type="text" class="form-control form-control-games games-input" id="minimal" placeholder="Minimal" autocomplete="off" >
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="maximal" class="block text-xs font-medium text-white pb-2">Maximal</label>
                                <div class="flex flex-col items-start">
                                    <input type="text" class="form-control form-control-games games-input" id="maximal" placeholder="maximal" autocomplete="off" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="old_post" class="block text-xs font-medium text-white pb-2">Old Post [OPTIONAL]</label>
                                <div class="flex flex-col items-start">
                                    <input type="text" class="form-control form-control-games games-input" id="old_post" placeholder="Old Post [OPTIONAL]" autocomplete="off" >
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="delay" class="block text-xs font-medium text-white pb-2">Delay</label>
                                <div class="flex flex-col items-start">
                                    <input type="number" class="form-control form-control-games games-input" id="delay" placeholder="Delay" autocomplete="off" >
                                </div>
                                <small class="px-2 py-2">Delay in minutes. Possible values: 0, 5, 10, 15, 30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 360, 420, 480, 540, 600</small>
                            </div>
                        </div>
                        <div>
                            <label for="expiry" class="block text-xs font-medium text-white pb-2">Expired [OPTIONAL]</label>
                            <div class="flex flex-col items-start">
                                <input type="date" class="form-control form-control-games games-input" id="expiry" placeholder="Expired [OPTIONAL]" autocomplete="off" >
                            </div>
                            <small class="px-2 py-2">Expiry date. Format d/m/Y</small>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="id" class="block text-xs font-medium text-white pb-2">{{$item->name}}</label>
                                <div class="flex flex-col items-start">
                                    <input type="text" class="form-control form-control-games games-input" id="{{$item->value}}" name="target[]" placeholder="{{$item->name}}" autocomplete="off" >
                                </div>
                            </div>
                            @if(in_array($item->server_id,['user_id','mentions_hashtag','mentions_user_followers','mentions_media_likers','poll','custom_comments','comment_replies']))
                            <div class="col-md-12 mb-3">
                                <label for="qty" class="block text-xs font-medium text-white pb-2">Jumlah/Quantity <span class="badge bg-info" id="infoMin">Min: 0</span>|<span class="badge bg-info" id="infoMax">Max: 0</span></label>
                                <div class="flex flex-col items-start">
                                    <input type="number" name="qty" id="qty" class="form-control" value="1" required min="1" {{in_array($item->server_id,['custom_comments','comment_replies']) ? 'readonly' : ''}}>
                                </div>
                            </div>
                            @endif
                            @if(in_array($item->server_id,['comment_replies','mentions_user_followers']))
                                <div class="col-md-12 mb-3">
                                    <label for="username" class="block text-xs font-medium text-white pb-2">Username</label>
                                    <div class="flex flex-col items-start">
                                        <input type="text" class="form-control form-control-games games-input" id="usernames" placeholder="Username" autocomplete="off" >
                                    </div>
                                </div>
                            @endif
                            @if(in_array($item->server_id,['mentions_hashtag']))
                                <div class="col-md-12 mb-3">
                                    <label for="Hashtag" class="block text-xs font-medium text-white pb-2">Hashtag</label>
                                    <div class="flex flex-col items-start">
                                        <input type="text" class="form-control form-control-games games-input" id="hashtag" placeholder="Hashtag untuk mengambil nama pengguna." autocomplete="off" >
                                    </div>
                                </div>
                            @endif
                            @if(in_array($item->server_id,['mentions_media_likers']))
                                <div class="col-md-12 mb-3">
                                    <label for="Media" class="block text-xs font-medium text-white pb-2">Media</label>
                                    <div class="flex flex-col items-start">
                                        <input type="text" class="form-control form-control-games games-input" id="media" placeholder="Link target untuk mengambil daftar likers." autocomplete="off" >
                                    </div>
                                </div>
                            @endif
                            @if(in_array($item->server_id,['poll']))
                                <div class="col-md-12 mb-3">
                                    <label for="Answer Number" class="block text-xs font-medium text-white pb-2">Answer Number</label>
                                    <div class="flex flex-col items-start">
                                        <input type="text" class="form-control form-control-games games-input" id="answer_number" placeholder="Nomor jawaban polling." autocomplete="off" >
                                    </div>
                                </div>
                            @endif
                            @if(in_array($item->server_id,['custom_comments','comment_replies']))
                                <div class="col-md-12 mb-3">
                                    <label for="custom_comments" class="block text-xs font-medium text-white pb-2">Custom Comments (1 per row)</label>
                                    <div class="flex flex-col items-start">
                                        <textarea class="form-control form-control-games games-input" id="custom_comments" rows="5"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                        
                        
                        
                        
                    @endforeach
                    </div>
                    <p class="text-xxs italic" style="margin:1rem;">{!! $kategori->deskripsi_field !!}</p>
		        </div>
		        <div class="rounded-xl bg-title-product shadow-2xl" id="section-method">
		            <div class="flex border-b border-murky-600 mb-2">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-murky-700-linear px-4 py-2 text-xl font-semibold">3</div>
                        <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b to-murky-800 px-2 text-base font-semibold leading-6 text-white sm:px-4" style="padding-top: .9rem !important;padding-bottom: .5rem !important;">Pilih Metode Pembayaran </h3>
                    </div>
                    <div class="px-3 py-3">
                        <div class="row">
                        	<!--<p class="" style="font-size: 13px;color: white;">*Optional: Jika tidak mempunyai kode promo abaikan saja.</p>-->
                        	<div class="col-md-6">
    							<input type="text" class="form-control" id="voucher" name="voucher" placeholder="Ketik Kode Promo (Opsional)">
    						</div>
    						<div class="col-md-6">
    							<button id="btn-check" class="col-12 btn btn-primary btn-sm btn-block mb-2 mt-2 waves-effect" type="button">Gunakan Kode Promo</button>
    						</div>
    						<div class="text-green-500" style="font-size: .85rem;line-height: 1rem;" id="text-voucher"></div>
                            <div class="text-xs">
                                <button class="relative flex w-full items-center space-x-2 rounded-md border border-murky-500 bg-murky-600 py-2 pr-4 pl-3" type="button" id="modalPromo">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve" class="storke-2 h-5 w-5 text-primary-500">
                                        <defs></defs>
                                        <g style="stroke:none;stroke-width:0;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:none;fill-rule:nonzero;opacity:1" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                            <path d="M 38.666 57.455 c -2.708 0 -5.301 -1.55 -6.496 -4.171 c -1.631 -3.576 -0.049 -7.813 3.527 -9.445 c 1.733 -0.791 3.67 -0.86 5.454 -0.192 c 1.784 0.666 3.202 1.988 3.992 3.72 c 0.79 1.732 0.858 3.669 0.192 5.453 s -1.988 3.202 -3.72 3.992 C 40.658 57.248 39.654 57.454 38.666 57.455 z M 38.653 47.194 c -0.442 0 -0.883 0.095 -1.297 0.284 c -1.57 0.716 -2.264 2.576 -1.548 4.146 c 0.347 0.761 0.969 1.341 1.752 1.633 c 0.783 0.291 1.633 0.262 2.393 -0.084 c 0.761 -0.348 1.341 -0.97 1.633 -1.753 c 0.292 -0.783 0.263 -1.633 -0.084 -2.393 c 0 0 0 0 0 0 c -0.347 -0.761 -0.969 -1.341 -1.752 -1.634 C 39.393 47.261 39.023 47.194 38.653 47.194 z" style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:currentColor;fill-rule:nonzero;opacity:1" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                                            <path d="M 46.257 72.49 c -0.204 0 -0.412 -0.032 -0.617 -0.099 c -1.051 -0.341 -1.626 -1.469 -1.286 -2.519 L 55.49 35.524 c 0.341 -1.05 1.467 -1.626 2.519 -1.286 c 1.051 0.34 1.626 1.468 1.285 2.519 L 48.159 71.106 C 47.885 71.952 47.1 72.49 46.257 72.49 z" style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:currentColor;fill-rule:nonzero;opacity:1" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                                            <path d="M 64.324 63.742 c -2.709 0 -5.302 -1.55 -6.496 -4.17 c -0.791 -1.732 -0.859 -3.669 -0.193 -5.453 c 0.667 -1.784 1.988 -3.202 3.72 -3.992 c 3.575 -1.634 7.813 -0.051 9.444 3.527 l 0 0 c 1.632 3.577 0.049 7.813 -3.527 9.444 C 66.316 63.536 65.313 63.742 64.324 63.742 z M 64.312 53.484 c -0.442 0 -0.883 0.095 -1.297 0.283 c -0.761 0.348 -1.341 0.97 -1.633 1.753 c -0.293 0.783 -0.263 1.633 0.084 2.393 c 0.716 1.57 2.575 2.266 4.146 1.549 c 1.57 -0.717 2.264 -2.576 1.549 -4.146 c -0.348 -0.761 -0.97 -1.341 -1.753 -1.633 C 65.052 53.55 64.681 53.484 64.312 53.484 z" style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:currentColor;fill-rule:nonzero;opacity:1" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                                            <path d="M 43.584 89.999 c -1.125 0 -2.252 -0.201 -3.338 -0.606 c -2.384 -0.891 -4.278 -2.656 -5.334 -4.971 L 14.466 39.6 c -1.759 -3.858 -1.873 -8.331 -0.313 -12.273 l 8.432 -21.3 c 1.891 -4.777 7.171 -7.185 12.016 -5.481 l 21.612 7.595 c 4 1.405 7.302 4.424 9.063 8.282 l 20.445 44.821 c 1.057 2.315 1.147 4.904 0.258 7.287 c -0.891 2.384 -2.656 4.278 -4.971 5.334 l -33.474 15.27 C 46.272 89.71 44.93 89.999 43.584 89.999 z M 31.429 4.001 c -2.205 0 -4.268 1.327 -5.126 3.497 l -8.432 21.3 c -1.162 2.936 -1.077 6.268 0.234 9.141 l 20.446 44.822 c 0.612 1.344 1.711 2.367 3.094 2.884 c 1.382 0.517 2.883 0.465 4.227 -0.148 l 33.474 -15.27 l 0 0 c 1.344 -0.613 2.367 -1.712 2.884 -3.094 c 0.517 -1.383 0.464 -2.884 -0.148 -4.228 L 61.637 18.083 c -1.311 -2.874 -3.772 -5.122 -6.75 -6.169 L 33.275 4.318 C 32.664 4.104 32.041 4.001 31.429 4.001 z" style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:currentColor;fill-rule:nonzero;opacity:1" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                                            <circle cx="33.546" cy="14.146" r="3.636" style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:currentColor;fill-rule:nonzero;opacity:1" transform="  matrix(1 0 0 1 0 0) "></circle>
                                            <path d="M 29.18 87.247 H 12.941 c -5.251 0 -9.524 -4.273 -9.524 -9.525 V 28.475 c 0 -4.238 1.752 -8.355 4.807 -11.294 l 6.496 -6.247 c 0.796 -0.767 2.063 -0.74 2.827 0.055 c 0.766 0.796 0.741 2.062 -0.055 2.827 l -6.495 6.247 c -2.275 2.189 -3.58 5.254 -3.58 8.411 v 49.248 c 0 3.047 2.478 5.525 5.524 5.525 H 29.18 c 1.104 0 2 0.895 2 2 C 31.179 86.352 30.284 87.247 29.18 87.247 z" style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;fill:currentColor;fill-rule:nonzero;opacity:1" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                                        </g>
                                    </svg>
                                    <span>Pakai Promo Yang Tersedia</span>
                                    <span class="absolute right-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5">
                                            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark mt-2" style="font-size: 11px !important">100% Secured Payment Gate <b>{{ !$config ? '' : $config->judul_web }} <span class="mdi mdi-shield-check"></span></b></span>
                        <div class="accordion mb-2 mt-3" id="accordionExample">
							@if(count(DB::table('methods')->where('tipe','bank-transfer')->get()) != 0)
							<div class="border-0 mb-4">
								<h2 class="accordion-header" id="heading-1">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false" aria-controls="collapse-1">Bank Transfer (Dicek Manual)</button>
								</h2>
								<div id="collapse-1" class="accordion-collapse collapse" aria-labelledby="heading-1" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
								            @foreach($pay_method as $p)
                                            @if($p->tipe == 'bank-transfer')
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													    <img src="{{$p->images}}" alt="{{$p->name}}" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													           <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{$p->name}}</span>
    													        </div>
    													    </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
											@endif
                                            @endforeach
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        @foreach($pay_method as $p)
                                    @if($p->tipe == 'bank-transfer')
							        <img src="{{$p->images}}" alt="" class="me-2" style="height:16px;width:auto;">
							        @endif
                                    @endforeach
							    </div>
							</div>
							@endif
							@if(count(DB::table('methods')->where('tipe','qris')->get()) != 0)
							<div class="border-0 mb-4">
								<h2 class="accordion-header" id="heading-2">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">QRIS (Dicek Otomatis)</button>
								</h2>
								<div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="heading-2" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
								            @foreach($pay_method as $p)
                                            @if($p->tipe == 'qris')
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													    <img src="{{$p->images}}" alt="{{$p->name}}" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													           <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{$p->name}}</span>
    													        </div>
    													    </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
											@endif
                                            @endforeach
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        @foreach($pay_method as $p)
                                    @if($p->tipe == 'qris')
							        <img src="{{$p->images}}" alt="" class="me-2" style="height:16px;width:auto;">
							        @endif
                                    @endforeach
							    </div>
							</div>
							@endif
							@if(count(DB::table('methods')->where('tipe','e-walet')->get()) != 0)
							<div class="border-0 mb-4">
								<h2 class="accordion-header" id="heading-3">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">E-Wallet (Dicek Otomatis)</button>
								</h2>
								<div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="heading-3" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
								            @foreach($pay_method as $p)
                                            @if($p->tipe == 'e-walet')
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													    <img src="{{$p->images}}" alt="{{$p->name}}" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													           <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{$p->name}}</span>
    													        </div>
    													    </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
											@endif
                                            @endforeach
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        @foreach($pay_method as $p)
                                    @if($p->tipe == 'e-walet')
							        <img src="{{$p->images}}" alt="" class="me-2" style="height:16px;width:auto;">
							        @endif
                                    @endforeach
							    </div>
							</div>
							@endif
							@if(count(DB::table('methods')->where('tipe','virtual-account')->get()) != 0)
							<div class="border-0 mb-4">
								<h2 class="accordion-header" id="heading-4">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">Virtual Account (Dicek Otomatis)</button>
								</h2>
								<div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="heading-4" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
								            @foreach($pay_method as $p)
                                            @if($p->tipe == 'virtual-account')
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													    <img src="{{$p->images}}" alt="{{$p->name}}" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													           <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{$p->name}}</span>
    													        </div>
    													    </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
											@endif
                                            @endforeach
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        @foreach($pay_method as $p)
                                    @if($p->tipe == 'virtual-account')
							        <img src="{{$p->images}}" alt="" class="me-2" style="height:16px;width:auto;">
							        @endif
                                    @endforeach
							    </div>
							</div>
							@endif
							@if(count(DB::table('methods')->where('tipe','convenience-store')->get()) != 0)
							<div class="border-0 mb-4">
								<h2 class="accordion-header" id="heading-5">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">Retail Outlets (Dicek Otomatis)</button>
								</h2>
								<div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="heading-5" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
								            @foreach($pay_method as $p)
                                            @if($p->tipe == 'convenience-store')
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													    <img src="{{$p->images}}" alt="{{$p->name}}" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													           <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{$p->name}}</span>
    													        </div>
    													    </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
											@endif
                                            @endforeach
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        @foreach($pay_method as $p)
                                    @if($p->tipe == 'convenience-store')
							        <img src="{{$p->images}}" alt="" class="me-2" style="height:16px;width:auto;">
							        @endif
                                    @endforeach
							    </div>
							</div>
							@endif
							@if(count(DB::table('methods')->where('tipe','pulsa')->get()) != 0)
							<div class="border-0 mb-4">
								<h2 class="accordion-header" id="heading-6">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-6" aria-expanded="false" aria-controls="collapse-6">Pulsa (Dicek Otomatis)</button>
								</h2>
								<div id="collapse-6" class="accordion-collapse collapse" aria-labelledby="heading-6" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
								            @foreach($pay_method as $p)
                                            @if($p->tipe == 'pulsa')
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-{{$p->id}}" onclick="select_method('{{$p->id}}');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													    <img src="{{$p->images}}" alt="{{$p->name}}" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													           <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{$p->name}}</span>
    													        </div>
    													    </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
											@endif
                                            @endforeach
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        @foreach($pay_method as $p)
                                    @if($p->tipe == 'pulsa')
							        <img src="{{$p->images}}" alt="" class="me-2" style="height:16px;width:auto;">
							        @endif
                                    @endforeach
							    </div>
							</div>
							@endif
							@if(Auth::check())
							<div class="border-0">
								<h2 class="accordion-header" id="heading-7">
								    <button style="background: var(--warna_5);border-radius: 6px 6px 0 0;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-7" aria-expanded="false" aria-controls="collapse-7">Saldo Akun (Member)</button>
								</h2>
								<div id="collapse-7" class="accordion-collapse collapse" aria-labelledby="heading-7" data-bs-parent="#accordionExample">
								    <div class="accordion-body" style="background: var(--warna_4);">
								        <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3" role="none">
                							<div class="method relative flex cursor-pointer overflow-hidden rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-3 method-list" id="method-1" onclick="select_method('1');">
    											<span class="flex w-full">
    												<span class="flex w-full flex-col justify-between">
    													<div class="">
    													   <img src="{{ !$config ? '' : $config->logo_header }}" alt="{{ ENV('APP_NAME') }} Cash" class="max-h-5" style="max-width: 100%;height: auto;">
    													</div>
    												    <div class="flex w-full items-center justify-between">
    													    <div class="mt-2 w-full">
    													        <div class="mt-1.5 flex items-center gap-2">
    													            <div class="relative z-30 text-xs font-semibold leading-4 text-murky-800"><h6 class="hargapembayaran"></h6></div>
    													        </div>
    													        <div class="mt-0.5 h-px w-full bg-murky-300"></div>
    													        <div>
    													            <span class="block text-xxs italic text-murky-800" id="payment_code">{{ ENV("APP_NAME") }} Cash</span>
    													        </div>
    												        </div>
    												    </div>
    											    </span>
    											</span>
    										</div>
										</div>
								    </div>
								</div>
							    <div class="p-1 text-end" style="border-radius: 0 0 6px 6px;background: #E6E7EB;">
							        <img src="{{ !$config ? '' : $config->logo_header }}" alt="" height="16" class="me-2">
							    </div>
							</div>
							@endif
						</div>
                    </div>
		        </div>
		        <div class="rounded-xl bg-title-product shadow-2xl mb-4" id="section-bukti">
		            <div class="flex border-b border-murky-600 mb-2">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-murky-700-linear px-4 py-2 text-xl font-semibold">4</div>
                        <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b to-murky-800 px-2 text-base font-semibold leading-6 text-white sm:px-4" style="padding-top: .9rem !important;padding-bottom: .5rem !important;">Bukti Pembelian </h3>
                    </div>
                    <div class="px-3 py-3">
    					<div class="mb-3">
    						<input type="email" class="form-control form-control-games" placeholder="Email" name="email" id="email" autocomplete="off">
    					</div>
    					<!--<div class="mb-3 d-none" id="getWa">
    						<div class="input-group">
    						    <select id="select2" name="format_wa">
    								@foreach($phone_country as $p_c)
    								<option value="{{$p_c->code}},{{$p_c->dial_code}}">{{$p_c->name}}</option>
    							    @endforeach
    							</select>
    						    <input type="text" class="form-control form-control-games" placeholder="No. Whatsapp" name="wa" autocomplete="off" onkeyup="loadNomor(this.value);">
    						</div>
    					</div>
    					<div class="mb-3">
    						<div class="flex">
    						    <input type="checkbox" id="checkwa"  autocomplete="off"> <p class="ms-1">Bukti via Whatsapp</p>
    						</div>
    					</div>-->
    					<div class="text-end">
							<input type="hidden" name="tombol" value="submit">
							<button class="btn btn-primary melll" type="button" onclick="order_confirm();" id="btn-order">Konfirmasi</button>
						</div>
						
                    </div>
		        </div>
		        <div class="sticky inset-x-0 bottom-0 z-10 -mx-4 !mt-0 w-screen d-md-none mb-4 rounded-xl bg-title-product shadow-2xl">
                    <div class="container space-y-0 py-3">
                        <div class="flex items-start justify-start space-x-4 py-2 md:hidden">
                            <div class="flex w-full flex-col space-y-1">
                                <div class="rounded-md border-l-4 border-yellow-400 bg-yellow-100 p-4">
                                    <div style="display: flex;align-items: center;">
                                        <div class="flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-5 w-5 text-yellow-700">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">Belum ada item produk yang dipilih.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start justify-start space-x-4 py-1 md:hidden">
                            <div class="mb-2 aspect-square">
                                <img alt="icon" sizes="100vw" srcset="{{ $kategori->thumbnail }}" src="{{ $kategori->thumbnail }}" width="100" height="100" decoding="async" data-nimg="1" class="aspect-square rounded-lg object-cover" loading="lazy" style="color: transparent" />
                            </div>
                            <div class="flex w-full flex-col space-y-1">
                                <div class="text-xs font-semibold cana select glowing-text">{{ $kategori->nama }}</div>
                                <div class="text-xs font-semibold selected-order"></div>
                                <p class="text-xs font-semibold text-warning selected-order"></p>
                                <div class="flex w-full items-center">
                                    <p class="text-xs italic select"></p>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center !important;">
        					 <input type="hidden" name="tombol" value="submit">
        					<button class="btn btn-primary" type="button" onclick="order_confirms();" id="btn-orders">Konfirmasi</button>
        				</div>
                    </div>
                </div>
                <div class="rounded-xl bg-title-product shadow-2xl d-block d-sm-none">
        			<div class="flex border-b border-murky-600">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-murky-700-linear to-primary-600 px-4 py-2 text-xl font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                <path
                                    fill-rule="evenodd"
                                    d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                    clip-rule="evenodd">
                                </path>
                            </svg>
                        </div>
                        <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b to-murky-800 px-2 text-base font-semibold leading-6 text-white sm:px-4" style="padding-top: .9rem !important;padding-bottom: .5rem !important;">Testimoni</h3>
                    </div>
        			@if(count($rating) == 0)
        			<div class="px-2 py-2">
            			<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                        <strong>Information!</strong> Produk belum ada penilaian.</div>
                    </div>
    				@else
    				@php
                    $result = DB::table('ratings')->where('kategori_id',$kategori->id)->get();
                    $average_rating = 0;
                    $total_review = 0;
                    $five_star_review = 0;
                    $four_star_review = 0;
                    $three_star_review = 0;
                    $two_star_review = 0;
                    $one_star_review = 0;
                    $total_user_rating = 0;
                    foreach($result as $row) {
                        if($row->bintang == '5') {
                            $five_star_review++;
                        }
                        if($row->bintang == '4') {
                            $four_star_review++;
                        }
                        if($row->bintang == '3') {
                            $three_star_review++;
                        }
                        if($row->bintang == '2') {
                            $two_star_review++;
                        }
                        if($row->bintang == '1') {
                            $one_star_review++;
                        }
                        $total_review++;
                        $total_user_rating = $total_user_rating + $row->bintang;
                    }
                    $width_five_star = $five_star_review / $total_review * 100;
                    $width_four_star = $four_star_review / $total_review * 100;
                    $width_three_star = $three_star_review / $total_review * 100;
                    $width_two_star = $two_star_review / $total_review * 100;
                    $width_one_star = $one_star_review / $total_review * 100;
                    $average_rating = $total_user_rating / $total_review;
                    @endphp
                    <div class="flex flex-col space-y-8">
            	        <div class="">
            			    <div class="">
            				    <div class="">
            					    <div class="flow-root p-6">
                                        <div class="-my-6">
                                            <div>
                                                <h2 class="sr-only">Testimoni Pembeli</h2>
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-10 w-10 flex-shrink-0 text-yellow-400">
                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <div>
                                                            <span class="text-5xl font-semibold text-text-color">{{number_format($average_rating, 1)}}</span>
                                                            <sub class="text-lg font-semibold text-text-color">/5.0</sub>
                                                        </div>
                                                    </div>
                                                    <p class="pt-2 text-center text-sm">Pelanggan merasa puas dengan produk ini. <br> Dari <span class="font-semibold">{{$total_review}}</span> ulasan.</p>
                                                </div>
                                                <div class="mt-6">
                                                    <h3 class="sr-only">Review data</h3>
                                                    <dl class="space-y-3">
                                                        <div class="flex items-center text-sm">
                                                            <dt class="flex flex-1 items-center">
                                                                <p class="w-3 font-medium text-text-color">5<span class="sr-only"> star reviews</span></p>
                                                                <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <div class="relative ml-3 flex-1">
                                                                        <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                        @if($width_five_star != 0)
                                                                        <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_five_star}}%;"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </dt>
                                                            <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$five_star_review}}<span class="sr-only"> reviews</span></dd>
                                                        </div>
                                                        <div class="flex items-center text-sm">
                                                            <dt class="flex flex-1 items-center">
                                                                <p class="w-3 font-medium text-text-color">4<span class="sr-only"> star reviews</span></p>
                                                                <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <div class="relative ml-3 flex-1">
                                                                        <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                        @if($width_four_star != 0)
                                                                        <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_four_star}}%;"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </dt>
                                                            <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$four_star_review}}<span class="sr-only"> reviews</span></dd>
                                                        </div>
                                                        <div class="flex items-center text-sm">
                                                            <dt class="flex flex-1 items-center">
                                                                <p class="w-3 font-medium text-text-color">3<span class="sr-only"> star reviews</span></p>
                                                                <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <div class="relative ml-3 flex-1">
                                                                        <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                        @if($width_three_star != 0)
                                                                        <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_three_star}}%;"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </dt>
                                                            <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$three_star_review}}<span class="sr-only"> reviews</span></dd>
                                                        </div>
                                                        <div class="flex items-center text-sm">
                                                            <dt class="flex flex-1 items-center">
                                                                <p class="w-3 font-medium text-text-color">2<span class="sr-only"> star reviews</span></p>
                                                                <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <div class="relative ml-3 flex-1">
                                                                        <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                        @if($width_two_star != 0)
                                                                        <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_two_star}}%;"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </dt>
                                                            <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$two_star_review}}<span class="sr-only"> reviews</span></dd>
                                                        </div>
                                                        <div class="flex items-center text-sm">
                                                            <dt class="flex flex-1 items-center">
                                                                <p class="w-3 font-medium text-text-color">1<span class="sr-only"> star reviews</span></p>
                                                                <div aria-hidden="true" class="ml-1 flex flex-1 items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-5 w-5 flex-shrink-0">
                                                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <div class="relative ml-3 flex-1">
                                                                        <div class="h-3 rounded-[4px] border border-gray-200 bg-gray-100"></div>
                                                                        @if($width_one_star != 0)
                                                                        <div class="absolute inset-y-0 rounded-[4px] border border-yellow-400 bg-yellow-400" style="width: {{$width_one_star}}%;"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </dt>
                                                            <dd class="ml-3 w-10 text-right text-sm tabular-nums text-text-color">{{$one_star_review}}<span class="sr-only"> reviews</span></dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                                <div class="mt-6">
                                                    <h3 class="text-lg font-medium text-text-color">Bagikan pengalamanmu!</h3>
                                                    <p class="mt-1 text-sm text-text-color/75">Apakah kamu menyukai produk ini? Beri tahu kami dan calon pembeli lainnya tentang pengalamanmu.</p>
                                                </div>
                                            </div><hr>
                					    @foreach($rating as $ratings)
                					    <div class="py-0">
                                            <div class="flex items-center">
                                                <div class="w-full">
                                                    <div class="flex items-start justify-between">
                                                        @php
                                                            $ymdhis = explode(' ',$ratings->created_at);
                                                            $month = [
                                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                'Juli','Agustus','September','Oktober','November','Desember'
                                                            ];
                                                            $explode = explode('-', $ymdhis[0]);
                                                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                        @endphp
                                                        <div><h4 class="mt-0.5 text-xs font-bold text-white">62{{substr($ratings->no_pembeli,0,-15).'********'.substr($ratings->no_pembeli, -2)}}</h4></div>
                                                        <div class="flex items-center">
                                                            @for($i=1; $i<=5; $i++) 
                                                                @if($i <= $ratings->bintang)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-yellow-400 h-4 w-4 flex-shrink-0">
                                                                        <path
                                                                            fill-rule="evenodd"
                                                                            d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                                                            clip-rule="evenodd"
                                                                        ></path>
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white" aria-hidden="true" class="text-yellow-400 h-4 w-4 flex-shrink-0">
                                                                        <path
                                                                            fill-rule="evenodd"
                                                                            d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                                                            clip-rule="evenodd"
                                                                        ></path>
                                                                    </svg>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="sr-only">5 dari 5 bintang</p>
                                                </div>
                                            </div>
                                            <div class="flex w-full justify-between text-xxs"><span>{{ $ratings->layanan }}</span><span>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</span></div>
                                            <div class="text-murky-20 mt-1 space-y-6 text-xs italic">“{{ $ratings->comment }}”</div>
                                        </div><hr>
                					    @endforeach
            					        </div>
            					    </div>
            					    <div class="px-4 pb-4 sm:px-6 sm:pb-6">
                                        <a class="inline-flex items-center gap-x-2 text-sm" href="/reviews" style="color:white;outline: none;">
                                            <span>Lihat Semua</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                                    clip-rule="evenodd"
                                                ></path>
                                            </svg>
                                        </a>
                                    </div>
            					</div>
            				</div>
            			</div>
                    </div>
                    @endif
        		</div>
		    </ul>
		</div>
	</div>
	<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered rounded-4">
	        <div class="modal-content rounded-4" style="background: var(--warna_4);">
	            <div class="modal-body rounded-4 text-white py-5">
	            	<div class="text-center">
	            		<img src="/assets/images/confirm.png" alt="" width="76" class="mb-3">
	            		<h6 class="mb-3">Konfirmasi Pesanan</h6>
	            	</div>
	            	<div class="px-5-desktop">
	            		<table class="table mb-4">
		            		<tr>
		            			<th>Kategori</th>
		            			<td class="text-end">{{ $kategori->nama }}</td>
		            		</tr>
		            		<tr id="tr-product">
		            			<th>Produk</th>
		            			<td class="text-end">-</td>
		            		</tr>
		            		<tr id="tr-id-player">
		            			<th>Tujuan</th>
		            			<td class="text-end">-</td>
		            		</tr>
		            		<tr id="tr-voucher">
		            			<th>Voucher</th>
		            			<td class="text-end">-</td>
		            		</tr>
		            		<tr id="tr-method">
		            			<th>Metode</th>
		            			<td class="text-end">-</td>
		            		</tr>
		            		<tr id="tr-total">
		            			<th>Total</th>
		            			<td class="text-end">-</td>
		            		</tr>
		            	</table>
	            	</div>
	            	<div class="text-center">
	            		<button type="button" class="btn text-white px-4" onclick="order_close();">Batal</button>
	            		<button type="button" class="btn btn-primary px-4" id="btn-order-process" onclick="order_process();">Bayar Sekarang</button>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="modal fade" id="petunjukModal" tabindex="-1" aria-labelledby="petunjukModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered rounded-4">
	        <div class="modal-content rounded-4" style="background: var(--warna_4);">
	            <div class="modal-body rounded-4 text-white py-5">
	            	<div class="px-5-desktop">
	            	   <p style="text-align:center"><img src="{{ $kategori->petunjuk }}" alt="" class="img-fluid"></p>
	            	</div>
	            	<div class="text-center mt-1">
	            		<button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Ok, sip</button>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
	@if(isset($kategori->deskripsi_popup))
	<div class="modal fade" id="modal-popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--warna_4);">
                <div class="modal-body">
                    <?php echo nl2br($kategori->deskripsi_popup) ?>
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tutup</button>
                        <!--<button type="button" class="btn btn-primary" onclick="modal__hide();">Saya sudah membaca</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(count(DB::table('layanans')->where('kategori_id',$kategori->id)->get()) != 0)
	<div class="modal fade" id="ShowDescService" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--warna_4);">
                <div class="modal-body" id="modal-detail-body">
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(count($voucher) != 0)
    <div class="modal fade" id="modalpromo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: var(--warna_4);">
                <div class="modal-header"><h4 class="modal-title">Promo yang tersedia</h4></div>
                <div class="modal-body">
                    @foreach($voucher as $v)
                    @php
                    $kats = DB::table('kategoris')->where('id',$v->kategori_id)->first(); 
                    $ymdhis = explode(' ',$v->expired);
                    $month = [
                        1 => 'Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember'
                    ];
                    $explode = explode('-', $ymdhis[0]);
                    $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                    if($v->globals == 0 AND $v->kategori_id == $kategori->id AND $v->versi == "login") {
                        if(Auth::check()){
                            if($v->role == Auth::user()->role){
                                $ds = '';
                                $bg = 'bg-emerald-500';
                                $st = 'Tersedia';
                            } else {
                                $ds = 'disabled';
                                $bg = 'bg-red-500';
                                $st = 'Tidak Tersedia';
                            }
                        } else {
                            $ds = 'disabled';
                            $bg = 'bg-red-500';
                            $st = 'Tidak Tersedia';
                        }
                    } else if($v->globals == 1 AND $v->kategori_id == null AND $v->versi == "login") {
                        if(Auth::check()){
                            if($v->role == Auth::user()->role){
                                $ds = '';
                                $bg = 'bg-emerald-500';
                                $st = 'Tersedia';
                            } else {
                                $ds = 'disabled';
                                $bg = 'bg-red-500';
                                $st = 'Tidak Tersedia';
                            }
                        } else {
                            $ds = 'disabled';
                            $bg = 'bg-red-500';
                            $st = 'Tidak Tersedia';
                        }
                    } else if($v->globals == 0 AND $v->kategori_id == $kategori->id AND $v->versi == "public") {
                        $ds = '';
                        $bg = 'bg-emerald-500';
                        $st = 'Tersedia';
                    } elseif($v->globals == 1 AND $v->versi == "public") {
                        $ds = '';
                        $bg = 'bg-emerald-500';
                        $st = 'Tersedia';
                    } else {
                        $ds = 'disabled';
                        $bg = 'bg-red-500';
                        $st = 'Tidak Tersedia';
                    }
                    @endphp
                        @if(date('Y-m-d H:i:s') < $v->expired)
                        <div class="md:w-full lg:w-full hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out transform overflow-hidden rounded-2xl mt-2">
                            <div id="promo-{{$v->kode}}" class="promo h-full p-2 px-3 cursor-pointer bg-product promo-list rounded-4 from-murky-700 bg-gradient-to-t {{ $ds }}" promo-id="{{$v->kode}}" onclick="select_voucher('{{$v->kode}}');">
                                <b class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-semibold text-primary-500" id="headlessui-label-:rjm:">Diskon hingga {{ $v->promo }}%</span>
                                        <div class="pt-2">
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjn:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span style="color:white;">Promo berlaku hingga {{ $formatted.' '.substr($ymdhis[1],0,5).' WIB' }}</span>
                                            </span>
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjo:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Minimum pembelian Rp. {{ number_format($v->min_transaksi) }}</span>
                                            </span>
                                            @if($v->versi == "login")
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjo:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Batas penggunaan {{ $v->limit_voucher_login }}x untuk setiap akun.</span>
                                            </span>
                                            @endif
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjo:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Kuota Promo {{ $v->stock }}</span>
                                            </span>
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjp:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Maximum Potongan Rp. {{ number_format($v->max_potongan) }}</span>
                                            </span>
                                            @if(isset($kats))
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjo:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Promo ini berlaku untuk produk {{ $kats->nama }}</span>
                                            </span>
                                            @endif
                                            @if($v->versi == "login")
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjo:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Voucher ini berlaku untuk <span style="color:#61FF33;">Login</span> role <span style="color:#61FF33;">{{$v->role}}</span></span>
                                            </span>
                                            @endif
                                            @if(date('Y-m-d H:i:s') > $v->expired)
                                            <span class="mt-1 flex items-center gap-x-2 text-xs text-murky-700" id="headlessui-description-:rjo:">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                                </svg>
                                                <span style="color:white;">Voucher telah kadaluarsa!</span>
                                            </span>
                                            @endif
                                        </div>
                                        <span class="mt-3 text-xs font-medium text-murky-900" id="headlessui-description-:rjr:">Kode Promo: <span class="font-semibold">{{ $v->kode }}</span></span>
                                        <span class="absolute bottom-2 right-2 rounded-md px-2 py-1 text-xs font-medium text-white {{ $bg }}">{{ $st }}</span>
                                    </span>
                                </b>
                            </div>
                        </div>
                        @endif
                    @endforeach
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="modal fade" id="modalpromo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: var(--warna_4);">
                <div class="modal-header"><h4 class="modal-title">Promo yang tersedia</h4></div>
                <div class="modal-body">
                    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <strong>Information!</strong> Promo sedang tidak tersedia.</div>
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('js')
<!-- <script async src="https://www.google.com/recaptcha/api.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
	$('#modalPromo').click(function(){
  		$('#modalpromo').modal('show')
	});
});
$(document).ready(function(){
    $('.showdescservice').click(function(){
    $('#ShowDescService').modal('show');
        var product = $("input[name=product]").val();
        $.ajax({
            type: "GET",
            url: '{{ENV("APP_URL")}}/order/description/' + product,
            beforeSend: function() {
                $('#modal-detail-body').html('Sedang memuat...');
            },
            success: function(result) {
                $('#modal-detail-body').html(result);
            },
            error: function() {
                $('#modal-detail-body').html('Terjadi kesalahan.');
            }
        });
    });
});
</script>
<script>
    function showInitialElement() {
        var initialElement = document.querySelector('.flex.items-start.justify-start.space-x-4.py-2.md\\:hidden');
        var selectedElement = document.querySelector('.flex.items-start.justify-start.space-x-4.py-1.md\\:hidden');
        initialElement.style.display = 'flex';
        selectedElement.style.display = 'none';
    }

    function showSelectedElement() {
        var initialElement = document.querySelector('.flex.items-start.justify-start.space-x-4.py-2.md\\:hidden');
        var selectedElement = document.querySelector('.flex.items-start.justify-start.space-x-4.py-1.md\\:hidden');
        initialElement.style.display = 'none';
        selectedElement.style.display = 'flex';
    }

    function updateSelectedElement(name, price) {
        var selectedOrderElement = document.querySelector('.text-xs.font-semibold.selected-order');
        var energyYellowElement = document.querySelector('.text-xs.font-semibold.text-warning.selected-order');
        var viaElement = document.querySelector('.flex.w-full.items-center p.text-xs.italic');

        selectedOrderElement.textContent = name;
        energyYellowElement.textContent = price;
    }
    
    function updateSelectedElements(price) {
        var energyYellowElement = document.querySelector('.text-xs.font-semibold.text-warning.selected-order');
        var viaElement = document.querySelector('.flex.w-full.items-center p.text-xs.italic');
        
        energyYellowElement.textContent = price;
    }

    var listGroupItems = document.querySelectorAll('.product-list');

    listGroupItems.forEach(function (item) {
        item.addEventListener('click', function () {
            var name = this.querySelector('#namalayanan').textContent;
            var price = this.querySelector('.harga') ? this.querySelector('.harga')
                .textContent : this.querySelector('.text-dark.meltih').textContent;
            updateSelectedElement(name, price);
            showSelectedElement();
        });
    });

    showInitialElement();
</script>

<script>
    var listGroupItems = document.querySelectorAll('.method-list');
    listGroupItems.forEach(function (item) {
        item.addEventListener('click', function () {
            var name = this.querySelector('.text-dark.meltihhh').textContent.trim();
            var selectElement = document.querySelector('.text-xs.italic.select');
            selectElement.textContent = name;
        });
    });
    var listGroupItems = document.querySelectorAll('.method-list');
    listGroupItems.forEach(function (item) {
        item.addEventListener('click', function () {
            var price = this.querySelector('.h6') ? this.querySelector('.h6').textContent : this.querySelector('.hargapembayaran').textContent;
            updateSelectedElements(price);
            showSelectedElement();
        });
    });
</script>
<script>
    var modal_popup = new bootstrap.Modal(document.getElementById('modal-popup'));
    modal_popup .show();
</script>
<script type="text/javascript">
        function select_voucher(id) {
            var target = $('.games-input').map(function() {return this.value;}).get().join(',');
            var service = $("#product").val();
			if ($("#promo-" + id).hasClass('disabled')) {
			    toastr.error('Voucher tidak dapat digunakan.', 'Gagal');
			} else if (!target || target == ' ' || target == '' || target == ',') {
			    toastr.error('Silahkan isi data!.', 'Gagal');
			    $('html, body').animate({
    		        scrollTop: $("#section-order").offset().top
    		    }, 400);
			} else if (!service) {
			    $("#voucher").val(id);
			    $('#modalpromo').modal('hide');
			    $(".promo").removeClass('active');
    			$("#promo-" + id).addClass('active');
			    toastr.error('Silahkan pilih nominal terlebih dahulu.', 'Gagal');
			    $('html, body').animate({
    		        scrollTop: $("#section-item").offset().top
    		    }, 400);
			} else {
			    $('#modalpromo').modal('hide');
			    $(".promo").removeClass('active');
    			$("#promo-" + id).addClass('active');
    			$("#voucher").val(id);
			}
		}
        function formatState (state) {
    	    
            if (!state.id) {
                return state.text;
            }
            
            flag = state.element.value.split(',');
            
            var $state = $('<span><img src="https://flagcdn.com/16x12/' + flag[0] + '.png" class="eniv-flag"> ' + state.text + '</span>');
            
            return $state;
        };

	    $("#select2").select2({ 
	        dropdownAutoWidth: 'true',
	        templateResult: formatState
	    });
	    
	    $("#select2").on('select2:select', function (e) {
	        
            var flag = e.params.data.id.split(',');
            
            $(".select2-selection__rendered").html('<img src="https://flagcdn.com/24x18/' + flag[0] + '.png" class="eniv-flag">');
        });
        
        setTimeout(function() {
            
            if ($(".select2-selection__rendered").text() == 'Indonesia (+62)') {
                $(".select2-selection__rendered").html('<img src="https://flagcdn.com/24x18/id.png" class="eniv-flag">');
            }
        }, 300);
        
        function formatNomorHP(nomorHP, kodeNegara) {
            
            var kodeNegaraNow = nomorHP.split(' ')[0];
            
            nomorHP = nomorHP.replace(/\D/g, '');
            
            if (nomorHP.substring(0, 1) == 0) {
                nomorHP = nomorHP.substring(1);
            }

            /*var list_flag = [''];*/
            
            if (kodeNegara.includes(kodeNegaraNow) == true) {
                nomorHP = nomorHP.substring(kodeNegaraNow.length - 1);
            }
            
            nomorHP = nomorHP.replace(/(\d{3})(\d{4})(\d{4})/, '$1 $2 $3');
            
            return nomorHP;
        }
        
        function loadNomor(nohp) {
            
            var flag = $("select[name=format_wa]").val().split(',');
            
            $("input[name=wa]").val(flag[1] + ' ' + formatNomorHP(nohp, flag[1]));
        }
        
        /*const checkbox = document.getElementById('checkwa')
        checkbox.addEventListener('change', (event) => {
          if (event.currentTarget.checked) {
            $("#getWa").removeClass('d-none');
          } else {
            $("#getWa").addClass('d-none');
          }
        })*/
		
		function show_category(id) {
		    
		    $(".row-category").addClass('d-none');
		    
	        $(".product-category-item").removeClass('active');
	        $("#category-" + id).addClass('active');
		    
		    if (id == 'all') {
		        $(".row-category").removeClass('d-none');
		    } else {
		        $("#row-" + id).removeClass('d-none');
		    }
	    }
        
        var modal_detail = new bootstrap.Modal(document.getElementById('modal-detail'));
        
		function select_product(id) {
		    
			$('html, body').animate({
		        scrollTop: $("#section-method").offset().top
		    }, 400);

			$(".bg-product").removeClass('active');
			$("#product-" + id).addClass('active');
			
			$(".method").removeClass('active');
			
			$("input[name=method]").val('');
			$("input[name=product]").val(id);
            var qty = $("input[name=qty]").val();
			$.ajax({
				url: '{{ENV("APP_URL")}}/order/harga/' + id + '/' + qty,
				dataType: 'JSON',
				success: function(result) {
					for (let price in result) {
					    
						$("#method-" + result[price].id + ' h6').text(result[price].price);
						
						$("#method-" + result[price].id).removeClass('disabled');
						$('#infoMin').text(result[price].min)
                        $('#infoMax').text(result[price].max)
						if (result[price].status == 'Dis') {
						    $("#method-" + result[price].id).addClass('disabled');
						}
					}
				}
			});
		}
		
		$("input[name=qty]").on('keyup', function() {
            var qty = $("input[name=qty]").val();
            var product = $("input[name=product]").val();
			$.ajax({
				url: '{{ENV("APP_URL")}}/order/harga/' + product + '/' + qty,
				dataType: 'JSON',
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
		$("#custom_comments").on('keyup', function() {
            var count = 1;
            for (let i = 0; i < $(this).val().length; i++) {
                if ($(this).val()[i] == "\n") {
                    count++;
                }
            }
            $('#qty').val(count).trigger('input').trigger('keyup')
        })
		
        $("#btn-check").on("click", function(){
            var voucher = $("#voucher").val();
            var product = $("input[name=product]").val();
            var target = $('.games-input').map(function() {return this.value;}).get().join(',');
            $.ajax({
                url: "<?php echo route('check.voucher') ?>",
                dataType: "JSON",
                type: "POST",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    "voucher": voucher,
                    "service": product,
                    "target": target,
                },
                beforeSend: function () {
				    $("#btn-check").attr('disabled', 'disabled').text('Loading...');
				},
                success: function (result){
                    if(result.status != false) {
                        toastr.success('Voucher ditemukan.', 'Success!');
                        for (let price in result) {
        					$("#method-" + result[price].id + ' h6').text(result[price].price);
        					$("#method-" + result[price].id).removeClass('disabled');
        				    if (result[price].status == 'Dis') {
        					    $("#method-" + result[price].id).addClass('disabled');
        					}
        					$('#text-voucher').text(result[price].text);
        					$('#voc').val(result[price].kode);
        				}
        			    $("#btn-check").removeAttr('disabled').text('Gunakan Kode Promo');
                    } else {
                        toastr.error(result.message, 'Gagal');
                        if(result.code == 401) {
                            $('html, body').animate({
                		        scrollTop: $("#section-item").offset().top
                		    }, 400);
                        } else if(result.code == 402) {
                            $('html, body').animate({
                		        scrollTop: $("#section-order").offset().top
                		    }, 400);
                        }
                        $("#btn-check").removeAttr('disabled').text('Gunakan Kode Promo');
                    }
                },
            }) 
        });
		function select_method(id) {

			var product = $("input[name=product]").val();

			if (!product) {
				toastr.error('Silahkan pilih nominal terlebih dahulu.', 'Gagal');
			} else {
			    
			    if ($("#method-" + id).hasClass('disabled')) {
			        toastr.error('Metode tidak dapat digunakan.', 'Gagal');
			    } else {
			        $(".method").removeClass('active');
    				$("#method-" + id).addClass('active');
                    $('html, body').animate({
        		        scrollTop: $("#section-bukti").offset().top
        		    }, 400);
    				$("input[name=method]").val(id);
			    }
			}
		}
        function order_confirms() {
			var product = $("input[name=product]").val();
			var method = $("input[name=method]").val();
			var email = $("input[name=email]").val();
            var qty = $("#qty").val();
            var ktg_tipe = $("#ktg_tipe").val();
            var uid = $("#user_id").val();
            var custom_comments = $("#custom_comments").val();
            var usernames = $("#usernames").val();
            var hashtag = $("#hashtag").val();
            var expiry = $("#expiry").val();
            var delay = $("#delay").val();
            var old_post = $("#old_post").val();
            var maximal = $("#maximal").val();
            var minimal = $("#minimal").val();
            var post = $("#post").val();
            var media = $("#media").val();
            var answer_number = $("#answer_number").val();
            var keywords = $("#keywords").val();
            var voucher = $("#voucher").val();
            var voc = $("#voc").val();
            var ip = $("#ip").val();
			var target = $('.games-input').map(function() {
		        return this.value;
		    }).get().join(',');

			if (!target || target == ' ' || target == '' || target == ',') {
				toastr.error('Silahkan isi data pelanggan.', 'Gagal');
			} else if (!product) {
				toastr.error('Silahkan pilih nominal terlebih dahulu!', 'Gagal');
			} else if (!method) {
				toastr.error('Silahkan pilih metode pembayaran!', 'Gagal');
			} else if (!email) {
				toastr.error('Silahkan isi Email Anda!', 'Gagal');
			} else {

				$("#tr-product td").text($("#product-" + product + " h10").text());
				$("#tr-method td").text($("#method-" + method + " #payment_code").text());
				$("#tr-total td").text($("#method-" + method + " h6").text());
				$("#tr-voucher td").text($("#voc").val());
				$("#tr-id-player td").text(target.replaceAll(',', ' '));

				$.ajax({
					url: '<?php echo route('ajax.confirm-data') ?>',
					dataType: 'JSON',
					type: 'POST',
					data: {
                        '_token': '<?php echo csrf_token(); ?>',
                        'uid': uid,
                        'custom_comments': custom_comments,
                        'usernames': usernames,
                        'hashtag': hashtag,
                        'expiry': expiry,
                        'delay': delay,
                        'old_post': old_post,
                        'maximal': maximal,
                        'minimal': minimal,
                        'post': post,
                        'media': media,
                        'answer_number': answer_number,
                        'keywords': keywords,
                        'service': product,
                        'payment_method': method,
                        'qty' : qty,
                        'ktg_tipe' : ktg_tipe,
                        'voucher': voucher,
                        'ip': ip
                    },
					beforeSend: function () {
					    $("#btn-orders").attr('disabled', 'disabled').text('Loading...');
					},
					success: function(result) {
						if (result) {
							if (result.ok) {
								modal_detail.show();
							} else {
								toastr.error(result.msg, 'Gagal');
								$("#btn-orders").removeAttr('disabled').text('Konfirmasi');
							}
						} else {
							toastr.error('Pengecekan gagal, silahkan hubungi admin.', 'Gagal');
							$("#btn-orders").removeAttr('disabled').text('Konfirmasi');
						}

						$("#btn-orders").removeAttr('disabled').text('Konfirmasi');
					},
					error: function(e) {
                        if (e.status == 422) {
                            toastr.error('Invalid Captcha.', 'Gagal');
                            $("#btn-orders").removeAttr('disabled').text('Konfirmasi');
                        }
                    }
				});
			}
		}
		function order_confirm() {

			var product = $("input[name=product]").val();
			var method = $("input[name=method]").val();
			var email = $("input[name=email]").val();
            var qty = $("#qty").val();
            var ktg_tipe = $("#ktg_tipe").val();
            var uid = $("#user_id").val();
            var custom_comments = $("#custom_comments").val();
            var usernames = $("#usernames").val();
            var hashtag = $("#hashtag").val();
            var expiry = $("#expiry").val();
            var delay = $("#delay").val();
            var old_post = $("#old_post").val();
            var maximal = $("#maximal").val();
            var minimal = $("#minimal").val();
            var post = $("#post").val();
            var media = $("#media").val();
            var answer_number = $("#answer_number").val();
            var keywords = $("#keywords").val();
            var voucher = $("#voucher").val();
            var voc = $("#voc").val();
            var ip = $("#ip").val();
			var target = $('.games-input').map(function() {
		        return this.value;
		    }).get().join(',');

			if (!target || target == ' ' || target == '' || target == ',') {
				toastr.error('Silahkan isi data pelanggan.', 'Gagal');
			} else if (!product) {
				toastr.error('Silahkan pilih nominal terlebih dahulu!', 'Gagal');
			} else if (!method) {
				toastr.error('Silahkan pilih metode pembayaran!', 'Gagal');
			} else if (!email) {
				toastr.error('Silahkan isi Email Anda!', 'Gagal');
			} else {

				$("#tr-product td").text($("#product-" + product + " h10").text());
				$("#tr-method td").text($("#method-" + method + " #payment_code").text());
				$("#tr-total td").text($("#method-" + method + " h6").text());
				$("#tr-voucher td").text($("#voc").val());
				$("#tr-id-player td").text(target.replaceAll(',', ' '));

				$.ajax({
					url: '<?php echo route('ajax.confirm-data') ?>',
					dataType: 'JSON',
					type: 'POST',
					data: {
                        '_token': '<?php echo csrf_token(); ?>',
                        'uid': uid,
                        'custom_comments': custom_comments,
                        'usernames': usernames,
                        'hashtag': hashtag,
                        'expiry': expiry,
                        'delay': delay,
                        'old_post': old_post,
                        'maximal': maximal,
                        'minimal': minimal,
                        'post': post,
                        'media': media,
                        'answer_number': answer_number,
                        'keywords': keywords,
                        'service': product,
                        'payment_method': method,
                        'qty' : qty,
                        'ktg_tipe' : ktg_tipe,
                        'voucher': voucher,
                        'ip': ip
                    },
					beforeSend: function () {
					    $("#btn-order").attr('disabled', 'disabled').text('Loading...');
					},
					success: function(result) {
						if (result) {
							if (result.ok) {
								modal_detail.show();
							} else {
								toastr.error(result.msg, 'Gagal');
								$("#btn-order").removeAttr('disabled').text('Konfirmasi');
							}
						} else {
							toastr.error('Pengecekan gagal, silahkan hubungi admin.', 'Gagal');
							$("#btn-order").removeAttr('disabled').text('Konfirmasi');
						}

						$("#btn-order").removeAttr('disabled').text('Konfirmasi');
					},
					error: function(e) {
                        if (e.status == 422) {
                            toastr.error('Invalid Captcha.', 'Gagal');
                            $("#btn-orders").removeAttr('disabled').text('Konfirmasi');
                        }
                    }
				});
			}
		}

		function order_close() {
			$("#btn-order").removeAttr('disabled').text('Konfirmasi');
			modal_detail.hide();
		}

		function order_process() {
            var uid = $("#user_id").val();
            var custom_comments = $("#custom_comments").val();
            var usernames = $("#usernames").val();
            var hashtag = $("#hashtag").val();
            var expiry = $("#expiry").val();
            var delay = $("#delay").val();
            var old_post = $("#old_post").val();
            var maximal = $("#maximal").val();
            var minimal = $("#minimal").val();
            var post = $("#post").val();
            var media = $("#media").val();
            var answer_number = $("#answer_number").val();
            var keywords = $("#keywords").val();
            var qty = $("#qty").val();
            var service = $("#product").val();
            var pembayaran = $("#method").val();
            var nohp = $("input[name='wa']").val();
            var email = $("input[name='email']").val();
            var ktg_tipe = $("#ktg_tipe").val();
            var voucher = $("#voucher").val();
            var ip = $("#ip").val();

        	$.ajax({
                url: "<?php echo route('order') ?>",
                dataType: "JSON",
                type: "POST",
                data: {
                    '_token': '<?php echo csrf_token() ?>',
                    'uid': uid,
                    'custom_comments': custom_comments,
                    'usernames': usernames,
                    'hashtag': hashtag,
                    'expiry': expiry,
                    'delay': delay,
                    'old_post': old_post,
                    'maximal': maximal,
                    'minimal': minimal,
                    'post': post,
                    'media': media,
                    'answer_number': answer_number,
                    'keywords': keywords,
                    'service': service,
                    'payment_method': pembayaran,
                    'nomor': nohp,
                    'email': email,
                    'qty' : qty,
                    'ktg_tipe': ktg_tipe,
                    'voucher': voucher,
                    'ip': ip
                },
                beforeSend: function () {
				    $("#btn-order-process").attr('disabled', 'disabled').text('Loading...');
				},
				error: function() {
					toastr.error('Proses order gagal, silahkan hubungi admin.', 'Gagal');
					$("#btn-order-process").removeAttr('disabled').text('Bayar Sekarang');
				},
            	success: function(resOrder) {
                    if (resOrder.status) {
                        toastr.success(`Order ID : ${resOrder.order_id}`, 'Berhasil memesan!');
                        window.location = `/pembelian/invoice/${resOrder.order_id}`;
                        $("#btn-order-process").removeAttr('disabled').text('Bayar Sekarang');
                    } else {
                        toastr.error(`${resOrder.data}`, 'Gagal');
                        $("#btn-order-process").removeAttr('disabled').text('Bayar Sekarang');
                    }
                }
        	});
		}
</script>
<script>
    const toggleButton = document.getElementById('toggleButton');
    const dropdownContent = document.getElementById('dropdownContent');

    toggleButton.addEventListener('click', function() {
        const expanded = toggleButton.getAttribute('aria-expanded') === 'true' || false;
        toggleButton.setAttribute('aria-expanded', !expanded);
        dropdownContent.classList.toggle('hidden');
    });
</script>
@endsection