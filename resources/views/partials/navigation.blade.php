<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        @menu('main', 'siravel-frontend::partials.main-menu')
        <?php /**
         * {!!Siravel::menu_lang_mobile() !!}
         */ ?>

        @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'blog'))
            <li class="nav-item"><a class="nav-link" href="{{ url('blog') }}">{!! trans('features.blog') !!}</a></li>
        @endif
        @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'travels'))
            <li class="nav-item"><a class="nav-link" href="{{ url('travels') }}">{!! trans('features.travels') !!}</a></li>
        @endif
        @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'midias'))
            <li class="nav-item"><a class="nav-link" href="{{ url('gallery') }}">{!! trans('features.midia.gallery') !!}</a></li>
        @endif
        @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'events'))
            <li class="nav-item"><a class="nav-link" href="{{ url('events') }}">{!! trans('features.events') !!}</a></li>
        @endif
        @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'faqs'))
            <li class="nav-item"><a class="nav-link" href="{{ url('faqs') }}">{!! trans('features.faqs') !!}</a></li>
        @endif
        @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'commerce'))
            <li class="nav-item"><a class="nav-link" href="{{ route('commerce.products') }}">Products</a></li>
        @endif
      </ul>


      <ul class="nav navbar-nav navbar-right">
            @if (Auth::guest())
                <li class="{{ (Request::is('login') ? 'active' : '') }}"><a href="{{ url('login') }}"><i
                                class="fa fa-sign-in"></i> {{ __('words.login') }}</a></li>
                <li class="{{ (Request::is('register') ? 'active' : '') }}"><a
                            href="{{ url('register') }}">{{ __('words.register') }}</a></li>
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }} <i
                                class="fa fa-caret-down"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        @if(Auth::check())
                            @if(Auth::user()->admin==1)
                                <li>
                                    <a href="{{ url('admin/dashboard') }}"><i class="fa fa-tachometer"></i> {{ __('words.dashboard') }}</a>
                                </li>
                            @endif
                            <li role="presentation" class="divider"></li>
                        @endif
                        <li>
                            <a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> {{ __('words.logout') }}</a>
                        </li>
                    </ul>
                </li>
                @if (\App\Http\Policies\BusinessPolicy::hasAccess('feature', 'commerce'))
                    <li class="nav-item"><a class="nav-link" href="{!! route('commerce.cart.contents') !!}">
                        <span class="fa fa-shopping-cart"></span>
                        <span class="cart-count"></span> Carrinho
                    </a></li>
                    <li class="nav-item dropdown">
                        <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ url('user/settings') }}"><span class="fa fa-user"></span> Settings</a>
                            <a class="dropdown-item" href="{{ route('commerce.account.profile') }}"><span class="fa fa-id-card"></span> Profile</a>
                            <a class="dropdown-item" href="{{ route('commerce.account.favorites') }}"><span class="fa fa-heart"></span> Favorites</a>
                            <a class="dropdown-item" href="{{ route('commerce.account.purchases') }}"><span class="fa fa-dollar"></span> Purchases</a>
                            <a class="dropdown-item" href="{{ route('commerce.account.orders') }}"><span class="fa fa-truck"></span> Orders</a>
                            <a class="dropdown-item" href="{{ route('commerce.account.subscriptions') }}"><span class="fa fa-ticket"></span> Subscriptions</a>
                        </div>
                    </li>
                @endif
            @endif
            {!! app('translation'::class)->getMenuLanguage() !!}
        </ul>
    </div>
</nav>