@extends('main-admin', ['activeMenu' => 'artikel', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Edit Artikel</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/artikel-admin/edit
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
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Edit Artikel</h4>
                        <form action="{{ route('artikel-admin.detail.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Kategori Artikel</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="{{ $data->category }}" name="kategori" placeholder="example: Mobile Legends">
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Judul Artikel</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="{{ $data->title }}" name="title" placeholder="">
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Konten Artikel</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" id="content" name="content">{{ $data->content }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Author Artikel</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="{{ $data->author }}" name="author" placeholder="">
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Tags Input</label>
                                <div class="col-lg-5">
                                    <input type="text" data-role="tagsinput" name="tags" class="form-control" value="{{$data->tags}}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Upload Banner</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="banner">
                                    <div class="mt-1">
                                        <img src="{!! $data->path !!}" alt="" style="width:auto;height:55px;">
                                    </div>
                                </div>
                            </div>
                            <div class="btn-list">
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                                <a href="{{ route('artikel-admin') }}" class="btn btn-danger w-100">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
<script type="text/javascript">

</script>
@endsection