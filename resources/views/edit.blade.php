@extends( $bladeLayout ?: \Illuminate\Support\Facades\Config::get('pedreiro.blade_layout', 'layouts.app'))

@section(\Illuminate\Support\Facades\Config::get('pedreiro.blade_section'))
<div class="row">
    <div class="col-sm-12 col-lg-10 col-lg-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Edit {{ $title }}</h3>
            </div>

            <div class="panel-body">
                @include('pedreiro::_errors')
                <form action="{{ route("$route.update", $entity->id) }}" method="POST">
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
                            <a href="{{ route("$route.index") }}" class="btn btn-default btn-block">
                                <i class='fa fa-arrow-circle-left'></i> Back to Index
                            </a>
                        </div>

                        {{-- Cancel and go back to resource show --}}
                        <div class="col-sm-2">
                            <a href="{{ route("$route.show", $entity) }}" class="btn btn-warning btn-block">
                                <i class='fa fa-ban'></i> Cancel
                            </a>
                        </div>

                        {{-- Submit --}}
                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class='fa fa-check-circle'></i> Submit Form
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
