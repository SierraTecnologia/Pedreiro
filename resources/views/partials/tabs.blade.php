@foreach(config('siravel.languages') as $short => $language)
    <li class="nav-item">
        <a class="nav-link @if (request('lang') == $short || is_null(request('lang')) && $short == config('siravel.default-language')) active @endif" href="{{ siravel()->url($module.'/'.$item->id.'/edit?lang='.$short) }}">{{ ucfirst($language) }}</a>
    </li>
@endforeach
