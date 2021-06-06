
@if (\Route::has('admin.porteiro.dashboard'))
    <ol class="breadcrumb float-sm-right">
        @php
            if (!isset($segments)) {
                $url = str_replace(route('admin.porteiro.dashboard'), '', Request::url());
                $url = str_replace(route('master.porteiro.dashboard'), '', $url);
                $url = str_replace(route('rica.porteiro.dashboard'), '', $url);
                $url = str_replace('http:', '', $url);
                $url = str_replace('localhost', '', $url);
                $url = str_replace(config('app.url'), '', $url);
                $segments = array_filter(explode('/', $url));
            }
            if (!isset($mainUrl)) {
                $mainUrl = route('admin.porteiro.dashboard');
            }
        @endphp
        @if(count($segments) == 0)
            <li class="breadcrumb-item active"><i class="facilitador-boat"></i> {{ __('pedreiro::generic.dashboard') }}</li>
        @else
            <li class="breadcrumb-item active">
                <a href="{{ route('admin.porteiro.dashboard')}}"><i class="facilitador-boat"></i> {{ __('pedreiro::generic.dashboard') }}</a>
                <?php /*<a href="{{ route('admin.porteiro.dashboard')}}"><i class="facilitador-boat"></i> {{ __('pedreiro::generic.dashboard') }}</a>*/ ?>
            </li>
            @foreach ($segments as $segment)
                @php
                $mainUrl .= '/'.$segment;
                @endphp
                @if ($loop->last)
                    <li class="breadcrumb-item">{{ \Pedreiro\Routing\UrlGenerator::displayStringName($segment) }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $mainUrl }}">{{ \Pedreiro\Routing\UrlGenerator::displayStringName($segment) }}</a>
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
@endif