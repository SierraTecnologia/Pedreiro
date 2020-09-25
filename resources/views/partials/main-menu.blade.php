@foreach($links as $link)
    @if ($link->external)
        <li class="nav-item"><a class="nav-link" href="{{ url($link->external_url) }}">{{ $link->name }}</a></li>
    @else
        <li class="nav-item"><a class="nav-link" href="{{ url(\Siravel\Models\Negocios\Page::find($link->page_id)->url) }}">{{ $link->name }}</a></li>
    @endif
@endforeach