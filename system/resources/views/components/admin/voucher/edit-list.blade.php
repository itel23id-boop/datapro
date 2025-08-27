@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.voucher'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Edit Voucher List</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/edit-voucher-list
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
                <div class="card-box mb-30">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Edit Voucher</h4>
                        <form action="{{ route('voucher.detail.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Username</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kode Voucher</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" value="{{ $data->kode }}" name="kode">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Limit</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control" value="{{ $data->limit }}" name="limit">
                                </div>
                            </div>
                            <div class="btn-list">
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                                <a href="{{ route('voucher') }}" class="btn btn-danger w-100">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
@endsection