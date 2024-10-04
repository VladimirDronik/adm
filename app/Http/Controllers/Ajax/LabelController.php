<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Label;
use App\Models\HomeObject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    public function relatedParameters(Request $r)
    {
        abort_if(! ajaxHas($r, ['object_id']), 400);

        $object = HomeObject::findOrFail((int) $r->object_id);
        $relatedParameters = Label::getParametrsByObject($object);

        return response()->json(['related_parameters' => $relatedParameters]);
    }
}
