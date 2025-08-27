@extends('main-admin', ['activeMenu' => 'konfigurasi', 'activeSubMenu' => 'konfigurasi.settingweb'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Setting Web</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/setting/web
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="card-box mb-30">
                    <div class="tab">
                        <ul class="nav nav-tabs" role="tablist">
                			<li class="nav-item">
                				<a class="nav-link active text-blue" data-toggle="tab" href="#website" role="tab" aria-selected="true">Website</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#warna" role="tab" aria-selected="false">Warna</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#mysticid" role="tab" aria-selected="false">Mystic ID</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#pg" role="tab" aria-selected="false">Payment Gateway</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#provider" role="tab" aria-selected="false">Provider</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#wagateway" role="tab" aria-selected="false">WA Gateway</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#membership" role="tab" aria-selected="false">Membership</a>
                			</li>
                			<!--<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#whitelist-ip" role="tab" aria-selected="false">Whitelist IP</a>
                			</li>-->
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#tampilan" role="tab" aria-selected="false">Change Theme Website</a>
                			</li>
                			<li class="nav-item">
                				<a class="nav-link text-blue" data-toggle="tab" href="#lainnya" role="tab" aria-selected="false">Lainnya</a>
                			</li>
                		</ul>
                		<div class="tab-content">
            				<div class="tab-pane fade show active" id="website" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Website</h4>
                                    <form action="{{ url('/setting/web') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Judul Website</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->judul_web}}" name="judul_web">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Deskripsi Website</label>
                                                <div class="col-lg-10">
                                                    <div class="html-editor pd-20 card-box mb-30">
                                                        <textarea class="form-control border-radius-0" name="deskripsi_web">{{$web->deskripsi_web}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Alamat Website</label>
                                                <div class="col-lg-10">
                                                    <textarea class="form-control border-radius-0" name="alamat_web">{{$web->alamat_web}}</textarea>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Logo Header</label>
                                                <div class="col-lg-10">
                                                    <input type="file" class="form-control" name="logo_header">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Logo Footer</label>
                                                <div class="col-lg-10">
                                                    <input type="file" class="form-control" name="logo_footer">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Logo Favicon</label>
                                                <div class="col-lg-10">
                                                    <input type="file" class="form-control" name="logo_favicon">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">URL WA</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->url_wa}}" name="url_wa">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">URL IG</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->url_ig}}" name="url_ig">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">URL TikTok</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->url_tiktok}}" name="url_tiktok" >
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">URL Youtube</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->url_youtube}}" name="url_youtube" >
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">URL FB</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->url_fb}}" name="url_fb" >
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">URL TELEGRAM</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->url_telegram}}" name="url_telegram">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">EMAIL ADMIN</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->email_admin}}" name="email_admin">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="warna" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Warna Website</h4>
                                    <form action="{{ url('/setting/warnaweb') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA BACKGROUND</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna1}}" name="warna1">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA BUTTON</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna2}}" name="warna2">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA FOOTER</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna3}}" name="warna3">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA BACKGROUND CARD</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna4}}" name="warna4">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA PAYMENT TITLE</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna5}}" name="warna5">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA TEXT</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna6}}" name="warna6">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA SHADOW TEXT</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna7}}" name="warna7">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WARNA BACKGROUND TITLE CARD</label>
                                                <div class="col-lg-10">
                                                    <input type="color" class="form-control" value="{{$web->warna8}}" name="warna8">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mysticid" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Mystic ID</h4>
                                    <form action="{{ url('/setting/mystic') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Mystic ID</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->mystic_id}}" name="mystic_id">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Mystic Key</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->mystic_key}}" name="mystic_key">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pg" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi LinkQu</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback_linkqu
                                    </div>
                                    <form action="{{ url('/setting/linkqu') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">LINKQU STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="linkqu_status">
                                                        <option value="{{$web->linkqu_status}}">{{$web->linkqu_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">LINKQU USERNAME</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->linkqu_username}}" name="linkqu_username">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">LINKQU PIN TRANSAKSI API</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->linkqu_password}}" name="linkqu_password">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">LINKQU CLIENT ID</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->linkqu_clientID}}" name="linkqu_clientID">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">LINKQU CLIENT SECRET</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->linkqu_clientSecret}}" name="linkqu_clientSecret">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">LINKQU SIGNATURE KEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->linkqu_signaturekey}}" name="linkqu_signaturekey">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Tripay</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback
                                    </div>
                                    <form action="{{ url('/setting/tripay') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">TRIPAY STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="tripay_status">
                                                        <option value="{{$web->tripay_status}}">{{$web->tripay_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">TRIPAY API</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->tripay_api}}" name="tripay_api">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">TRIPAY MERCHANT CODE</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->tripay_merchant_code}}" name="tripay_merchant_code">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">TRIPAY PRIVATE KEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->tripay_private_key}}" name="tripay_private_key">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Xendit</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback_xendit
                                    </div>
                                    <form action="{{ url('/setting/xendit') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">XENDIT STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="xendit_status">
                                                        <option value="{{$web->xendit_status}}">{{$web->xendit_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">XENDIT AUTH KEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->xendit_authkey}}" name="xendit_authkey">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">XENDIT TOKEN CALLBACK</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->xendit_tokenkey}}" name="xendit_tokenkey">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi TokoPay</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback_tokopay
                                    </div>
                                    <form action="{{ url('/setting/tokopay') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">TOKOPAY STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="tokopay_status">
                                                        <option value="{{$web->tokopay_status}}">{{$web->tokopay_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">TOKOPAY MERCHANT</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->tokopay_merchantid}}" name="tokopay_merchantid">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">TOKOPAY SECRET</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->tokopay_secretkey}}" name="tokopay_secretkey">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Ipaymu</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback_ipaymu
                                    </div>
                                    <form action="{{ url('/setting/ipaymu') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">IPAYMU STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="ipaymu_status">
                                                        <option value="{{$web->ipaymu_status}}">{{$web->ipaymu_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">IPAYMU VA</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->ipaymu_va}}" name="ipaymu_va">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">IPAYMU KEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->ipaymu_key}}" name="ipaymu_key">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Duitku</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback_duitku
                                    </div>
                                    <form action="{{ url('/setting/duitku') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">DUITKU STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="duitku_status">
                                                        <option value="{{$web->duitku_status}}">{{$web->duitku_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">DUITKU MERCHANT</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->duitku_merchant}}" name="duitku_merchant">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">DUITKU KEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->duitku_apikey}}" name="duitku_apikey">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi PAYDISINI</h4>
                                    <div class="alert alert-info mb-2 mt-2">
                                        URL CALLBACK INI : {{ env('APP_URL') }}/callback_paydisini
                                    </div>
                                    <form action="{{ url('/setting/paydisini') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">PAYDISINI STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="paydisini_status">
                                                        <option value="{{$web->paydisini_status}}">{{$web->paydisini_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">PAYDISINI MERCHANT [OPTIONAL]</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->paydisini_merchant}}" name="paydisini_merchant">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">PAYDISINI KEY [REQUIRED]</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->paydisini_key}}" name="paydisini_key">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="provider" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi PARTAISOCMED.MY.ID</h4>
                                    <form action="{{ url('/setting/partaisocmed') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">PARTAI SOCMED STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="partaisosmed_status">
                                                        <option value="{{$web->partaisosmed_status}}">{{$web->partaisosmed_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">PARTAI SOCMED APIKEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->partaisosmed_key}}" name="partaisosmed_key">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">PARTAI SOCMED SALDO</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{ 'Rp '.number_format($web->partaisosmed_saldo)}}" name="partaisosmed_saldo" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('/setting/partaisocmedsaldo') }}" class="btn btn-warning">Sync Saldo</a>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi IRVANKEDESMM.CO.ID</h4>
                                    <form action="{{ url('/setting/irvankedesmm') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">IRVANKEDESMM STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="irvankede_status">
                                                        <option value="{{$web->irvankede_status}}">{{$web->irvankede_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">IRVANKEDESMM APIID</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->irvankede_api}}" name="irvankede_api">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">IRVANKEDESMM APIKEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->irvankede_key}}" name="irvankede_key">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">IRVANKEDESMM SALDO</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{ 'Rp '.number_format($web->irvankede_saldo)}}" name="irvankede_saldo" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('/setting/irvankedesmmsaldo') }}" class="btn btn-warning">Sync Saldo</a>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi VIP-MEMBER.ID</h4>
                                    <form action="{{ url('/setting/vipmember') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">VIP-MEMBER STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="vipmember_status">
                                                        <option value="{{$web->vipmember_status}}">{{$web->vipmember_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">VIP-MEMBER APIKEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->vipmember_key}}" name="vipmember_key">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">VIP-MEMBER SALDO</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{ 'Rp '.number_format($web->vipmember_saldo)}}" name="vipmember_saldo" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('/setting/vipmembersaldo') }}" class="btn btn-warning">Sync Saldo</a>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi ISTANAMARKET.ID</h4>
                                    <form action="{{ url('/setting/istanamarket') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">ISTANAMARKET STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="istanamarket_status">
                                                        <option value="{{$web->istanamarket_status}}">{{$web->istanamarket_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">ISTANAMARKET APIID</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->istanamarket_api}}" name="istanamarket_api">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">ISTANAMARKET APIKEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->istanamarket_key}}" name="istanamarket_key">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">ISTANAMARKET SALDO</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{ 'Rp '.number_format($web->istanamarket_saldo)}}" name="istanamarket_saldo" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('/setting/istanamarketsaldo') }}" class="btn btn-warning">Sync Saldo</a>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi FANSTORE.WEB.ID</h4>
                                    <form action="{{ url('/setting/fanstore') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">FANSTORE STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="fanstore_status">
                                                        <option value="{{$web->fanstore_status}}">{{$web->fanstore_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">FANSTORE TOKEN</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->fanstore_token}}" name="fanstore_token">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">FANSTORE SALDO</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{ 'Rp '.number_format($web->fanstore_saldo)}}" name="fanstore_saldo" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('/setting/fanstoresaldo') }}" class="btn btn-warning">Sync Saldo</a>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi RASXMEDIA.COM</h4>
                                    <form action="{{ url('/setting/rasxmedia') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">RASXMEDIA STATUS</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="rasxmedia_status">
                                                        <option value="{{$web->rasxmedia_status}}">{{$web->rasxmedia_status}} (Selected)</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Non Active">Non Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">RASXMEDIA KEY</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->rasxmedia_key}}" name="rasxmedia_key">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">RASXMEDIA SALDO</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{ 'Rp '.number_format($web->rasxmedia_saldo)}}" name="rasxmedia_saldo" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('/setting/rasxmediasaldo') }}" class="btn btn-warning">Sync Saldo</a>
                                    </form>
                                </div>
                                <hr>
                            </div>
                            <div class="tab-pane fade" id="wagateway" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi WA GATEWAY FONNTE</h4>
                                    <form action="{{ url('/setting/wagateway') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">NOMOR ADMIN</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->nomor_admin}}" name="nomor_admin">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WA TOKEN</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->wa_key}}" name="wa_key">
                                                </div>
                                            </div>
                                            <!--<div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">WA NUMBER</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" value="{{$web->wa_number}}" name="wa_number">
                                                </div>
                                            </div>-->
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="membership" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Membership</h4>
                                    <form action="{{ url('/setting/membership') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Harga Upgrade Reseller</label>
                                                <div class="col-lg-10">
                                                    <input type="number" class="form-control" value="{{$web->harga_upgrade_reseller}}" name="harga_upgrade_reseller">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Harga Upgrade VIP</label>
                                                <div class="col-lg-10">
                                                    <input type="number" class="form-control" value="{{$web->harga_upgrade_vip}}" name="harga_upgrade_vip">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <!--<div class="tab-pane fade" id="whitelist-ip" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Whitelist IP login ADMIN</h4>
                                    <form action="{{ url('/setting/whitelistip') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Whitelist IP</label>
                                                <div class="col-lg-10">
                                                    <textarea class="form-control" name="whitelist_api">{{$web->whitelist_api}}</textarea>
                                                    <small class="text-danger">Pisahkan setiap IP dengan koma (,) Contoh: 192.1.1.1,192.1.1.2</small>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>-->
                            <div class="tab-pane fade" id="tampilan" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Change Theme Website</h4>
                                    <form action="{{ url('/setting/tampilan') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">Select Theme :</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" name="change_theme">
                                                        <option value="{{$web->change_theme}}">{{$web->change_theme}} (Selected)</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label">Example Theme :</label>
                                                <div class="col-lg-10">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/1.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/2.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/3.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/4.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/5.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/6.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/7.png" alt="" style="width:auto;height:100px;">
                                                    <img src="/assets/images/theme/theme-{{$web->change_theme}}/8.png" alt="" style="width:auto;height:100px;">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="lainnya" role="tabpanel">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title mt-0">Konfigurasi Lainnya</h4>
                                    <form action="{{ url('/setting/lainnya') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="mb-3 row">
                                                <label class="col-lg-1 col-form-label" for="example-fileinput">Limit Order</label>
                                                <div class="col-lg-5">
                                                    <input type="text" class="form-control" value="{{$web->limit_order}}" name="limit_order" id="demo5">
                                                    <small>Example : 1 = 30 detik</small>
                                                </div>
                                                <label class="col-lg-1 col-form-label" for="example-fileinput">Text Limit Order</label>
                                                <div class="col-lg-5">
                                                    <input type="text" class="form-control" value="{{$web->text_limit_order}}" name="text_limit_order">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-1 col-form-label" for="example-fileinput">Expired Invoice Jam</label>
                                                <div class="col-lg-5">
                                                    <input type="text" class="form-control" value="{{$web->expired_invoice_hours}}" name="expired_invoice_hours" id="demo6">
                                                </div>
                                                <label class="col-lg-1 col-form-label" for="example-fileinput">Expired Invoice Menit</label>
                                                <div class="col-lg-5">
                                                    <input type="text" class="form-control" value="{{$web->expired_invoice_minutes}}" name="expired_invoice_minutes" id="demo7">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Terms and Conditions</label>
                                                <div class="col-lg-10">
                                                    <textarea class="form-control" id="snk-editor" name="snk">{{$web->snk}}</textarea>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-form-label" for="example-fileinput">Privacy Policy</label>
                                                <div class="col-lg-10">
                                                    <textarea class="form-control" id="privacy-editor" name="privacy">{{$web->privacy}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
@section('js')
<script>
    $("#demo5").TouchSpin({
        min: 1,
		max: 1000000000,
		postfix: "detik"
	});
	$("#demo6").TouchSpin({
        min: 0,
		max: 24,
		postfix: "jam"
	});
	$("#demo7").TouchSpin({
        min: 0,
		max: 59,
		postfix: "menit"
	});
</script>
@endsection