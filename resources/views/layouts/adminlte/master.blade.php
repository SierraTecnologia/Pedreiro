@extends('adminlte::page')

@push('css')
    @if (View::exists('socrates::botman.partials.css') && config('siravel.botman', false))
        @include('socrates::botman.partials.css')
    @elseif (View::exists('boravel::botman.partials.css') && config('siravel.botman', false))
        @include('boravel::botman.partials.css')
    @endif
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css">


    <script type="text/javascript">
        var _token = '{!! csrf_token() !!}';
        var _url = '{!! url("/") !!}';
        var _cms = '{!! url("/") !!}';
        var _pixabayKey = '{!! config('cms.pixabay', '') !!}';
        var _appTimeZone = '{!! config('app.timezone', 'UTC') !!}';
        var _apiKey = '{!! config("cms.api-key") !!}';
        var _apiToken = '{!! config("cms.api-token") !!}';
    </script>
@endpush

@section('js')
    @parent
    @if (View::exists('socrates::botman.partials.js') && config('siravel.botman', false))
        @include('socrates::botman.partials.js')
    @elseif (View::exists('boravel::botman.partials.js') && config('siravel.botman', false))
        @include('boravel::botman.partials.js')
    @endif
    <script src="https://cdn.rawgit.com/download/glyphicons/0.1.0/glyphicons.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    @stack('javascript')
    @yield('javascript')
    @stack('scripts')
    @yield('scripts')
@stop

@section('title')
    @parent
    @hasSection('page_title')
        @stack('page_title')
        @yield('page_title')
    @else
        <?php if (isset($title)) {
            echo $title;
        } ?>
        @stack('title')
        @yield('title')
    @endif
@stop

@section('content_header')
    @parent
    <div class="container-fluid" id="app">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                @stack('title')
                @yield('title')
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            @section('breadcrumbs')
              @include('pedreiro::layouts.adminlte.breadcrumb')
                @show
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
    @stack('content_header')
    @yield('content_header')
@stop

@section('content_top_nav_right')
      @if (Route::has('profile.transmissor.messages.index'))
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">199</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <?php /*
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 rounded-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          */ ?>
          <div class="dropdown-divider"></div>
          <a href="{{ route('profile.transmissor.messages.index') }}" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      @endif
      @if (Route::has('profile.transmissor.notifications.index'))
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php /*
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a> */ ?>
          <div class="dropdown-divider"></div>
          <a href="{{ route('profile.transmissor.notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      @endif
    {!! \Translation::menuAdminLte() !!}
@stop

@section('content')
  <div id="app">
    @parent
    @if (isset($content))
      @if (is_string($content))
        {!! $content !!}
      @else
        {!! $content->render() !!}
      @endif
    @endif
    @stack('content')
    @yield('content')
    @if (isset($main))
      @if (is_string($main))
        {!! $main !!}
      @else
        {!! $main->render() !!}
      @endif
    @endif
    @stack('main')
    @yield('main')
  </div>
@stop

@section('footer')
    @parent
    <?php if (isset($footer)) {
    echo $footer;
} ?>
    @stack('footer')
    @yield('footer')
@stop
