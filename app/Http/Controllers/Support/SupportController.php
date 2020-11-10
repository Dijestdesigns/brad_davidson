<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;
use App\Support;
use App\Log;

class SupportController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:support_access'])->only('index');
        $this->middleware(['permission:support_create'])->only(['create','store']);
    }

    public function index(Request $request)
    {
        $model      = new Support();
        $isFiltered = false;
        // $total          = $model::count();
        $modelQuery     = $model::query();
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        if ($isFiltered) {
            if ($request->get('q', false)) {
                $q = $request->get('q');

                $modelQuery->where(function($query) use($q) {
                    $query->where('query', 'LIKE', "%$q%");
                });
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->paginate(Tag::PAGINATE_RECORDS);

        return view('support.index', compact('total', 'records', 'request', 'isFiltered'));
    }

    public function store(Request $request)
    {
        $user  = auth()->user();
        $data  = $request->all();
        $model = new Support();

        $data['user_id'] = $user->id;

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $find = $model::find($create->id);
            self::createLog($find, __("Created query {$find->name}"), Log::CREATE, [], $find->toArray());

            // Send Email.
            $emailId     = env('MAIL_TO_SUPPORT', 'it.jaydeep.mor@gmail.com');
            $subject     = env('APP_NAME', 'Brad Davidson') . ' Urgent ! Query from ' . $find->name;

            $return = $this->sendMail('support', [$emailId], $subject, $find);

            return redirect()->back()->with('success', __('Thank you! We received your query and contact you soon!'));
        }

        return redirect()->back()->with('error', __("There has been an error!"));
    }
}
