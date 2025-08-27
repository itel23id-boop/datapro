@extends('main-admin', ['activeMenu' => 'laporan', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
            <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Laporan Pesanan</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/laporan
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
                        <h3 class="card-title">Order Report Chart</h3>
                        <form method="GET">
                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label>From</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ $filter_date1 }}">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>To</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ $filter_date2 }}">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Submit</label>
                                    <button type="submit" class="btn btn-block btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="border:1px solid #dee2e6;">
                                <thead>
                                    <tr>
                                        <th>Order Total</th>
                                        <th>Gross Income</th>
                                        <th>Net income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ count($cnt) }}</td>
                                        <td>{{ number_format($tot) }}</td>
                                        <td>{{ number_format($prf) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><hr>
                        <div id="line-chart"></div>
                    </div>
                </div>
<script type="text/javascript">
    $(function () {
        "use strict";
        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: [<? for($i = 0; $i <= $formater->countDay($filter_date1,$filter_date2); $i++) { 
                    print '{
                        w: \''.date('Y-m-d', strtotime("-$i days", strtotime(date('Y-m-d')))).'\', 
                        y: '.count(DB::table('pembelians')->where('status', 'Success')->orwhere('created_at', date('Y-m-d', strtotime("-$i days", strtotime(date('Y-m-d')))).'%')->get()).'},'; 
                } ?>],
            xkey: 'w',
            ykeys: ['y'],
            labels: ['Transaksi'],
            lineColors: ['#1576c2'],
            hideHover: 'auto'
        });
    });
</script>
@endsection