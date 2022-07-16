<div class="row mb-4">
    <div class="col-md-12">
        <h3><a class="nav-link float-left" href="{!! route('home') !!}">{{ \Illuminate\Support\Facades\Config::get('name', 'My Store') }}</a></h3>
        <nav class="nav float-right">
            <a class="nav-link" href="{!! route('cart.contents') !!}">
                <span class="fa fa-shopping-cart"></span>
                <span class="cart-count"></span> Items
            </a>
            <a class="nav-link" href="{{ route('admin.market.products.index') }}">Products</a>
            <div class="dropdown">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></button>
                <div class="dropdown-menu">
                    @if (auth()->user())
                        <a class="dropdown-item" href="{{ url('profile/settings') }}"><span class="fa fa-user"></span> Settings</a>
                        <a class="dropdown-item" href="{{ route('account.profile') }}"><span class="fa fa-id-card"></span> Profile</a>
                        <a class="dropdown-item" href="{{ route('account.favorites') }}"><span class="fa fa-heart"></span> Favorites</a>
                        <a class="dropdown-item" href="{{ route('account.purchases') }}"><span class="fa fa-dollar"></span> Purchases</a>
                        <a class="dropdown-item" href="{{ route('account.orders') }}"><span class="fa fa-truck"></span> Orders</a>
                        <a class="dropdown-item" href="{{ route('account.subscriptions') }}"><span class="fa fa-ticket"></span> Subscriptions</a>
                        <a class="dropdown-item" href="{{ url('logout') }}"><span class="fa fa-sign-out"></span> Logout</a>
                    @else
                        <a class="dropdown-item" href="{{ url('login') }}"><span class="fa fa-sign-in"></span> Login</a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</div>