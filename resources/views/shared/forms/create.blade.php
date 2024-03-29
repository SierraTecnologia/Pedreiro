@extends( $bladeLayout ?: \Illuminate\Support\Facades\Config::get('crud-forms.blade_layout'))

@section(\Illuminate\Support\Facades\Config::get('crud-forms.blade_section'))
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="panel-title">{{ __('pedreiro::media.add_new_folder') }} {{ $title }}</h3>
            </div>
            <div class="box-body panel-body card-body">
                @include('pedreiro::_errors')
                <form action="{{ route("$route.store", $entity->getIdentificador()) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            @include('pedreiro::form')
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        {{-- Back to resource index --}}
                        <div class="col-sm-3">
                            <a href="{{ route("$route.index") }}" class="btn btn-secondary btn-block">
                                <i class='fa fa-arrow-circle-left'></i> Back
                            </a>
                        </div>
                        {{-- Submit --}}
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class='fa fa-check-circle'></i> {{ __('common.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
