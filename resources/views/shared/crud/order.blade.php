@extends('pedreiro::layouts.voyager.master')

@section('page_title', $dataType->getTranslatedAttribute('display_name_plural') . ' ' . __('bread.order'))

@section('page_header')
<h1 class="page-title">
    <i class="facilitador-list"></i>{{ $dataType->getTranslatedAttribute('display_name_plural') }} {{ __('bread.order') }}
</h1>
@stop

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-bordered">
                <div class="card-header">
                    <p class="panel-title" style="color:#777">{{ __('pedreiro::generic.drag_drop_info') }}</p>
                </div>

                <div class="box-body panel-body card-body" style="padding:30px;">
                    <div class="dd">
                        <ol class="dd-list">
                            @foreach ($results as $result)
                            <li class="dd-item" data-id="{{ $result->getKey() }}">
                                <div class="dd-handle" style="height:inherit">
                                    @if (isset($dataRow->details->view))
                                        @include($dataRow->details->view, ['row' => $dataRow, 'dataType' => $dataType, 'dataTypeContent' => $result, 'content' => $result->{$display_column}, 'action' => 'order'])
                                    @elseif($dataRow->type == 'image')
                                        <span>
                                            <img src="@if( !filter_var($result->{$display_column}, FILTER_VALIDATE_URL)){{ Facilitador::image( $result->{$display_column} ) }}@else{{ $result->{$display_column} }}@endif" style="height:100px">
                                        </span>
                                    @else
                                        <span>{{ $result->{$display_column} }}</span>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')

<script>
$(document).ready(function () {
    $('.dd').nestable({
        maxDepth: 1
    });

    /**
    * Reorder items
    */
    $('.dd').on('change', function (e) {
        $.post('{{ \Pedreiro\Routing\UrlGenerator::managerRoute($dataType->slug, 'order') }}', {
            order: JSON.stringify($('.dd').nestable('serialize')),
            _token: '{{ csrf_token() }}'
        }, function (data) {
            toastr.success("{{ __('bread.updated_order') }}");
        });
    });
});
</script>
@stop
