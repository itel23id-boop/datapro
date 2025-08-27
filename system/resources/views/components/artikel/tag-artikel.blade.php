@extends('../../main', ['activeMenu' => 'artikel', 'activeSubMenu' => ''])
@section('content')
<style>
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
        .artikel-category {
	        padding-top: 6px;
	        overflow-x: auto;
            white-space: nowrap;
            flex-wrap: nowrap;
            margin-bottom: 18px;
	    }
	    .artikel-category a {
            padding: 2px 14px;
            display: inline-block;
            border-radius: 120px;
            text-decoration: none;
            border: 1px solid #9d9d9d;
            color: #e1e1e1;
            margin-right: 8px;
            white-space: nowrap;
	    }
	    .artikel-category a.active, .artikel-category a:hover {
	        border-color: transparent;
	        background: var(--warna);
	        color: #333;
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
	    .artikel-detail img {
	        max-width: 690px !important;
	    }
	    .img-ads {
	        width: 100%;
	        max-width: 642px;
	    }
	    .tags-item {
	        border: 1px solid #b5b5b5;
            padding: 1px 11px;
            display: inline-block;
            border-radius: 120px;
            margin-right: 6px;
	    }
	    .breadcrumb a {
	        text-decoration: none;
	        color: #a3a3a3;
	    }
	    .breadcrumb-item+.breadcrumb-item::before {
	        color: #a3a3a3;
	    }
	    .breadcrumb-item.active {
	        color: var(--warna);
	    }
	    .a2a_svg {
	        background-color: transparent !important;
            border: 1px solid #fff;
            border-radius: 50% !important;
            padding: 3px !important;
	    }
	    .artikel-banner-card {
            border-radius: 9px;
            min-height: 460px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .artile-banner-card-body{
            padding: 266px 316px 12px 73px;
            background: linear-gradient(8deg, #202020, transparent);
            border-radius: 9px;
            min-height: 460px;
        }
        #artikel-banner {
            margin-bottom: 30px;
        }
        .owl-nav button {
            position: absolute;
            top: 50%;
            background-color: #000;
            color: #fff;
            margin: 0;
            transition: all 0.3s ease-in-out;
        }
        .owl-nav button.owl-prev {
            left: 0;
        }
        .owl-nav button.owl-next {
            right: 0;
        }
        .owl-dots {
            text-align: center;
            padding-top: 0;
            margin-top: -46px;
            z-index: 2;
            position: relative;
        }
        .owl-dots button.owl-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            background: #ccc;
            margin: 0 3px;
        }
        .owl-dots button.owl-dot.active {
            background-color: var(--warna);
        }
        .owl-dots button.owl-dot:focus {
            outline: none;
        }
        .owl-nav button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.38) !important;
        }
        .owl-nav button:focus {
            outline: none;
        }
        .owl-nav {
            display: none;
        }
</style>
<style>
    /* Mobile */
	    @media screen and (max-width: 768px) {
	        #artikel-banner {
	            display: none;
	        }
	        #artikel-banner-mobile {
	            display: block;
	            margin-bottom: 22px;
	        }
	        .menu-right {
                float: right;
                margin-top: -53px;
            }
            .menu-right .dropdown-menu.show {
                transform: translate(36px,60px) !important;
            }
            .artikel-detail img {
                width: 100% !important;
            }
            .d-none-mobile {
	            display: none;
	        }
	        .artikel-item td img {
	            width: 100px !important;
	        }
	        .owl-dots {
	            margin-top: 8px;
	        }
	    }
	    
	    /* Desktop */
	    @media screen and (min-width: 768px) {
	        #artikel-banner-mobile {
	            display: none;
	        }
	        #artikel-banner {
	            display: block;
	        }
	    }
	    
	    /* Mobile */
	    @media screen and (max-width: 768px) {
	        .menu-right .dropdown {
                width: 100% !important;
	        }
	    }
</style>
<style>
	    .group-method-header {
	        background: #30231b !important;
	    }
	    .accordion-item {
	        background: transparent;
	    }
	    .img-white {
	        filter: brightness(0) invert(1);
	    }
        .group-method-footer, .group-method-body {
            background: #3e2e24 !important;
        }
	    body.light .content-main, body.light h1, body.light h2, body.light h3, body.light h4, body.light h5, body.light h6, body.light p, body.light .method h6, body.light .product h6, body.light .card-title, body.light .form-control, body.light .category ul li div, body.light .games a, body.light body.light .list-style-none.p-0.m-0 li, body.light .text-white.text-decoration-none.me-2 {
            color: #444 !important;
        }
        body.light .card {
            background: #fff;
        }
        body.light #modal-popup .modal-content {
            background: #fff;
            color: #333;
        }
        body.light {
            background: #ecf0f1;
        }
        body.light .btn-primary {
            color: #333 !important;
        }
        body.light .box-icon {
            color: #fff;
        }
        body.light .method.active h6, body.light .method.active span, body.light.product.active h6 {
            color: #333;
        }
        body.light .group-method-header {
            background: var(--warna) !important;
        }
        body.light .group-method-footer, body.light .group-method-body {
            background: #e6ecef !important;
        }
        body.light hr {
            border-color: #757878 !important;
        }
        body.light .accordion-button {
            color: #444 !important;
        }
        body.light table tr th, body.light table tr td {
            color: #444;
            border-color: #d1d1d1 !important;
        }
        body.light .footer {
            background: #fff;
        }
        body.light .footer a {
            color: #333 !important;
        }
        body.light .artikel-category a, body.light .tags-item, body.light .box-icon i {
            color: #333;
        }
        body.light .artile-banner-card-body h4 {
            color: #fff !important;
        }
        body.light .a2a_svg {
            background: var(--warna) !important;
        }
        body.light .stay-white, body.light .card-flash p {
            color: #fff !important;
        }
        body.light .img-white {
            filter: none;
        }
        body.light .modal button.text-white {
            color: #333 !important;
        }
        body.light .users-menu a {
            color: #333;
        }
        body.light .card-waves svg path {
            fill: #fff;
        }
        @media screen and (max-width: 768px) {
            .search {
                width: 66%;
            }
            .menu-right .dropdown {
                width: 32%;
                text-align: right;
            }
            .search button {
                padding-left: 24px;
                padding-right: 24px;
            }
        }
	</style>
<div class="content-main pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="artikel-category">
                    <a href="/artikel">Semua</a>
                    @foreach($category as $datas)
                    <a href="/artikel/category/<?php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $datas->category))) ?>">{{ $datas->category }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <h5 class="fw-600">Tags <span class="text-warning">{{ $tag_names }}</span></h5>
                <div class="row mb-5 d-none-mobile">
                    <!-- start foreach -->
                    @foreach($data as $datas)
                    @php
                    $excerpt_lengths = 30;
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
                    <div class="col-md-6 mb-3">
                        <a href="/artikel/{{$datas->url}}" class="artikel-item">
                            <img src="{{$datas->path}}" class="w-100 rounded d-block mb-3">
                            <span class="category mb-2">{{$datas->category}}</span>
                            <h6 class="fw-600 lh-26">{{$datas->title}}</h6>
                            <p class="mb-1 text-muted">{{ $excerpts }}...</p>
                            <div class="artikel-data">
                                <span class="author">{{$datas->author}}</span>
                                <span class="date"><span class="d-inline-block ms-1 me-2">•</span>{{ $formatted.' ('.substr($ymdhis[1],0,5).' WIB)' }}</span>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- start foreach -->
                        @foreach($data as $datas)
                        @php
                        $excerpt_lengths = 30;
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
                        <a href="/artikel/{{$datas->url}}" class="artikel-item mb-3">
                            <table class="w-100">
                                <tr>
                                    <td class="pe-3 pb-3" width="10" valign="top">
                                        <img src="{{$datas->path}}" class="rounded d-block" width="240">
                                    </td>
                                    <td>
                                        <span class="category mb-1">{{$datas->category}}</span>
                                        <h6 class="fw-600 lh-26">{{$datas->title}}</h6>
                                        <p class="mb-1 text-muted">{{ $excerpts }}...</p>
                                        <div class="artikel-data">
                                            <span class="author text-primary">{{$datas->author}}</span>
                                            <span class="date"><span class="d-inline-block ms-1 me-2">•</span>{{ $formatted.' ('.substr($ymdhis[1],0,5).' WIB)' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </a>
                        @endforeach
                        <!-- end foreach -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <form action="/artikel/search" method="GET">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control border-0" autocomplete="off" placeholder="Cari Berita" value>
                            <button class="btn btn-primary px-4" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
                <div class="mb-4">
                    <h5 class="fw-600">Tags Populer</h5>
                    <!-- start foreach -->
                    @foreach($tags as $datas)
                    <a href="/artikel/tags/{{$datas->name}}" class="text-decoration-none text-white">
                        <span class="tags-item mb-2">{{$datas->name}}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

@endsection