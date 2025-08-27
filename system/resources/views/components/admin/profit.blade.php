@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.profit'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Setelan Profit</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/profit
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="card-body">
                                <h4 class="header-title mt-0 mb-1">Semua Profit Layanan Provider</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">#</th>
                                                <th>Provider</th>
                                                <th>Percent</th>
                                                <th>Profit</th>
                                                <th>Profit Member</th>
                                                <th>Profit Reseller</th>
                                                <th>Profit VIP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $datas as $data )
                                            <tr>
                                                <th scope="row">{{ $data->id }}</th>
                                                <td>{{ $data->provider }}</td>
                                                <td>{{ $data->percent }}</td>
                                                <td>{{ $data->profit }}</td>
                                                <td>{{ $data->profit_member }}</td>
                                                <td>{{ $data->profit_reseller }}</td>
                                                <td>{{ $data->profit_vip }}</td>
                                                <td>
                                                    <div class="dropdown">
                            							<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            								<i class="dw dw-more"></i>
                            							</a>
                            							<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                            								<a class="dropdown-item" href="javascript:;" onclick="modal_open('edit', '{{ route('profit.detail', [$data->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
                            							</div>
                            						</div>
                                                </td>
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