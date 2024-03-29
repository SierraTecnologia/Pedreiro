@extends( $bladeLayout ?: \Illuminate\Support\Facades\Config::get('pedreiro.blade_layout', 'layouts.app'))

@section(\Illuminate\Support\Facades\Config::get('pedreiro.blade_section'))
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="card card-primary">
            <div class="card-header">
                <div class="btn-group float-right">
                    <a href="{{ route("$route.create" ) }}" class="btn btn-secondary btn-xs float-right">
                        <i class='fa fa-plus'></i> {{ __('common.add') }} {{ $title }}
                    </a>
                </div>
                <h3 class="panel-title">{{ Illuminate\Support\Str::plural($title) }} Index</h3>
            </div>
            <div class="box-body panel-body card-body">

                @if ($entities->count() === 0)
                    <div class="well text-center">Nenhum resultado encontrado.</div>
                @else
                    <table class="table table-striped table-sm data-table">
                        <thead>
                            <tr>
                                @foreach ($fields as $field)
                                    <th>{{$field['label']}}</th>
                                @endforeach
                                @if ($withTrashed)
                                    <th>Deleted On</th>
                                @endif
                                <th class="text-center" style="white-space: nowrap;">{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entities as $entity)
                                <tr>
                                    @foreach ($fields as $field)
                                        <td>@include( "pedreiro::shared.forms.displays.{$field['type']}")</td>
                                    @endforeach

                                    @if ($withTrashed)
                                        <td>{{ !empty($entity->deleted_at) ? $entity->deleted_at : '' }}</td>
                                    @endif

                                    <td class="text-center" style="white-space: nowrap">
                                        @if (empty($entity->deleted_at))
                                            {{-- Show --}}
                                            <a href="{{ route("$route.show", ['id' => $entity] ) }}" class="btn btn-info">
                                                @if (\Illuminate\Support\Facades\Config::get('pedreiro.button_icons'))
                                                    <i class="fa fa-info-circle"></i>
                                                @else
                                                {{ __('common.view') }}
                                                @endif
                                            </a>

                                            {{-- Update --}}
                                            <a href="{{ route("$route.edit", ['id' => $entity] ) }}" class="btn btn-warning">
                                                @if (\Illuminate\Support\Facades\Config::get('pedreiro.button_icons'))
                                                    <i class="fa fa-edit"></i>
                                                @else
                                                {{ __('common.edit') }}
                                                @endif
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route("$route.destroy", ['id' => $entity]) }}" method="POST" style="display: inline-block;">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button class="btn btn-danger delete-btn" type="submit">
                                                    @if (\Illuminate\Support\Facades\Config::get('pedreiro.button_icons'))
                                                        <i class="fa fa-remove"></i>
                                                    @else
                                                    {{ __('common.delete') }}
                                                    @endif
                                                </button>
                                            </form>
                                        @elseif ($withTrashed)
                                            {{-- Restore SoftDeleted --}}
                                            <form action="{{ '/' . request()->path()  . '/' . $entity->id . '/restore' }}" method="POST" style="display: inline-block;">
                                                {{ method_field('PUT') }}
                                                {{ csrf_field() }}
                                                <button class="btn btn-success restore-btn" type="submit">
                                                    @if (\Illuminate\Support\Facades\Config::get('pedreiro.button_icons'))
                                                        <i class="fa fa-level-up"></i>
                                                    @endif
                                                    Restore
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($withPagination && $withPagination != 0)
                        {{ $entities->links() }}
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
