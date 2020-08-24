<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeletedRecord;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;

    public function __construct()
    {
        $this->httpRequest = Request();
    }

    public static function remove($records)
    {
        $isDelete = false;

        if (!empty($records) && !$records->isEmpty()) {
            foreach ($records as $record) {
                $data['data'] = $record->toJson();
                $data['model_name'] = get_class($record);
                $data['deleted_by'] = auth()->user()->id;

                if (DeletedRecord::validators($data, true)) {
                    $create = DeletedRecord::create($data);

                    if ($create) {
                        $isDelete = $record->delete();
                    }
                }
            }
        }

        return $isDelete;
    }
}
