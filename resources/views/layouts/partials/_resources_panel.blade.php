
@if(isset($banners['sidebar-resources']))
<div class="card card-default corner-radius sidebar-resources">
  <div class="card-header text-center">
    <h3 class="panel-title">Publicidade</h3>
  </div>
  <div class="box-body panel-body card-body">
    <ul class="list list-group ">
        @foreach($banners['sidebar-resources'] as $banner)
            <li class="list-group-item ">
                <a href="{{ $banner->link }}" class="no-pjax" title="{{{ $banner->title }}}">
                    <img class="media-object inline-block " src="{{ $banner->image_url }}">
                    {{{ $banner->title }}}
                </a>
            </li>
        @endforeach
    </ul>
  </div>
</div>
@endif
