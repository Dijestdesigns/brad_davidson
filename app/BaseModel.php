<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Events\NotificationsRead;

class BaseModel extends Model
{
    const PAGINATE_RECORDS = 20;

    public function __construct(array $attributes = array())
    {
        $isRead = request()->get('is_read', false);

        if ($isRead && (int)$isRead === 1) {
            $id = request()->get('id', false);

            if ($id) {
                /*$model = new Notification();

                $find = $model::find($id);

                if (!empty($find)) {
                    $find->is_read = $model::READ;

                    $find->save();
                }*/

                DB::update("UPDATE `notifications` SET `is_read` = '1' WHERE `id` = '" . $id . "'");

                // broadcast(new NotificationsRead($id))->toOthers();
            }
        }

        parent::__construct($attributes);
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
