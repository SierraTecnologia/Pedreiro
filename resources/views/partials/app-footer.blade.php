<footer class="app-footer">
    <div class="site-footer-right">
        @if (rand(1,100) == 100)
            <i class="facilitador-rum-1"></i> {{ __('pedreiro::theme.footer_copyright2') }}
        @else
            {!! __('pedreiro::theme.footer_copyright') !!} <a href="http://thecontrolgroup.com" target="_blank">The Control Group</a>
        @endif
        @php $version = Support::getVersion(); @endphp
        @if (!empty($version))
            - {{ $version }}
        @endif
    </div>
</footer>
