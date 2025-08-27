@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.flashsale'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Konfigurasi Flash Sale</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/flashsale
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
                        <h4 class="mb-3 header-title mt-0">Tambah Flashsale</h4>
                        <form action="{{ route('flashsale.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Nama Flashsale</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama">
                                    @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Kategori Produk</label>
                                <div class="col-lg-10">
                                    <select class="form-control" onchange="get_layanan(this.value)" name="kategori_id">
                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                        @foreach ($kategoris as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Layanan Produk</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="layanan_id" id="layanan" onchange="get_harga(this.value)">
                                        <option value="" selected disabled>Pilih Kategori Terlebih Dahulu</option>
                                    </select>
                                </div>
                            </div>
                
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Harga Public</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" id="harga_normal" readonly>
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Harga Member</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" id="harga_member" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="" class="col-lg-1 col-form-label">Harga Reseller</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" id="harga_reseller" readonly>
                                </div>
                                <label for="" class="col-lg-1 col-form-label">Harga VIP</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" id="harga_vip" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Harga Flashsale</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control @error('harga_promo') is-invalid @enderror" value="{{ old('harga_promo') }}" name="harga_promo">
                                    @error('harga_promo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <label class="col-lg-1 col-form-label" for="example-fileinput">Expired Flashsale</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control datetimepicker @error('expired_flash_sale') is-invalid @enderror" value="{{ old('expired_flash_sale') }}" name="expired_flash_sale" placeholder="Choose Date and time">
                                    @error('expired_flash_sale')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div> 
                            
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Thumbnail Flashsale</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="thumbnail">
                                    @error('thumbnail')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-30">
                            <div class="card-body">
                                <h4 class="header-title mt-0 mb-1">Semua Flashsale</h4>
                                <div class="pb-20">
                                    <table class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th class="table-plus datatable-nosort">#</th>
                                                <th>Tanggal</th>
                                                <th>Nama</th>
                                                <th>Url</th>
                                                <th>Kategori Produk</th>
                                                <th>Layanan Produk</th>
                                                <th>Harga Normal Public</th>
                                                <th>Harga Normal Member</th>
                                                <th>Harga Normal Reseller</th>
                                                <th>Harga Normal VIP</th>
                                                <th>Harga Flashsale</th>
                                                <th>Thumbnail</th>
                                                <th>Expired</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=1;?>
                                            @foreach( $data as $datas )
                                            <?php 
                                            $layanan = DB::table('layanans')->where('id', $datas->id_layanan)->first(); 
                                            $kategori = DB::table('kategoris')->where('id', $datas->id_kategori)->first(); 
                                            ?>
                                            @php
                                            $label_pesanan = '';
                                            if($datas->status == "active"){
                                            $label_pesanan = 'info';
                                            }else if($datas->status == "unactive"){
                                            $label_pesanan = 'warning';
                                            }
                                            $ymdhis = explode(' ',$datas->created_at);
                                            $month = [
                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                'Juli','Agustus','September','Oktober','November','Desember'
                                            ];
                                            $explode = explode('-', $ymdhis[0]);
                                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                                            
                                            $ymdhis2 = explode(' ',$datas->expired_flash_sale);
                                            $month2 = [
                                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                                'Juli','Agustus','September','Oktober','November','Desember'
                                            ];
                                            $explode2 = explode('-', $ymdhis2[0]);
                                            $formatted2 = $explode2[2].' '.$month2[(int)$explode2[1]].' '.$explode2[0];
                                            
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $no }}</th>
                                                <td>{{ $formatted.' '.substr($ymdhis[1],0,5).'' }}</td>
                                                <td>{{ $datas->nama }}</td>
                                                <td>{{ $datas->url }}</td>
                                                <td>{{ $kategori->nama }}</td>
                                                <td>{{ $layanan->layanan }}</td>
                                                <td>{{ $layanan->harga }}</td>
                                                <td>{{ $layanan->harga_member }}</td>
                                                <td>{{ $layanan->harga_reseller }}</td>
                                                <td>{{ $layanan->harga_vip }}</td>
                                                <td>{{ $datas->harga_promo }}</td>
                                                <td><img src="{{env('APP_URL')}}/{{$datas->thumbnail}}" alt="" style="width:auto;height:50px;"></td>
                                                <td>{{ $formatted2.' '.substr($ymdhis2[1],0,5).'' }}</td>
                                                <td>
                                                    <div class="dropdown">
            											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            												<i class="dw dw-more"></i>
            											</a>
            											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            												<a class="dropdown-item" href="javascript:;" onclick="modal_open('edit', '{{ route('flashsale.detail', [$datas->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
            												<a class="dropdown-item" href="/flashsale/hapus/{{ $datas->id }}"><i class="dw dw-delete-3"></i> Delete</a>
            											</div>
            										</div>
                                                </td>
                                            </tr>
                                            <?php $no++ ;?>
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
    function get_harga(layanan_id) {
        let harga_normal = $('#harga_normal')
        let harga_member = $('#harga_member')
        let harga_reseller = $('#harga_reseller')
        let harga_vip = $('#harga_vip')
        $.ajax({
            url: "{{ route('paket-layanan.get-harga') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                layanan_id: layanan_id
            },
            beforeSend: function() {
                harga_normal.val('0');
                harga_member.val('0');
                harga_reseller.val('0');
                harga_vip.val('0');
            },
            success: function(response) {
                var data = response.data;
                harga_normal.empty();
                harga_member.empty();
                harga_reseller.empty();
                harga_vip.empty();
                $.each(data, function(index, item) {
                    $('#harga_normal').val(item.harga);
                    $('#harga_member').val(item.harga_member);
                    $('#harga_reseller').val(item.harga_reseller);
                    $('#harga_vip').val(item.harga_vip);
                });
            },
            error: function(response) {
                let res = JSON.parse(response.responseText)
                $('#harga_normal').val('0');
                $('#harga_member').val('0');
                $('#harga_reseller').val('0');
                $('#harga_vip').val('0');
            }
        });
    }
    function get_layanan(kategori_id) {
        let layanan = $('#layanan')
        $.ajax({
            url: "{{ route('paket-layanan.get-layanan') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                kategori_id: kategori_id
            },
            beforeSend: function() {
                layanan.html('<option value="">Mengambil Layanan...</option>');
            },
            success: function(response) {
                var data = response.data;
                layanan.empty();
                layanan.html('<option value="" selected disabled>- Select One -</option>');
                $.each(data, function(index, item) {
                    layanan.append('<option value="' + item.id + '">' + item.layanan +
                        '</option>');
                });
            },
            error: function(response) {
                let res = JSON.parse(response.responseText)
                layanan.html('<option value="">' + res.message + '</option>');
            }
        });
    }
</script>

@endsection