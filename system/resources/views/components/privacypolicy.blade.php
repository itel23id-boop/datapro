@extends('../main')

@section('css')

@endsection


@section('content')
<div class="content-main mt-5">
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 mx-auto mt-5">
                <div class="pb-4">
                    <div class="card shadow-form">
                        <div class="mb-4">   
                            <div class="card-body mb-4">{!! !$config ? '' : $config->privacy !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








@section('js')
@endsection



@endsection