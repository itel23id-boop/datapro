@extends('../../main')

@section('css')
<style>
.min-w-full {
    min-width: 100%;
}

table {
    text-indent: 0;
    border-color: inherit;
    border-collapse: collapse;
}
.leading-5 {
    line-height: 1.25rem;
}
.rounded-sm {
    border-radius: 0.125rem;
}
.text-emerald-900 {
    --tw-text-opacity: 1;
    color: rgb(6 78 59 / var(--tw-text-opacity));
}
.py-3\.5 {
    padding-top: 0.875rem;
    padding-bottom: 0.875rem;
}
.text-left {
    text-align: left;
}
.first\:table-cell:first-child {
    display: table-cell;
}
.first\:pl-4:first-child {
    padding-left: 1rem;
}
.first\:pr-4:first-child {
    padding-right: 1rem;
}
.first\:text-left:first-child {
    text-align: left;
}
.last\:relative:last-child {
    position: relative;
}
.last\:table-cell:last-child {
    display: table-cell;
}
.last\:text-right:last-child {
    text-align: right;
}
.btn:disabled{background:#8ba4b1;border-color:#8ba4b1}
    
    .even\:bg-murky-700\/50:nth-child(2n) tr {
    background-color: rgba(61,67,72,.5);
}

tr {
    background-color: rgba(61,67,72,.5);
}
.emerald-200 {
    background-color: #38b2ac; 
    color: white;
}

.yellow-300 {
    background-color: #f6e05e; 
    color: black;
}

.sky-600 {
    background-color: #3182ce; 
    color: white; 
}

.rose-300 {
    background-color: #fc8181; 
    color: white; 
}
.bg-emerald-200 {
    --tw-bg-opacity: 1;
    background-color: rgb(167 243 208 / var(--tw-bg-opacity));
}
.bg-yellow-300 {
    --tw-bg-opacity: 1;
    background-color: rgb(253 224 71 / var(--tw-bg-opacity));
}
.bg-sky-600 {
    --tw-bg-opacity: 1;
    background-color: rgb(2 132 199 / var(--tw-bg-opacity));
}
.bg-rose-300 {
    --tw-bg-opacity: 1;
    background-color: rgb(253 164 175 / var(--tw-bg-opacity));
}
</style>
@endsection

@section('content')
<main class="relative">
    <section id="history" class="relative">
        <div class="space-y-12 pt-5">
        	<div class="relative overflow-hidden bg-murky-900 shadow-2xl">
        		<form action="{{url('/cari')}}" method="POST" class="container relative z-20 py-12 text-left">
        		     @csrf
        			<h2 class="max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">Lacak pesanan kamu dengan nomor invoice!</h2>
        			<p class="mt-6 max-w-3xl">
        				Pesanan Kamu tidak terdaftar meskipun Kamu yakin telah memesan? Harap tunggu 1-5 menit. Tetapi jika pesanan masih belum muncul, Kamu bisa <a class="underline decoration-primary-500 underline-offset-2" href="{{ !$config ? '' : $config->url_wa }}" style="outline: none;">Hubungi Kami</a>.
        			</p>
        			<div class="mb-3 mt-3">
						<div class="input-group">
							<input type="text" class="form-control form-control-games" placeholder="Order ID Pesanan" autocomplete="off" value="" name="id" id="id">
							<button class="btn btn-primary">Cek</button>
						</div>
					</div>
        		</form>
        	</div>
        </div>
    </section>
</main>
<div class="container sm:flex sm:items-center mt-5">
	<div class="sm:flex-auto">
		<h1 class="text-xl font-semibold text-white">10 Transaksi Terakhir</h1>
		<p class="mt-2 max-w-2xl text-sm text-murky-200">
			Ini adalah 10 transaksi terakhir dari semua pengguna. Informasi yang tersedia mencakup tanggal transaksi, kode invoice, nomor ponsel, harga, dan status.
		</p>
	</div>
</div>
<div class="container">
	<div class="space-y-4">
		<div class="overflow-x-auto ring-1 ring-murky-600 sm:mx-0 sm:rounded-lg mt-5">
			<table class="min-w-full divide-y divide-murky-600">
                <thead>
                    <tr>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="">Date</div>
                        </th>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">Invoice</div>
                        </th>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">Quantity</div>
                        </th>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">Start Count</div>
                        </th>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">Remains</div>
                        </th>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">Price</div>
                        </th>
                        <th scope="col" colspan="1" class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell table-label">
                            <div class="">Status</div>
                        </th>
                    </tr>
                </thead>
               <tbody id="data-body">
                         @foreach ($data as $pembelian)
                            @php
                            $label = '';
                            if ($pembelian->status == 'Pending') {
                                $label = 'yellow-300';
                            } else if ($pembelian->status == 'Processing') {
                                $label = 'sky-600';
                            } else if ($pembelian->status == 'Success') {
                                $label = 'emerald-200';
                            } elseif ($pembelian->status == 'Refund') {
                                $label = 'rose-300';
                            } else {
                                $label = 'rose-300';
                            }
                                
                            $ymdhis = explode(' ',$pembelian->updated_at);
                            $month = [
                                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                                'Juli','Agustus','September','Oktober','November','Desember'
                            ];
                            $explode = explode('-', $ymdhis[0]);
                            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                            @endphp
                            <tr>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">{{ $formatted.' '.substr($ymdhis[1],0,5).' WIB' }}</div>
                                </td>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">FT{{ substr($pembelian->order_id,0,-30).'*******'.substr($pembelian->order_id, -3)}}</div>
                                </td>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">{{$pembelian->quantity}}</div>
                                </td>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">{{$pembelian->count}}</div>
                                </td>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">{{$pembelian->remain}}</div>
                                </td>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap"><span>Rp. {{ number_format($pembelian->harga, 0, ',', '.') }}</span></div>
                                </td>
                                <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap"><span class="inline-flex rounded-sm px-2 text-xs font-semibold leading-5 print:p-0 bg-{{ $label }} text-emerald-900">{{ $pembelian->status }}</span></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
		</div>
	</div>
</div>
@endsection
@section('js')
<script>
    function refreshHistory() {
        $.get("{{ route('cari.get-history') }}", function(data) {
            $("#data-body").html(data);
        });
    }setInterval(refreshHistory, 5000);
    function refreshHistorys() {$.get('{{ENV("APP_URL")}}/updatepesanan');}setInterval(refreshHistorys, 5000);
</script>
@endsection