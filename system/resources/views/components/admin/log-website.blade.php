@extends('main-admin', ['activeMenu' => 'log-website', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Setelan Log Website</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/log-website
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="tab">
                                <ul class="nav nav-tabs" role="tablist">
        							<li class="nav-item">
        								<a class="nav-link active text-blue" data-toggle="tab" href="#logOrder" role="tab" aria-selected="true">Log Order</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#logTopup" role="tab" aria-selected="true">Log Topup</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#logRegister" role="tab" aria-selected="true">Log Register</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#logLogin" role="tab" aria-selected="false">Log Login</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#logLogout" role="tab" aria-selected="false">Log Logout</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#logUser" role="tab" aria-selected="false">Log User</a>
        							</li>
        							<li class="nav-item">
        								<a class="nav-link text-blue" data-toggle="tab" href="#logSystem" role="tab" aria-selected="false">Log System</a>
        							</li>
        						</ul>
        						<div class="tab-content">
        							<div class="tab-pane fade show active" id="logOrder" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log Order</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logOrders">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Trx ID</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                							    @if($datas->type == "order")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                              <input type="text" id="order_id" value="{{ $datas->order_id }}" name="order_id" class="form-control" disabled>
                                                                              <div class="input-group-append">
                                                                                <a class="input-group-text" href="{{env('APP_URL')}}/pembelian/invoice/{{ $datas->order_id }}" target="blank_">
                                                                                  <i class="micon dw dw-copy"></i>
                                                                                </a>
                                                                              </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="logTopup" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log Topup</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logTopups">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Trx ID</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                							    @if($datas->type == "topup")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>{{ $datas->order_id }}</td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="logRegister" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log Register</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logRegisters">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                							    @if($datas->type == "register")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="logLogin" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log Login</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logLogins">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                							    @if($datas->type == "login")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="logLogout" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log Logout</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logLogouts">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                							    @if($datas->type == "logout")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="logUser" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log User</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logUsers">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                							    @if($datas->type == "user")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="logSystem" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card-box mb-30">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-1">Log System</h4>
                                                    <div class="pb-20">
                                                        <div class="table-responsive">
                                                            <table class="data-table table stripe hover nowrap" id="logsystems">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-plus datatable-nosort">ID</th>
                                                                        <th>Date</th>
                                                                        <th>Username</th>
                                                                        <th>Pesan</th>
                                                                        <th>IP</th>
                                                                        <th>Location</th>
                                                                        <th>Device</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($data as $datas)
                                                                @php
                                                                $ymdhis = explode(' ',$datas->created_at);
                                                                $month = [
                                                                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                                    'Juli','Agustus','September','Oktober','November','Desember'
                                                                ];
                                                                $explode = explode('-', $ymdhis[0]);
                                                                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                                                @endphp
                                                                    @if($datas->type == "system")
                                                                    <tr>
                                                                        <th scope="row">#{{ $datas->id }}</th>
                                                                        <td>
                                                                            {{ $formatted.' '.substr($ymdhis[1],0,5).'' }}
                                                                        </td>
                                                                        <td>{{ $datas->user ? $datas->user : 'Guest' }}</td>
                                                                        <td>
                                                                            {{ $datas->text }}
                                                                        </td>
                                                                        <td style="color:blue;">
                                                                            {{ $datas->ip }}
                                                                        </td>
                                                                        <td style="color:red;">
                                                                            {{ $datas->loc }}
                                                                        </td>
                                                                        <td style="color:green;">
                                                                            {{ ucwords($datas->ua) }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#logOrders").DataTable({
            order: [[1, 'desc']],
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
    $(document).ready(function(){
    	$("#logTopups").DataTable({
            order: [[1, 'desc']],
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
    $(document).ready(function(){
    	$("#logRegisters").DataTable({
            order: [[1, 'desc']],
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
    $(document).ready(function(){
    	$("#logLogins").DataTable({
            order: [[1, 'desc']],
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
    $(document).ready(function(){
    	$("#logLogouts").DataTable({
            order: [[1, 'desc']],
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
    $(document).ready(function(){
    	$("#logUsers").DataTable({
            order: [[1, 'desc']],
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
    $(document).ready(function(){
    	$("#logsystems").DataTable({
            order: [[1, 'desc']],
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