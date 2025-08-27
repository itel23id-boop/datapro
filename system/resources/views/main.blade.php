<!DOCTYPE html>
<!--
* INGIN BUAT WEBSITE SEPERTI INI?
* CONTACT ME ON : 0813-1188-3274
* DI MOHON UNTUK TIDAK MENGHAPUS INI HARGAI PEMBUAT SCRIPTNYA
-->
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>{{ !$config ? '' : $config->judul_web }} - {{ !$config ? '' : $config->deskripsi_web }}</title>
    
	<meta name="title" content="{{ !$config ? '' : $config->judul_web }}">
    <meta name="description" content="{{ !$config ? '' : $config->deskripsi_web }}">
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ ENV('APP_URL') }}">
    <meta property="og:title" content="{{ !$config ? '' : $config->judul_web }}">
    <meta property="og:description" content="{{ !$config ? '' : $config->deskripsi_web }}">
    <meta name="twitter:image" content="{{ !$config ? '' : $config->logo_footer }}" />
    <meta property="og:image" content="{{ !$config ? '' : $config->logo_footer }}" />
    <meta name="robots" content="index, follow">
    <meta content="desktop" name="device">
    <meta name="author" content="{{ ENV('APP_NAME') }}">
    <meta name="coverage" content="Worldwide">
    <meta name="apple-mobile-web-app-title" content="{{ !$config ? '' : $config->judul_web }}">
    
    <link rel="shortcut icon" href="{{ url('') }}{{ !$config ? '' : $config->logo_favicon }}">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.1/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
	<link rel="stylesheet" href="/assets/css/main-{{$config->change_theme}}.min.css?v=<?= time() ?>">
	<link rel="stylesheet" href="/assets/css/responsive.min.css?v=<?= time() ?>">
	<link rel="stylesheet" href="/assets/css/chatbox.css?v=<?= time() ?>">
	<link rel="stylesheet" href="/assets/css/style-{{$config->change_theme}}.css?v=<?= time() ?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.7.95/css/materialdesignicons.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/pixel.css?v=<?= time() ?>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('css')
        <style>
	    :root {
	        --warna_1: <?= $config->warna1; ?>;
            --warna_2: <?= $config->warna2; ?>;
            --warna_3: <?= $config->warna3; ?>;
            --warna_4: <?= $config->warna4; ?>;
            --warna_5: <?= $config->warna5; ?>;
            --warna_6: <?= $config->warna6; ?>;
            --warna_7: <?= $config->warna7; ?>;
            --warna_8: <?= $config->warna8; ?>;
            --warna: #FEC832;
            --primary: #FEC832;
            --success: #258f0a;
            --info: #1CB0F6;
            --danger: #D33131;
            --warning: #FA811B;
	    }
	    .resultsearch .dropdown-item:hover{
             background-color: hsla(0,0%,100%,.15);
             color: #fff;
        }
        body {
            line-height:26px;
            background: linear-gradient(to right, var(--warna_1), #2D2D2D);
        }
        .scroll-up-btn {
            display: none;
            position: fixed;
            width: 45px;
            height: 45px;
            bottom: 19px;
            right: 10px;
            color: #FFF;
            border: 1px solid transparent;
            background: var(--warna_2);
            border-radius: 10px;
            text-align: center;
            font-size: 16px;
            z-index: 99999;
        }
        .shadow-form {
            box-shadow: 0 4px 80px hsla(0, 0%, 77%, .13), 0 1.6711px 33.4221px hsla(0, 0%, 77%, .093), 0 0.893452px 17.869px hsla(0, 0%, 77%, .077), 0 0.500862px 10.0172px hsla(0, 0%, 77%, .065), 0 0.266004px 5.32008px hsla(0, 0%, 77%, .053), 0 0.11069px 2.21381px hsla(0, 0%, 77%, .037);
        }
        .border-0 {
            border: 0!important;
        }
        .card-auth {
            background-image: url(/assets/images/irithl.jpg);
        }
        .card-auth {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100%;
        }
        .card-auth-register {
            background-image: url(/assets/images/irithl.jpg);
        }
        .artikel-item {
	        display: inline-block;
	        text-decoration: none;
	        width: 100%;
	    }
	    .artikel-item .category, .artile-banner-card-body .category {
	        background: var(--warna);
            display: inline-block;
            padding: 1px 10px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            color: #333;
	    }
	    .artikel-item p {
	        margin-top: -6px;
	    }
	    .artikel-item .date {
	        color: #a3a3a3;
	    }
	    .lh-26 {
            line-height: 26px;
        }
        .lh-32 {
            line-height: 32px;
        }
    @media (min-width: 768px) {
        .card-auth {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 130%!important;
        }
        .card-auth-register {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 199%!important;
        }
    }
    @media (max-width: 640px) {
        .card-auth {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 283%!important;
        }
        .card-auth-register {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 445% !important;
        }
    }
    .card-auth-register {
        background-repeat: no-repeat;
        background-position: center;
        background-size: 129%;
    }
    .css-1jh570t {
        background: linear-gradient(90deg, rgba(39, 39, 48, 0.6138830532212884) 32%, rgba(255, 0, 0, 0) 100%);
    }
    .fab-container {
        position: fixed;
        bottom: 70px;
        right: 10px;
        z-index: 999;
        cursor: pointer;
    }

    .fab-icon-holder {
        width: 45px;
        height: 45px;
        bottom: 140px;
        left: 10px;
        color: #FFF;
        background: #FFF;
        /* padding: 1px; */
        border-radius: 10px;
        text-align: center;
        font-size: 30px;
        z-index: 99999;
    }

    .fab-icon-holder:hover {
        opacity: 0.8;
    }

    .fab-icon-holder i {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        font-size: 25px;
        color: #ffffff;
    }

    .fab-options {
        list-style-type: none;
        margin: 0;
        position: absolute;
        bottom: 48px;
        left: -37px;
        opacity: 0;
        transition: all 0.3s ease;
        transform: scale(0);
        transform-origin: 85% bottom;
    }

    .fab:hover+.fab-options,
    .fab-options:hover {
        opacity: 1;
        transform: scale(1);
    }

    .fab-options li {
        display: flex;
        justify-content: flex-start;
        padding: 5px;
    }

    .fab-label {
        padding: 2px 5px;
        align-self: center;
        user-select: none;
        white-space: nowrap;
        border-radius: 3px;
        font-size: 16px;
        background: #666666;
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        margin-left: 10px;
    }
    .img-chat {
        max-width: 100%;
        height: auto;
        /* background-color: #f89728; */
        border-radius: 10px;
    }
        .fasttopup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .fasttopup li{
            position: absolute;
            
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(202, 202, 203, 0.2);
         
            animation: animate 25s linear infinite;
            bottom: -150px;
            
        }
        
        .fasttopup li:nth-child(1){
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
            background-image :linear-gradient(45deg, var(--warna_1), var(--primary));
        }
        
        
        .fasttopup li:nth-child(2){
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
            background-image : linear-gradient(45deg, var(--warna_1), var(--primary));
        }
        
        .fasttopup li:nth-child(3){
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
            background-image : linear-gradient(45deg, var(--warna_2), var(--success));
        }
        
        .fasttopup li:nth-child(4){
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
            background-image : linear-gradient(45deg, var(--warna_3), var(--info));
        }
        
        .fasttopup li:nth-child(5){
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
            background-image : linear-gradient(45deg, var(--warna_4), var(--danger));
        }
        
        .fasttopup li:nth-child(6){
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
            background-image : linear-gradient(45deg, var(--warna), var(--danger));
        }
        
        .fasttopup li:nth-child(7){
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
            background-image : linear-gradient(45deg, var(--warna_1), var(--success));
        }
        
        .fasttopup li:nth-child(8){
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
            background-image : linear-gradient(45deg, var(--warna_2), var(--info));
        }
        
        .fasttopup li:nth-child(9){
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
            background-image : linear-gradient(45deg, var(--warna_3), var(--danger));
        }
        
        .fasttopup li:nth-child(10){
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
            background-image : linear-gradient(45deg, var(--warna_4), var(--warning));
        }
        @keyframes  animate {
            0%{
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100%{
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
        .animate-shimmer {
            animation: shimmer 1.75s linear infinite
        }
        
        @keyframes  flicker {
            0%,19.999%,22%,62.999%,64%,64.999%,70%,to {
                opacity: .99;
                filter: drop-shadow(0 0 1px rgba(252,211,77)) drop-shadow(0 0 15px rgba(245,158,11)) drop-shadow(0 0 1px rgba(252,211,77))
            }
        
            20%,21.999%,63%,63.999%,65%,69.999% {
                opacity: .4;
                filter: none
            }
        }
        
        .animate-flicker {
            animation: flicker 3s linear infinite
        }
        
        @keyframes  spin {
            to {
                transform: rotate(1turn)
            }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite
        }
        
	    .category ul {
	        overflow-x: auto;
            white-space: nowrap;
            display: flex;
            flex-wrap: nowrap;
	    }
	    .method {
	        background: #cfcfcf;
	    }
	    .content-main {
	        padding-top: 20px;
	        
	    }
	    .content-mains {
	        padding-top: 20px;
	        
	    }
	    .games a img {
	        border-radius: 18px 18px 0 0;
	    }
	    .games .shape {
	        clip-path: polygon(0 48%,9% 48%,18% 65%,27% 49%,36% 72%,45% 58%,55% 70%,64% 58%,73% 86%,82% 48%,91% 63%,100% 70%,100% calc(100% + 1px),0 calc(100% + 1px));
            -webkit-clip-path: polygon(0 48%,9% 48%,18% 65%,27% 49%,36% 72%,45% 58%,55% 70%,64% 58%,73% 86%,82% 48%,91% 63%,100% 70%,100% calc(100% + 1px),0 calc(100% + 1px));
            background: var(--warna_3);
            width: 100%;
            height: 28px;
            margin-top: -41px;
	    }
	    .games .box {
	        background: var(--warna_3);
            min-height: 64px;
            border-radius: 0 0 18px 18px;
            position: relative;
            z-index: 1;
            color: #333;
            font-weight: 500;
	    }
	    .row-g, .col-g {
	        padding: 0 6px;
	    }
	    .search {
	        width: 100%;
	    }
	    
	    @media print {
	        body {
	            padding: 0;
	            margin: 0;
	        }
	        .d-none-print {
	            display: none;
	        }
	        * {
	            color: #333 !important;
	        }
	        .card-print {
	            max-width: 450px;
	            margin: auto;
	        }
	        .card-print .card-title {
	            text-align: center;
	        }
	        .content-main {
	            padding-top: 0;
	        }
	    }
	</style>
	<style>
	    .select2 {
	        width: 78px !important;
	    }
	    .select2-container--default .select2-selection--single {
            background-color: transparent;
            border: 1px solid #676978;
            border-radius: 12px 0 0 12px;
            border-right: none;
        }
        .select2-container .select2-selection--single {
            height: 47px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff;
            line-height: 47px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding: 0;
            text-align: center;
        }
        .select2-container--default .select2-results>.select2-results__options::-webkit-scrollbar {
            width: 0;
        }
	</style>
    <style>
	    table.border, table.dataTable thead th, table.dataTable thead td {
            border-color: #353562 !important;
        }
        .dataTable {
            margin-bottom: 10px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            font-size: 14px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover, .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--warna_2);
            color: #333 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active, .dataTables_wrapper .dataTables_paginate .paginate_button, .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
            color: #fff !important;
        }
        .table-striped>tbody>tr:nth-of-type(odd)>* {
            color: #fff !important;
        }
        .dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select {
            border-color: #959595;
            outline: none !important;
            box-shadow: none !important;
            color: #fff !important;
            margin-left: 12px;
            border-radius: 100px;
            padding-left: 18px;
        }
        .dataTables_wrapper .dataTables_filter {
            float: left;
        }
        .table.border {
            border-color: #272f5d !important;
        }
        .border {
            border-color: #272f5d !important;
        }
        .dataTable .btn {
            padding: 6px 12px !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 0;
        }
        table.dataTable tbody th, table.dataTable tbody td {
            padding: 12px 8px;
        }
        
        .product {
	        height: calc(100% - 20px);
	    }
	    .nav {
	        border-bottom: none;
	        margin-bottom: 12px;
	    }
	    .nav .nav-item .nav-link {
	        color: #fff;
	        border: none !important;
	        box-shadow: none !important;
	        outline: none !important;
	        border-radius: 8px;
	        margin-right: 6px;
	    }
	    .nav .nav-item .nav-link.active, .nav .nav-item .nav-link:hover {
	        background: var(--warna_4);
	        color: #333;
	    }
	    .bg-transparent {
	        background: transparent !important;
	    }
	    input:read-only {
            background: #4A4A4A!important;
        }
        .hero-waves {
          display: block;
          margin-top: 100px;
          width: 100%;
          height: 90px;
          z-index: 5;
          position: relative;
        }
	</style>
</head>
<body>
    @include('layout.navbar.'.$config->change_theme)
	<div class="fasttopup">
	    <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </div>
    @yield('content')
    <svg width="50" class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
      <defs>
        <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
      </path></defs>
      <g class="wave1">
        <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
      </use></g>
      <g class="wave2">
        <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
      </use></g>
      <g class="wave3">
        <use xlink:href="#wave-path" x="50" y="4" fill="var(--warna_3)">
      </use></g>
    </svg>
    
	@include('layout.footer.'.$config->change_theme)

	<div class="fab-container">
    <div class="fab fab-icon-holder">
    <img src="/assets/callcenter.png" class="img-chat" alt="">
    </div>
    <ul class="fab-options">
    <li>
    <a href="{{ !$config ? '' : $config->url_ig }}" class="text-decoration-none" target="_blank">
    <div class="fab-icon-holder" style="background: radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%), radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%), radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent), linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);">
    <i class="fab fa-instagram"></i>
    </div>
    </a>
    </li>
    <li>
    <a href="{{ !$config ? '' : $config->url_wa }}" class="text-decoration-none" target="_blank">
    <div class="fab-icon-holder" style="background-color: #25D366;">
    <i class="fab fa-whatsapp"></i>
    </div>
    </a>
    </li>
    <li>
    </li>
</div>
    <button class="scroll-up-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

	<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	@yield('js')
	<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Success");
    @endif
    @if(session('error'))
        toastr.error("{{ session('error') }}", "Error");
    @endif
    </script>
@if($config->change_theme == "2")
<script>
    const showButton = document.getElementById('showSearchButton');
    const searchContainer = document.getElementById('searchContainer');
    showButton.addEventListener('click', function() {
      const isHidden = searchContainer.classList.contains('hidden');
      if (isHidden) {
        searchContainer.classList.remove('hidden');
        searchContainer.classList.remove('h-0');
        searchContainer.classList.add('visible');
        searchContainer.classList.add('h-10');
        searchContainer.classList.add('py-1');
      } else {
        searchContainer.classList.remove('visible');
        searchContainer.classList.remove('h-10');
        searchContainer.classList.remove('py-1');
        searchContainer.classList.add('hidden');
        searchContainer.classList.add('h-0');
      }
    });
    searchContainer.classList.add('hidden');
    searchContainer.classList.add('h-0');
    showButton.classList.add('lucide-search');
    
    $('#searchInputs').on('keyup',function(){
    let input = $('#searchInputs')
    let base = $('#live_search')
    base.removeClass('d-none')
        if(input.val()){
                $.ajax({
                    method: "post",
                    url: "{{url('/cari/index')}}",
                    headers: {
                        'X-CSRF-TOKEN':"{{ csrf_token() }}"
                    },
                    data: {to_search:input.val()},
                    success: function (response) {
                        let categories = response.data
                        var html = ''
                        $.each(categories, function (index, value) { 
                            let url = "{{ env('APP_URL') }}/order/"+value.kode
                            html += `<div class="space-y-4 p-3">
            	                       <a class="block" href="${url}">
            	                           <div class="flex items-center gap-x-3">
            	                               <div class="relative h-12 w-12 overflow-hidden rounded-lg">
            	                                   <img alt="${value.nama}" loading="lazy" decoding="async" data-nimg="fill" sizes="100vw" src="${value.thumbnail}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
            	                               </div>
                	                           <div>
                	                               <h3 class="font-semibold" style="line-height: 0px!important;margin: 0!important;padding-top: 1rem;">${value.nama}</h3>
                	                               <h5 class="text-xxxs text-secondary-foreground">${value.sub_nama}</h5>
                	                           </div>
            	                           </div>
            	                        </a>
            	                     </div>`
                        });
                        base.html(html);
                    },
                    error: function(response){
                        base.html(`<div class="space-y-4 p-3">
            	                       <div class="flex items-center gap-x-3">
                	                       <div>
                	                           <h3 class="font-semibold" style="line-height: 0px!important;margin: 0!important;">Produk tidak tersedia!</h3>
                	                       </div>
            	                       </div>
            	                    </div>`)
                }
            });
        }else{
            base.addClass('d-none')
        }
    });
    
    $('#searchInput').on('keyup',function(){
    let input = $('#searchInput')
    let base = $('.animate-fade-enter')
    base.removeClass('d-none')
        if(input.val()){
                $.ajax({
                    method: "post",
                    url: "{{url('/cari/index')}}",
                    headers: {
                        'X-CSRF-TOKEN':"{{ csrf_token() }}"
                    },
                    data: {to_search:input.val()},
                    success: function (response) {
                        let categories = response.data
                        var html = ''
                        $.each(categories, function (index, value) { 
                            let url = "{{ env('APP_URL') }}/order/"+value.kode
                            html += `<div class="space-y-4 p-3">
            	                       <a class="block" href="${url}">
            	                           <div class="flex items-center gap-x-3">
            	                               <div class="relative h-12 w-12 overflow-hidden rounded-lg">
            	                                   <img alt="${value.nama}" loading="lazy" decoding="async" data-nimg="fill" sizes="100vw" src="${value.thumbnail}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
            	                               </div>
                	                           <div>
                	                               <h3 class="font-semibold" style="line-height: 0px!important;margin: 0!important;padding-top: 1rem;">${value.nama}</h3>
                	                               <h5 class="text-xxxs text-secondary-foreground">${value.sub_nama}</h5>
                	                           </div>
            	                           </div>
            	                        </a>
            	                     </div>`
                        });
                        base.html(html);
                    },
                    error: function(response){
                        base.html(`<div class="space-y-4 p-3">
            	                       <div class="flex items-center gap-x-3">
                	                       <div>
                	                           <h3 class="font-semibold" style="line-height: 0px!important;margin: 0!important;">Produk tidak tersedia!</h3>
                	                       </div>
            	                       </div>
            	                    </div>`)
                }
            });
        }else{
            base.addClass('d-none')
        }
    });
</script>
<script type="text/javascript">
    setInterval(function(){
        $(".altumcode-clickable").stop().slideToggle('slow');
    }, 5000);
    $(document).ready(function() {
        function showNotif(message) {
            $('#notif-message').html(message);
            $("#altumcode-close").click(function() {
                $(".altumcode-clickable").stop().slideToggle('slow');
            });
        }
        $.ajax({
            url: "{{ route('getnotif') }}",
            dataType: 'json',
            success: function(data) {
                if (data.order) {
                    showNotif(data.order);
                }
            },
            error: function(
                jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            }
        );
        setInterval(function() {
            $.ajax({
                url: "{{ route('getnotif') }}",
                dataType: 'json',
                success: function(data) {
                    if (data.order) {
                        showNotif(data.order);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }, 5000);
    });
</script>
@elseif($config->change_theme == "1")
<script>
    var EnternityDigitals = [
        <?php $pembelians = DB::table('pembelians')->orderBy('created_at','DESC')->get(); ?>
        @foreach ($pembelians as $pembelian)
            <?php 
            $layanan = DB::table('layanans')->where('id',$pembelian->id_layanan)->first();
            $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first();
            ?>
            {
                "order_id": "{{ $pembelian->order_id }}",
                "kategori": "{{ $kategori->nama }}",
                "layanan": "{{ $pembelian->layanan }}",
                "created_at": "{{ $pembelian->created_at }}",
                "logo": "{{ isset($kategori->thumbnail) ? $kategori->thumbnail : $config->logo_favicon }}"
            },
        @endforeach
    ];

    var currentIndex = 0;
    var widgetElement = null;
    var progressBar = null;
    var contentElement = null;

    function showCurrentContent() {
        var EnternityDigital = EnternityDigitals[currentIndex];
        var EnternityDiv = document.createElement('div');

        EnternityDiv.setAttribute('id', '' + EnternityDigital.order_id);
        EnternityDiv.setAttribute('class', 'tsp-widget alert-widget alert-widget-4 fade-in');
        EnternityDiv.setAttribute('style', 'inset: auto auto 10px 30px; display: block; z-index: 999; transition: opacity 1s');

        EnternityDiv.innerHTML = `
            <div class="pull-left icon" style="border-radius:10px;">
                <img class="live_preview_image" style="border-radius:10px;" src="${EnternityDigital.logo}" >
            </div>
            <div class="desc desc_live_preview" style="background-color:var(--warna_1);border:1px solid rgba(0, 0, 0, 0.19);border-radius:10px;">
                <span class="tsp-has-close-button" onclick="exitWidget()" style="cursor:pointer;position: absolute;text-shadow: 0px 0px 0px #000;right:10px">✖</span>
                <h4 class="desc-heading">
                    <small>
                        <span id="id${EnternityDigital.order_id}" class="desc-heading-name">
                            <span style="color: #9601a1"></span>
                            <a target="_blank" href="#">
                                <span style="color:#cd61ef"> ${EnternityDigital.order_id.slice(0, -6) + '*****'}</span>
                            </a>
                        </span>
                    </small>
                </h4>
                <div id="content${EnternityDigital.order_id}">
                    <h4 class="desc-heading_foot">
                      Membeli ${EnternityDigital.kategori} <a style="color:#fff;text-decoration: none;" target="_blank" href="#" title="${EnternityDigital.layanan}">${EnternityDigital.layanan}</a>
                    </h4>
                    <progress class="mt-1" id="progress${EnternityDigital.order_id}" value="0" max="100" style="width: 100%;"></progress>
                </div>
                 <small class="desc-heading_foot">
                        <a style="color:#fff;" target="_blank" href="#" title="{{ url('') }}{{ !$config ? '' : $config->judul_web }}</a>
                    </small>
            </div>`;

        document.getElementById('EnternityContainer').appendChild(EnternityDiv);

        contentElement = document.querySelector(`#content${EnternityDigital.order_id}`);
        progressBar = contentElement.querySelector(`#progress${EnternityDigital.order_id}`);
        widgetElement = document.getElementById('' + EnternityDigital.order_id);

        function hideWidget() {
            if (widgetElement) {
                widgetElement.style.opacity = 0; 

                setTimeout(function () {
                    widgetElement.remove();
                    currentIndex++;

                    if (currentIndex >= EnternityDigitals.length) {
                        currentIndex = 0;
                    }

                    showCurrentContent();
                }, 3000); 
            }
        }

        function runProgressBar(duration) {
            var startTime = new Date().getTime();
            var endTime = startTime + duration;

            function updateProgressBar() {
                var currentTime = new Date().getTime();
                var progress = Math.min((currentTime - startTime) / duration, 1);
                progressBar.value = progress * 100;

                if (currentTime < endTime) {
                    requestAnimationFrame(updateProgressBar);
                } else {
                    hideWidget();
                }
            }

            updateProgressBar();
        }

        runProgressBar(7000);
    }
    showCurrentContent();
    function exitWidget() {
        if (widgetElement) {
            widgetElement.style.opacity = 0; 

            widgetElement.remove();
            currentIndex++;

            if (currentIndex >= EnternityDigitals.length) {
                currentIndex = 0;
            }
        }
    }
    //<![CDATA[
          /*
            By Osvaldas Valutis, www.osvaldas.info
            Available for use under the MIT License
          */
        
        
          ;
          (function(document, window, index) {
            'use strict';
        
            var elSelector = '.header',
              elClassHidden = 'header--hidden',
              throttleTimeout = 500,
              element = document.querySelector(elSelector);
        
            if (!element) return true;
        
            var dHeight = 0,
              wHeight = 0,
              wScrollCurrent = 0,
              wScrollBefore = 0,
              wScrollDiff = 0,
        
              hasElementClass = function(element, className) {
                return element.classList ? element.classList.contains(className) : new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
              },
              addElementClass = function(element, className) {
                element.classList ? element.classList.add(className) : element.className += ' ' + className;
              },
              removeElementClass = function(element, className) {
                element.classList ? element.classList.remove(className) : element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
              },
        
              throttle = function(delay, fn) {
                var last, deferTimer;
                return function() {
                  var context = this,
                    args = arguments,
                    now = +new Date;
                  if (last && now < last + delay) {
                    clearTimeout(deferTimer);
                    deferTimer = setTimeout(function() {
                      last = now;
                      fn.apply(context, args);
                    }, delay);
                  } else {
                    last = now;
                    fn.apply(context, args);
                  }
                };
              };
        
            window.addEventListener('scroll', throttle(throttleTimeout, function() {
              dHeight = document.body.offsetHeight;
              wHeight = window.innerHeight;
              wScrollCurrent = window.pageYOffset;
              wScrollDiff = wScrollBefore - wScrollCurrent;
        
              if (wScrollCurrent <= 0) // scrolled to the very top; element sticks to the top
                removeElementClass(element, elClassHidden);
        
              else if (wScrollDiff > 0 && hasElementClass(element, elClassHidden)) // scrolled up; element slides in
                removeElementClass(element, elClassHidden);
        
              else if (wScrollDiff < 0) // scrolled down
              {
                if (wScrollCurrent + wHeight >= dHeight && hasElementClass(element, elClassHidden)) // scrolled to the very bottom; element slides in
                  removeElementClass(element, elClassHidden);
        
                else // scrolled down; element slides out
                  addElementClass(element, elClassHidden);
              }
        
              wScrollBefore = wScrollCurrent;
            }));
        
          }(document, window, 0));
          //]]>
</script>
<script>
    $('#banner').owlCarousel({
		    loop:true,
		    margin:10,
		    autoplay:true,
		    autoplayTimeout:3000,
		    responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:1
		        },
		        1000:{
		            items:1
		        }
		    }
		});

		$('#games-populer').owlCarousel({
		    loop:true,
		    margin:10,
		    autoplay:true,
		    autoplayTimeout:3000,
		    responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:1
		        },
		        1000:{
		            items:1
		        }
		    }
		});
</script>
@endif
		<script>
		var scrollBtn = document.querySelector(".scroll-up-btn");
        window.addEventListener("scroll", function() {
          if (window.pageYOffset > 20) {
            scrollBtn.style.display = "block";
          } else {
            scrollBtn.style.display = "none";
          }
        })
        scrollBtn.addEventListener("click", function() {
          window.scrollTo({
            top: 0,
            behavior: "smooth"
          });
        });
    	$("#datatable").dataTable({
    		pageLength: 10,
            ordering: false,
            lengthChange: false,
    	});
		$('#searchProds').keyup(function () {
            const data = $(this).val();
            if (data.length < 1) {
                $('.resultsearch').removeClass('show');
                $('.resultsearch li').remove();
            } else {
                delay(function () {
                    $.ajax({
                        url: "{{url('/cari/index')}}",
                        method: "POST",
                        data: {
                            data: data
                            },
                        beforeSend: function () {
                            $('.resultsearch li').remove();
                        },
                        success: function (res) {
                            $('.resultsearch').append(res);
                            $('.resultsearch').addClass('show');
                        }
                    })
                }, 1000);
            }
        })
		function load_games(category) {

			$(".li-category").removeClass('active');
			$("#li-" + category).addClass('active');

			if (category == 'all') {
				$(".row-games").removeClass('d-none');
			} else {
				$(".row-games").addClass('d-none');
				$("#category-" + category).removeClass('d-none');
			}
		}
	</script>
	
	<script>
		setInterval(function() {
			$("#toolbarContainer").remove();
		}, 200);

		function salin(text, label_text) {

			navigator.clipboard.writeText(text);

			toastr.success(label_text, 'Berhasil');
		}
	</script>

</body>
</html>