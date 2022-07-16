@if(function_exists('market'))
<div class="col-md-12">
    <nav class="navbar px-0 navbar-light justify-content-between">
        <ul class="navbar-nav">
            @if (Route::has('admin.'.$module.'.create'))
                <li class="nav-item">
                    <a class="btn btn-primary" href="{!! route('admin.'.$module.'.create') !!}">{{ __('pedreiro::media.add_new_folder') }}</a>
                </li>
            @endif
            @if (Route::has('admin.market.'.$module.'.create'))
                <li class="nav-item">
                    <a class="btn btn-primary" href="{!! route('admin.market.'.$module.'.create') !!}">{{ __('pedreiro::media.add_new_folder') }}</a>
                </li>
            @endif
        </ul>
        {!! Form::open(['url' => market()->url($module.'/search'), 'class' => 'form-inline mt-2']) !!}
            <input class="form-control mr-sm-2" name="term" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        {!! Form::close() !!}
    </nav>
</div>
@endif
