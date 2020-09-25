@if (\Route::has('admin.dashboard'))
    <ol class="breadcrumb float-sm-right">
        @php
        if (!isset($segments))
            $segments = array_filter(explode('/', str_replace(route('admin.dashboard'), '', Request::url())));
            // $segments = array_filter(explode('/', str_replace(route('rica.dashboard'), '', Request::url())));
        if (!isset($mainUrl))
            $mainUrl = route('admin.dashboard');
            // $mainUrl = route('rica.dashboard');
        @endphp
        @if(count($segments) == 0)
            <li class="breadcrumb-item active"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</li>
        @else
            <li class="breadcrumb-item active">
                <a href="{{ route('admin.dashboard')}}"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</a>
                <?php /*<a href="{{ route('admin.dashboard')}}"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</a>*/ ?>
            </li>
            @foreach ($segments as $segment)
                @php
                $mainUrl .= '/'.$segment;
                @endphp
                @if ($loop->last)
                    <li class="breadcrumb-item">{{ \Support\Routing\UrlGenerator::displayStringName($segment) }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $mainUrl }}">{{ \Support\Routing\UrlGenerator::displayStringName($segment) }}</a>
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
@endif