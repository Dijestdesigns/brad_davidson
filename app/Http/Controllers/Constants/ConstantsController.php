<?php

namespace App\Http\Controllers\Constants;

use Illuminate\Http\Request;
use App\Log;
use App\Constant;

class ConstantsController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:constant_update'])->only('update');
    }

    public function update(int $id, Request $request)
    {
        $data   = $request->all();
        $model  = new Constant();
        $record = $model::find($id);
        $update = false;

        if ($record) {
            $validator = $model::validators($data);

            $validator->validate();

            $oldData = $record->toArray();

            $updateData = [
                'key'   => $data['key'],
                'value' => $data['value']
            ];

            $update = $record->update($updateData);

            if ($update) {
                $find = $model::find($id);
                self::createLog($find, __("Updated constant {$find->key}"), Log::UPDATE, $oldData, $find->toArray());

                return redirect()->back()->with('success', __("Constant updated!"));
            }
        }

        return redirect()->back()->with('error', __("There has been an error!"));
    }
}
