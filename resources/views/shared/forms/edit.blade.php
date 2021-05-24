@extends( $bladeLayout ?: \Illuminate\Support\Facades\Config::get('crud-forms.blade_layout'))

@section(\Illuminate\Support\Facades\Config::get('crud-forms.blade_section'))
<div class="row">
    <div class="col-sm-12 col-lg-10 col-lg-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Edit {{ $title }}</h3>
            </div>

            <div class="panel-body">
                @include('pedreiro::_errors')
                <form action="{{ route("$route.update", $entity->getIdentificador()) }}" method="POST">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            @include('pedreiro::form')
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        {{-- Back to resource index --}}
                        <div class="col-sm-2">
                            <a href="{{ route("$route.index") }}" class="btn btn-secondary btn-block">
                                <i class='fa fa-arrow-circle-left'></i> {{ __('common.back') }}
                            </a>
                        </div>

                        {{-- Cancel and go back to resource show --}}
                        <div class="col-sm-2">
                            <a href="{{ route("$route.show", $entity) }}" class="btn btn-warning btn-block">
                                <i class='fa fa-ban'></i> {{ __('pedreiro::generic.cancel') }}
                            </a>
                        </div>

                        {{-- Submit --}}
                        <div class="col-sm-8">
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
