@extends( $bladeLayout ?: \Illuminate\Support\Facades\Config::get('pedreiro.blade_layout', 'layouts.app'))

@section(\Illuminate\Support\Facades\Config::get('pedreiro.blade_section'))
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $title }} {{ __('common.details') }}</h3>
                </div>

                <div class="panel-body">
                    <ul>
                        @foreach ($fields as $field)
                            <li>
                                <strong>{{ $field['label'] }}</strong>:
                                @include( "pedreiro::shared.forms.displays.{$field['type']}")
                            </li>

                        @endforeach
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        {{-- Back to resource index --}}
                        <div class="col-sm-3">
                            <a href="{{ route("$route.index") }}" class="btn btn-secondary btn-block">
                                <i class='fa fa-arrow-circle-left'></i> {{ __('common.back') }}
                            </a>
                        </div>
                        {{-- Edit resource --}}
                        <div class="col-sm-3 col-sm-offset-3">
                            <a href="{{ route("$route.edit", $entity->id ) }}" class="btn btn-warning btn-block">
                                <i class='fa fa-edit'></i> {{ __('common.edit') }} {{ $title }}
                            </a>
                        </div>
                        {{-- Delete resource --}}
                        <div class="col-sm-3">

                            <form action="{{ route("$route.destroy", $entity->id) }}" method="POST" style="display: inline-block;">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="btn btn-danger delete-btn btn-block" type="submit">
                                    <i class="fa fa-remove"></i> {{ __('common.delete') }} {{ $title }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
