<?php

namespace App\Adm\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdmForm;
use App\Models\AdmFormItem;
use Illuminate\Http\Request;

class AdmFormController extends Controller
{
    public function form(Request $request, $link_hash)
    {
        $admForm = AdmForm::query()->where('link_hash', $link_hash)->with('admFormItems')->first();

        if(!$admForm) {
            return back()->with('adm_form_err', 'error');
        }

        $admFields = $admForm->fields();

        $itemData = [];

        foreach ($admFields as $admFieldName) {
            $itemData[$admFieldName] = $request->get($admFieldName);
        }

        AdmFormItem::query()->create([
            'adm_form_id' => $admForm->id,
            'payload' => $itemData,
        ]);

        return back()->with('adm_form_success', 'saved!');
    }
}
