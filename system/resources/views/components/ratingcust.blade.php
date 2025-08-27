@extends('main')
<style>
.star-rating {
    line-height: 32px;
    font-size: 1.25em;
}
.star-rating .fa-star {
    color: #da951d;
}
.star-rating .fa-star {
    color: #da951d;
}
.text-murky-300 {
    --tw-text-opacity: 1;
    color: rgb(157 165 171 / var(--tw-text-opacity));
}
</style>
@section('content')
<main class="relative">
    <div class="py-12 sm:py-24">
        <div class="container">
            <div class="mx-auto max-w-xl text-center">
                <h1 class="text-xxl font-semibold leading-8 tracking-tight text-primary-500">Testimonials</h1>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white">Terima kasih untuk semua pelanggan yang memberi kami ulasan dan peringkat.</p>
            </div>
            <div class="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
                <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3" id="items_container">
                    @foreach ($ratings as $rating)
                    <?php
                    $kategoris = DB::table('kategoris')->where('id',$rating->kategori_id)->select('nama','thumbnail')->first();
                    ?>
                    @php
                    $ymdhis = explode(' ',$rating->created_at);
                    $month = [
                        1 => 'Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember'
                    ];
                    $explode = explode('-', $ymdhis[0]);
                    $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                    @endphp
                    <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                        <figure class="card w-full rounded-2xl p-6 text-sm leading-6 shadow-form">
                            <h3 class="font-bold">{{ $kategoris->nama }}</h3>
                            <blockquote class="mt-3 italic text-white"><p>“{{ $rating->comment }}”</p></blockquote>
                            <figcaption class="mt-3 flex w-full flex-col items-center justify-center gap-x-4">
                                <div class="flex w-full items-center justify-between">
                                    <div class="text-murky-300">{{ '08********'.substr($rating->no_pembeli, -2)}}</div>
                                    <div class="flex items-center">
                                         <div class="star-rating">
                                            <td>
                                                                @for($i=1; $i<=5; $i++)
                                                                    @if($i <= $rating->bintang)
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor</td>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 flex w-full items-center justify-between">
                                    <div class="text-xs text-murky-300">{{ $rating->layanan }}</div>
                                    <div class="flex items-center text-xs text-white">{{ $formatted.' '.substr($ymdhis[1],0,5).' WIB' }}</div>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div style="text-align: center !important;">
                <button id="load_more_button" data-page="{{ $ratings->currentPage() + 1 }}" class="btn btn-sm btn-primary w-100">Muat Lebih Banyak</button>
            </div>
        </div>
    </div>
</main>
@endsection
@section('js')
<script>
        $(document).ready(function() {
            var start = 5;
            $('#load_more_button').click(function() {
                $.ajax({
                    url: "{{ route('load.more') }}",
                    method: "POST",
                    data: {
                        "_token": "<?php echo csrf_token(); ?>",
                        "start": start
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#load_more_button').html('Loading...');
                        $('#load_more_button').attr('disabled', true);
                    },
                    success: function(data) {

                    if (data.status == true) {
                        $('#items_container').append(data.data);
                        $('#load_more_button').html('Load More');
                        $('#load_more_button').attr('disabled', false);
                        start = data.next;
                    } else {
                        $('#load_more_button').append(data.data);
                        $('#load_more_button').attr('disabled', true);
                    }
                }
            });
        });
    });
</script>
@endsection