<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeletedRecord;
use App\Log;

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

    public static function createLog($record, $message, $operationType, $oldData = [], $newData = [])
    {
        if (!empty($record)) {
            $url       = url()->full();
            $ipAddress = request()->ip();
            $userAgent = request()->server('HTTP_USER_AGENT');
            $userAgent = (empty($userAgent)) ? request()->header('User-Agent') : $userAgent;

            $data['model']          = get_class($record);
            $data['model_id']       = $record->id;
            $data['message']        = $message;
            $data['old_data']       = json_encode($oldData);
            $data['new_data']       = json_encode($newData);
            $data['operation_type'] = $operationType;
            $data['url']            = $url;
            $data['ip_address']     = $ipAddress;
            $data['user_agent']     = $userAgent;
            $data['created_by']     = auth()->user()->id;

            $model = new Log();

            if ($model::validators($data, true)) {
                return $model::create($data);
            }
        }

        return false;
    }
}
