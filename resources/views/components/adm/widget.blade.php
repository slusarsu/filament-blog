@if($widget->type == 'text')
    {{$widget->body}}
@endif

@if($widget->type == 'html')
    {!! $widget->body !!}
@endif