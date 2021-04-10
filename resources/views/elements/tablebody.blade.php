<tbody>
    <tr>
        <td>{{ $element->id }}</td>
        <td>
            <a href="#" id="nameElement_{{ $element->id }}"
               onclick="edit_name({{ $element->id }});"
               data-toggle="modal"
               data-target="#nameElementModal">{{ $element->name }}</a>

            @if($element->type == 'accordion')
                &nbsp;&nbsp;<i class="fa fa-level-down fa-2x" aria-hidden="true" data-toggle="collapse"
                   data-target="#accordion_{{ $element->id }}" class="clickable"></i>
            @endif
        </td>
        <td>{{ $element->type }}</td>
        <td><img src="{{ asset('ela/images/views_items/'.$element->image) }}"
                 id="imageElement_{{ $element->id }}" class="imageElement"
                 data-toggle="modal" data-target="#selectImageCategory"
                 onclick="updateImage({{ $element->id }}, true);"
                 width="50px" height="50px" style="cursor: pointer">
        </td>
        <td>{{ $element->value }}</td>
        <td class="text-center">
            <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$element->id}}" value="1" @if($element->active) checked @endif>
        </td>
        <td style="width: 150px;">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control input-default" readonly
                           value="{{ $element->sort }}">
                </div>
                <div class="col-md-6 text-left">
                    <button type="button" class="btn btn-info btn-xs"
                            id="sortBtn{{ $element->id }}"
                            onclick="changeSort({{ $element->id }}, 'up');">выше
                    </button>

                    <button type="button" class="btn btn-info btn-xs"
                            id="sortBtn{{ $element->id }}"
                            onclick="changeSort({{ $element->id }}, 'down');">ниже
                    </button>
                </div>
            </div>
        </td>
        <td class="text-center">
            <a href="{{ route('elements.edit',[$element->id]) }}"
               class="btn btn-info btn-sm btn-rounded">
                <i class="fa fa-cog fa-lg"></i>
            </a>
        </td>
        <td class="text-center">
            <button type="button"
                    class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                    data-id="{{ $element->id }}"
                    data-type="{{ $element->type }}"
                    data-name="{{ $element->name }}">
                <i class="fa fa-trash fa-lg"></i>
            </button>
        </td>
    </tr>
    @if($element->type == 'accordion')

        @foreach($element->childs AS $child)

                <tr style="background-color: #f5f5f5" id="accordion_{{$element->id}}" class="collapse">
                    <td></td>
                    <td style="text-align: right"> <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{$child->name}}

                    <td>{{$child->type}}</td>
                    <td><img src="{{ asset('ela/images/views_items/'.$child->image) }}"
                             id="imageElement_{{ $child->id }}" class="imageElement"
                             data-toggle="modal" data-target="#selectImageCategory"
                             onclick="updateImage({{ $child->id }}, true);"
                             width="50px" height="50px" style="cursor: pointer">
                    </td>

                    <td>{{ $element->value }}</td>
                    <td class="text-center">
                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$element->id}}" value="1" @if($element->active) checked @endif>
                    </td>
                    <td style="width: 150px;">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control input-default" readonly
                                       value="{{ $element->sort }}">
                            </div>
                            <div class="col-md-6 text-left">
                                <button type="button" class="btn btn-info btn-xs"
                                        id="sortBtn{{ $element->id }}"
                                        onclick="changeSort({{ $element->id }}, 'up');">выше
                                </button>

                                <button type="button" class="btn btn-info btn-xs"
                                        id="sortBtn{{ $element->id }}"
                                        onclick="changeSort({{ $element->id }}, 'down');">ниже
                                </button>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('elements.edit',[$element->id]) }}"
                           class="btn btn-info btn-sm btn-rounded">
                            <i class="fa fa-cog fa-lg"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                data-id="{{ $element->id }}"
                                data-type="{{ $element->type }}"
                                data-name="{{ $element->name }}">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>

                    </td>
                </tr>


        @endforeach

    @endif
</tbody>
