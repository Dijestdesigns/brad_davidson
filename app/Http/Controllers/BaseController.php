<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeletedRecord;
use App\Log;
use App\Email;
use APp\User;
use Illuminate\Support\Facades\Mail;
use View;
use Carbon\Carbon;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;

    public $errorCode     = 401;
    public $successCode   = 200;
    public $returnNullMsg = 'No response found!';

    public function __construct()
    {
        $this->httpRequest = Request();
    }

    public function returnError($message = NULL)
    {
        return response()->json([
            'code' => $this->errorCode,
            'msg'  => $message
        ]);
    }

    public function returnSuccess($message = NULL, $with = NULL)
    {
        return response()->json([
            'code' => $this->successCode,
            'msg'  => $message,
            'data' => $with
        ]);
    }

    public function returnNull($message = NULL)
    {
        return response()->json([
            'code' => $this->successCode,
            'msg'  => !empty($message) ? $message : $this->returnNullMsg
        ]);
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
            $data['created_by']     = !empty(auth()->user()->id) ? auth()->user()->id : User::$superadminId;

            $model = new Log();

            if ($model::validators($data, true)) {
                return $model::create($data);
            }
        }

        return false;
    }

    public function sendMail($view, $to, $subject, $body, $toName = '', $cc = '', $bcc = '', $attachments = [])
    {
        if (empty($view)) {
            return response()->json([
                'code' => 401,
                'msg'  => __('Please provide email view.')
            ]);
        }

        $validator = Email::validator(['to' => $to, 'subject' => $subject, 'body' => $body]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'msg'  => $validator->errors()->first()
            ]);
        }

        $bodyContent = View::make('emails.'. $view, compact('body'))->render();
        Mail::send('emails.'. $view, compact('body'), function($message) use ($to, $subject, $toName, $cc, $bcc, $attachments) {
            $message->to($to, $toName)
                    ->subject($subject);
            if (!empty($cc)) {
                $message->cc($cc);
            }

            if (!empty($bcc)) {
                $message->bcc($bcc);
            }

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (empty($attachment['path'])) {
                        continue;
                    }

                    $as   = (!empty($attachment['as'])) ? $attachment['as'] : '';
                    $mime = (!empty($attachment['mime'])) ? $attachment['mime'] : '';

                    $message->attach(public_path('storage/' . $attachment['path']), ['as' => $as, 'mime' => $mime]);
                }
            }
        });

        if (Mail::failures()) {
            return response()->json([
                'code' => 401,
                'msg'  => __('Email not sent')
            ]);
        } else {
            foreach ($to as $mailId) {
                Email::insert([
                    'from'           => env('MAIL_FROM_ADDRESS', ''),
                    'to'             => $toName . ' ' . $mailId,
                    'cc'             => $cc,
                    'bcc'            => $bcc,
                    'subject'        => $subject,
                    'body'           => $bodyContent,
                    'attachments'    => json_encode($attachments),
                    'exception_info' => NULL,
                    'created_at'     => Carbon::now()
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => __('Email sent successfully !')
            ]);
        }
    }
}
