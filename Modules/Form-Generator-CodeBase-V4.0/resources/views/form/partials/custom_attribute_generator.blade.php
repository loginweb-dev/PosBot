@foreach($attributes as $attribute)
    {{$attribute['key']}} = "{{$attribute['value']}}"
@endforeach