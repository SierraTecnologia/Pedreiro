@extends('adminlte::page')

@php
    $currentSection = request()->segment(1);
    $sectionDashboardUrls = array_merge(
        [
            'admin' => 'admin',
            'master' => 'master',
            'painel' => 'painel',
            'rica' => 'rica',
        ],
        config('pedreiro.error_pages.section_dashboard_urls', []),
        config('adminlte.section_dashboard_urls', [])
    );
    $dashboardPath = $sectionDashboardUrls[$currentSection]
        ?? config('adminlte.dashboard_url', 'home');
    $dashboardUrl = url($dashboardPath);
@endphp

@section('title')
    @yield('error_title')
@stop

@section('content_header')
@stop

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 160px);">
        <div class="col-12 col-md-8 col-lg-6 text-center">
            <div class="display-1 fw-bold text-@yield('error_theme', 'danger') lh-1 mb-3">
                @yield('error_code')
            </div>
            <h1 class="h3 mb-3">@yield('error_title')</h1>
            <p class="text-secondary mb-4">@yield('error_message')</p>
            <a href="{{ $dashboardUrl }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                @yield('error_action', 'Voltar ao painel')
            </a>
        </div>
    </div>
@stop
