@props(['item' => []])

<li>
    <a href="{{$item->link}}">{{$item->title}}</a>

    @if(count($item->sub_item))
        <ul>
            @foreach($item->sub_item as $subItem)
                <x-adm.menu-item :item="$subItem"/>
            @endforeach
        </ul>
    @endif
</li>

