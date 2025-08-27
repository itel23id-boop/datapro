@extends('../../main')

@section('css')

@endsection

@section('content')
    <div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card mb-4 d-none-print border-0 shadow-form">
						<div class="card-body">
							<h6 class="shadow-text card-title">Cek Pesanan</h6>
							<form action="{{ route('cari.post') }}" method="post">
							    @csrf
								<div class="mb-3">
									<div class="input-group">
										<input type="text" class="form-control form-control-games" placeholder="Order ID Pesanan" autocomplete="off" value="" name="id" id="id">
										<button class="btn btn-primary">Cek</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					@if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @elseif(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
					<div class="card mb-4 d-none-print border-0 shadow-form">
						<div class="card-body">
							<h6 class="shadow-text card-title">10 Pembelian terakhir</h6>
							<div class="table-responsive">
							    <table class="table">
							        <thead>
    							        <tr>
    							            <td>Tanggal</td>
    							            <td>Invoice</td>
    							            <td>Quantity</td>
    							            <td>Start Count</td>
    							            <td>Remain</td>
    							            <td>Harga</td>
    							            <td>Status</td>
    							        </tr>
							        </thead>
							        <tbody id="history_transaksi">
                                		@foreach($data as $d)
                                        @php
                                        
                                        $ymdhis = explode(' ',$d->created_at);
                                        $month = [
                                            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                            'Juli','Agustus','September','Oktober','November','Desember'
                                        ];
                                        $explode = explode('-', $ymdhis[0]);
                                        $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                        
                                        $label_pesanan = '';
                                        if($d->status == "Pending"){
                                            $label_pesanan = 'warning';
                                        }else if($d->status == "Processing"){
                                            $label_pesanan = 'info';
                                        }else if($d->status == "Success"){
                                            $label_pesanan = 'success';
                                        }else if($d->status == "Refund"){
                                            $label_pesanan = 'refund';
                                        }else{
                                            $label_pesanan = 'danger';
                                        }
                                        @endphp
    							        <tr>
    							            <td nowrap>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
    							            <td>FT{{ substr($d->order_id,0,-30).'*******'.substr($d->order_id, -3)}}</td>
    							            <td>{{$d->quantity}}</td>
    							            <td>{{$d->count}}</td>
    							            <td>{{$d->remain}}</td>
    							            <td nowrap>Rp. {{number_format($d->harga,0,'.','.')}}</td>
    							            <td><span class="badge bg-{{ $label_pesanan }}">{{$d->status}}</span></td>
    							        </tr>
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
@endsection
@section('js')
<script>
    function refreshHistory() {
        $.get("{{ route('cari.get-history') }}", function(data) {
            $("#history_transaksi").html(data);
        });
    }setInterval(refreshHistory, 5000);
    function refreshHistorys() {$.get('{{ENV("APP_URL")}}/updatepesanan');}setInterval(refreshHistorys, 1000);
</script>
@endsection