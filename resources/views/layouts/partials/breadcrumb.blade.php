<section class="content-header">
	<h1>
		{!! trans('words.dashboard') !!}
		<small>{!! trans('words.controlPane') !!}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> {!! trans('words.home') !!}</a></li>
        {!! Siravel::breadcrumbs($location) !!}
        <li class="active"></li>
	</ol>
</section>