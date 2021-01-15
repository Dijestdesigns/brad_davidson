<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\Notification;

class NotificationsController extends \App\Http\Controllers\BaseController
{
    public function index(Request $request)
    {
        $model          = new Notification();
        $isFiltered     = false;
        // $total          = $model::count();
        $modelQuery     = $model::query();
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        $userId = auth()->user()->id;

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        $modelQuery->where('user_id', $userId)->where('is_read', $model::UNREAD);

        $records = $modelQuery->orderBy('updated_at', 'DESC')->paginate($model::PAGINATE_RECORDS);

        $total   = $modelQuery->get()->count();

        return view('notifications.index', compact('total', 'records', 'request', 'isFiltered'));
    }

    public function read(int $id)
    {
        $model = new Notification();

        $record = $model->find($id);

        if (!empty($record)) {
            $record->is_read = $model::READ;

            if ($record->save()) {
                return redirect('notifications')->with('success', __("Notification removed!"));
            }
        }

        return redirect('notifications')->with('error', __("Notification not removed!"));
    }
}
