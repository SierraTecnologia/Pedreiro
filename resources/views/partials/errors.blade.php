@if (isset($errors))
    @if ($errors->count() > 0)
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="{!! trans('features.close') !!}"><span aria-hidden="true">&times;</span></button>
            <ul class="siravel-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif