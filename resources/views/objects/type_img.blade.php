@if($object->type === 'lamp')
    <img width="30" height="30" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
@elseif($object->type === 'socket')
    <img width="35" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
@elseif($object->type === 'termo')
    <img width="60" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
@elseif($object->type === 'hydro')
    <img width="50" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
@else
    <img width="50" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
@endif
