@extends( $bladeLayout ?: \Illuminate\Support\Facades\Config::get('crud-forms.blade_layout'))

@section(\Illuminate\Support\Facades\Config::get('crud-forms.blade_section'))

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="card card-primary">
            <div class="card-header">
                <div class="btn-group float-right">
                    <a href="{{ route("$route.create" ) }}" class="btn btn-secondary btn-xs float-right">
                        <i class='fa fa-plus'></i> aa{{ __('pedreiro::media.add_new_folder') }} {{ $title }}
                    </a>
                </div>
                <h3 class="panel-title">{{ Illuminate\Support\Str::plural($title) }} Index</h3>
            </div>
            <div class="box-body panel-body card-body">
                <table class="table table-striped table-sm data-table">
                    <thead>
                        <tr>
                            @foreach ($fields as $field)
                                <th>{{$field['label']}}</th>
                            @endforeach
                            @if ($withTrashed)
                                <th>Deleted On</th>
                            @endif
                            <th class="text-center" style="white-space: nowrap;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entities as $entity)
                            <tr>
                                @foreach ($fields as $field)
                                    <td>@include( "pedreiro::displays.{$field['type']}")</td>
                                @endforeach

                                @if ($withTrashed)
                                    <td>{{ !empty($entity->deleted_at) ? $entity->deleted_at : '' }}</td>
                                @endif

                                <td class="text-center" style="white-space: nowrap">
                                    @if (empty($entity->deleted_at))
                                        {{-- Show --}}
                                        <a href="{{ route("$route.show", $entity->getIdentificador() ) }}" class="btn btn-info">
                                            @if (\Illuminate\Support\Facades\Config::get('crud-forms.button_icons'))
                                                <i class="fa fa-info-circle"></i>
                                            @else
                                                show
                                            @endif
                                        </a>

                                        {{-- Update --}}
                                        <a href="{{ route("$route.edit", $entity->getIdentificador() ) }}" class="btn btn-warning">
                                            @if (\Illuminate\Support\Facades\Config::get('crud-forms.button_icons'))
                                                <i class="fa fa-edit"></i>
                                            @else
                                                edit
                                            @endif
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route("$route.destroy", $entity->getIdentificador()) }}" method="POST" style="display: inline-block;">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button class="btn btn-danger delete-btn" type="submit">
                                                @if (\Illuminate\Support\Facades\Config::get('crud-forms.button_icons'))
                                                    <i class="fa fa-remove"></i>
                                                @else
                                                    delete
                                                @endif
                                            </button>
                                        </form>
                                    @elseif ($withTrashed)
                                        {{-- Restore SoftDeleted --}}
                                        <form action="{{ '/' . request()->path()  . '/' . $entity->id . '/restore' }}" method="POST" style="display: inline-block;">
                                            {{ method_field('PUT') }}
                                            {{ csrf_field() }}
                                            <button class="btn btn-success restore-btn" type="submit">
                                                @if (\Illuminate\Support\Facades\Config::get('crud-forms.button_icons'))
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
            </div>
        </div>
    </div>
</div>

@endsection
