@extends('main-admin', ['activeMenu' => 'member', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Pengguna</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/member
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-6">
                    <div class="card-box mb-30">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <span class="text-muted text-uppercase fs-12 fw-bold">TOTAL SALDO PENGGUNA</span>
                                    <h3 class="mb-0">Rp. {{ number_format($total_balance, '0', '.', ',') }}</h3>
                                    <small>Dengan total {{ $banyak_user }} pengguna, {{ $banyak_admin }} Admin</small>
                                </div>
                                <div class="align-self-center flex-shrink-0">
                                    <span class="icon-lg icon-dual-primary" data-feather="shopping-bag"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="card-box mb-30">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Tambah Member</h4>
                        <form action="{{ route('member.post') }}" method="POST">
                            @csrf
                
                            <div class="mb-3 row">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Nama</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama">
                                    @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Email</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Username</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username">
                                    @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Password</label>
                                <div class="col-lg-5">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" name="password">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">No Whatsapp</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control @error('no_wa') is-invalid @enderror" value="{{ old('no_wa') }}" name="no_wa">
                                    @error('no_wa')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Role</label>
                                <div class="col-lg-5">
                                    <select class="form-control @error('role') is-invalid @enderror" name="role">
                                        <option value="Member">Member</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="VIP">VIP</option>
                                    </select>
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger">Buat Member</button>
                        </form>
                    </div>
                </div>
                <div class="card-box mb-30">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Kirim saldo</h4>
                        <form action="{{ route('saldo.post') }}" method="POST">
                            @csrf
                
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Username</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username">
                                    @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                
                            <div class="mb-3 row">
                                <label for="" class="col-lg-2 col-form-label">Jumlah</label>
                                <div class="col-lg-10">
                                    <input type="number" class="form-control @error('balance') is-invalid @enderror" value="{{ old('balance') }}" name="balance">
                                    @error('balance')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                
                            <button type="submit" class="btn btn-danger">Kirim</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mt-0 mb-1">Semua Pengguna</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">ID</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>No Whatsapp</th>
                                                <th>Saldo</th>
                                                <th>Level</th>
                                                <th>OTP</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Hapus</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $users as $user )
                                            @php
                                            if($user->status == 'Active') {
                                                $color = 'success';
                                            }elseif($user->status == 'Not Active') {
                                                $color = 'success';
                                            } else {
                                                $color = 'danger';
                                            }
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $user->id }}</th>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->username }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->no_wa }}</td>
                                                <td>Rp. {{ number_format($user->balance, 0, ',', '.') }}</td>
                                                <td>{{ $user->role }}</td>
                                                <td><small>{{ $user->otp == null ? 'OTP NOT FOUND FOR REGISTRATION OR FORGOT PASS' : $user->otp }}</small></td>
                                                <td><span class="badge badge-{{$color}}">{{ $user->status }}</span></td>
                                                <td>{{ $user->created_at }}</td>
                                                <td><a class="btn btn-danger" href="{{ route('member.delete',[$user->id]) }}">Hapus</a></td>
                                                <td><a href="javascript:;" onclick="modal_open('edit', '{{ route('member.detail', [$user->id]) }}')" class="btn btn-info"><i class="dw dw-edit2"></i> Edit</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                
                    </div>
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.data-table').DataTable({
            order: [[0, 'desc']],
    		scrollCollapse: true,
    		autoWidth: false,
    		responsive: true,
    		columnDefs: [{
    			targets: "datatable-nosort",
    			orderable: false,
    		}],
    		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    		"language": {
    			"info": "_START_-_END_ of _TOTAL_ entries",
    			searchPlaceholder: "Search",
    			paginate: {
    				next: '<i class="ion-chevron-right"></i>',
    				previous: '<i class="ion-chevron-left"></i>'  
    			}
    		},
    	});
    });
</script>
@endsection