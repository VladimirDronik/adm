@if($equipment->type === 'boiler')
    <img width="30" height="30" title="{{ $equipment->type }}" src="{{ asset('ela/images/views_items/boiler.svg') }}">
@elseif($equipment->type === 'boiler_gvs')
    <img width="30" height="30" title="{{ $equipment->type }}" src="{{ asset('ela/images/views_items/boiler-gvs.svg') }}">
@else
    <img width="35" height="35" title="{{ $equipment->type }}" src="{{ asset('ela/images/views_items/noimege.png') }}">
@endif
