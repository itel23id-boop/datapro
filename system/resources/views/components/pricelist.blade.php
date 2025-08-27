@extends("main")

@section("content")
	<div class="{{ $config->change_theme == '2' ? 'content-mains' : 'content-main mt-5' }}">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<h4 class="shadow-text fw-700 mb-3">Daftar Harga</h4>
					<div class="card mb-4 border-0 shadow-form">
						<div class="card-body">
						    <div class="row mb-3">
						        <div class="col-md-4">
						            <select class="form-control form-control-games" onchange="load_product(this.value);">
						                <option class="text-dark" value="{{ $id == null ? '0' : $id->id }}" {{ $id == null ? '' : 'selected' }}>{{ $id == null ? 'Pilih Games…' : $id->nama.' (Selected)' }}</option>
						                @foreach($kategoris as $k)
						                <option class="text-dark" value="{{ $k->id }}" >{{ $k->nama }}</option>
						                @endforeach
						            </select>
						        </div>
						    </div>
						    <div class="table-responsive">
						        <table class="table" id="datatable">
    						        <thead>
    						            <tr>
    						                <th>Kategori</th>
    						                <th>Layanan</th>
    						                <th>Harga Tamu</th>
    						                <th>Harga Member</th>
    						                <th>Harga Reseller</th>
    						                <th>Harga VIP</th>
    						                <th>Status</th>
    						            </tr>
    						        </thead>
    						        <tbody>
    						        @foreach($datas as $data)
                                    @php
                                        if($data->status_layanan == "available"){
                                            $label_pesanan = "success";
                                        }else{
                                            $label_pesanan = "danger";
                                        }
                                    @endphp
    								    <tr>
    									    <td>{{ $data->nama_kategori }}</td>
    										<td>{{ $data->layanan }}</td>
    										<td>Rp. {{ number_format($data->harga,0,',','.') }}</td>
    										<td>Rp. {{ number_format($data->harga_member,0,',','.') }}</td>
    										<td>Rp. {{ number_format($data->harga_reseller,0,',','.') }}</td>
    										<td>Rp. {{ number_format($data->harga_vip,0,',','.') }}</td>
    										<td><span class="badge bg-{{ $label_pesanan }}">{{ $data->status_layanan }}</span></td>
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
	function go_section(id) {
		$('html, body').animate({
		    scrollTop: $("#games-" + id).offset().top
		}, 400);
	}
		
	function load_product(id) {
	    window.location.href = '{{ env("APP_URL") }}/daftar-harga/' + id;
	}
</script>
@endsection