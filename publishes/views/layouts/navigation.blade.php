@extends('cms::layouts.master')

@section('navigation')

<div class="raw100 raw-left navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button class="navbar-toggle sidebar-menu-btn">
            <span class="fa fa-bars nav-open"></span>
            <span class="fa fa-close nav-close"></span>
        </button>
        <span class="navbar-brand">
            <span class="cms-logo"></span> {{ config('cms.backend-title', 'Cms') }}
        </span>
        @if (Auth::user())
        <p class="navbar-text navbar-left raw-m-hide">{!! trans('features.signedInAs', ['authName' => Auth::user()->name]) !!}</p>
        @endif
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNavbar">
            <span class="fa fa-gear"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse navbar-right" id="mainNavbar">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('/') }}"><span class="fa fa-arrow-left"></span> {!! trans('features.backToSite') !!} </a></li>
            @if (Auth::user())
            <li><a href="/logout"><span class="fa fa-sign-out"></span> {!! trans('features.logout') !!}</a></li>
            @endif
        </ul>
    </div>
</div>

@stop