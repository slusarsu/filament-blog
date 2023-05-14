@foreach($items as $item)
    <ul>
        <x-adm.menu-item :item="$item"/>
    </ul>
@endforeach
