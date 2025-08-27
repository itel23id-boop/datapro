@extends('main-admin', ['activeMenu' => 'top-ranking', 'activeSubMenu' => ''])

@section('content')
<!-- start page title -->
            <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Top Ranking</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/top-ranking
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
                    <div class="col-md-6 col-xl-6">
                        <div class="card-box mb-30">
                            <div class="card-body">
                                <h3 class="card-title">Top 10 Pembeli Terbanyak</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                    <tr>
                                        <th>Peringkat</th>
                                        <th>Nama</th>
                                        <th>Total</th>
                                    </tr>
                                    @foreach ($totalOrders as $key => $totalOrder)
                                        @if ($key < 1)
                                        @continue
                                        @endif
                                        @if($totalOrder->status == "Success")
                                        <tr>
                                            <td>{{ $loop->iteration - 1 }}</td>
                                            <td>{{ $totalOrder->username ? $totalOrder->username : 'Guest'}}</td>
                                            <td>{{ $totalOrder->total_order }} Pesanan</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <div class="card-box mb-30">
                            <div class="card-body">
                                <h3 class="card-title">Top 10 Layanan Terlaris</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Peringkat</th>
                                            <th>Kategori</th>
                                            <th>Layanan</th>
                                            <th>Total</th>
                                        </tr>
                                        @foreach ($toplayanans as $key => $toplayanan)
                                        <?php
                                        $layanan = DB::table('layanans')->where('id',$toplayanan->id_layanan)->first(); 
                                        $kategori = DB::table('kategoris')->where('id',$layanan->kategori_id)->first(); 
                                        ?>
                                        <tr>
                                            <Td>{{ $loop->iteration }}</Td>
                                            <Td>{{ $kategori->nama }}</Td>
                                            <Td>{{ $toplayanan->layanan }}</Td>
                                            <Td>{{ $toplayanan->top_layanan }} Pesanan</Td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection