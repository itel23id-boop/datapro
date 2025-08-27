@extends('../main')

@section('css')

@endsection

@section('content')
    <div class="content-main mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<h4 class="fw-700 mb-3">Pertanyaan Umum</h4>
					<div class="accordion" id="accordionExample">
					    @foreach($datas as $data)
						<div class="accordion-item" style="background: var(--warna_4);">
					        <h2 class="accordion-header" id="heading-{{$data->id}}">
					            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$data->id}}" aria-expanded="false" aria-controls="collapse-{{$data->id}}">
					            	{{$data->judul}}					            </button>
					        </h2>
					        <div id="collapse-{{$data->id}}" class="accordion-collapse collapse" aria-labelledby="heading-{{$data->id}}" data-bs-parent="#accordionExample">
					            <div class="accordion-body">
					            	<p><?php echo nl2br($data->pesan) ?></p>
					            </div>
					        </div>
					    </div>
                        @endforeach
				    </div>
				</div>
			</div>
		</div>
	</div>

@section('js')

@endsection
@endsection