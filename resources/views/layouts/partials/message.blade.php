@if (Session::has('message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="{!! trans('features.close') !!}"><span aria-hidden="true">&times;</span></button>
        <span> {{ Session::get('message') }} </span>
    </div>
@endif
@include('flash::message')