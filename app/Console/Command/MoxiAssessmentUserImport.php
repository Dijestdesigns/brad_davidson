<?php

namespace App\Console\Command;

use Illuminate\Console\Command;
use App\Libraries\GoogleLibrary;
use App\User;
use App\ClientTag;
use App\Role;
use App\Log as ModelLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Contracts\Session;
use Log;
use App\Http\Controllers\BaseController;

class MoxiAssessmentUserImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:moxi:users';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import live Moxi Assessment users.';

    public $googleLibrary, $googleService, $spreadsheetId;

    private $startMonth = 2;

    private $clientTagId = 8;

    private $weightUnits = 'p';

    private $sleepLoops = 45;

    private $chunkedLengths = 200;

    private $moxiRolesId = 6;

    private $staticPassword = "%Q3xrI0S";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->googleLibrary = new GoogleLibrary();
        $this->googleService = $this->googleLibrary->getService();
        $this->spreadsheetId = $this->googleLibrary->sheetId;
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $googleService  = $this->googleService;

        $data           = $googleService->spreadsheets_values->get($this->spreadsheetId, env('GOOGLE_SUBSHEET_NAME') . '!A2:CI1000')->getValues();

        $userData       = [];

        if (!empty($data)) {
            foreach ((array)$data as $index => $row) {
                // Name.
                $userData[$index]['name'] = $this->getData('name', $row);

                // Surname.
                $userData[$index]['surname'] = $this->getData('surname', $row);

                // Email
                $userData[$index]['email'] = $this->getData('email', $row);

                // Mobile
                $userData[$index]['contact'] = $this->getData('contact', $row);

                // Gender
                $userData[$index]['gender'] = $this->getData('gender', $row);

                // Age
                $userData[$index]['age'] = $this->getData('age', $row);

                // Weight
                $userData[$index]['weight'] = $this->getData('weight', $row);

                // Height
                $userData[$index]['height'] = $this->getData('height', $row);

                // Pancreas Function
                $userData[$index]['pancreas_function'] = $this->getData('pancreas_function', $row);

                // Liver Congestion
                $userData[$index]['liver_congestion'] = $this->getData('liver_congestion', $row);

                // Adrenal Function
                $userData[$index]['adrenal_function'] = $this->getData('adrenal_function', $row);

                // Gut Function
                $userData[$index]['gut_function'] = $this->getData('gut_function', $row);

                // Unique ID
                $userData[$index]['moxi_unique_id'] = $this->getData('moxi_unique_id', $row);
            }
        }

        if (!empty($userData)) {
            $modelClientTags = new ClientTag();

            $model          = new User();

            $staticPassword = $this->staticPassword;

            $startMonth     = $this->startMonth;

            $clientTagId    = $this->clientTagId;

            $weightUnits    = $this->weightUnits;

            $sleepLoops     = $this->sleepLoops;

            $chunkedLengths = $this->chunkedLengths;

            $moxiRolesId    = $this->moxiRolesId;

            $userData       = array_chunk($userData, $chunkedLengths);

            config(['logging.channels.moxi_assessment_user_import_log.path' => storage_path('logs/moxi_assessment_user_import_log/moxi_logs_' . date('Ymd') . '.log')]);

            foreach ($userData as $index => $chunked) {
                foreach ($chunked as $data) {
                    if (empty($data['moxi_unique_id'])) {
                        Log::channel('moxi_assessment_user_import_log')->info('User create issue. Error : moxi_unique_id is empty. Data : ' . json_encode($data));

                        continue;
                    }

                    // Check already exists.
                    $checkAlreadyExists = $model::where('moxi_unique_id', $data['moxi_unique_id'])->exists();

                    if ($checkAlreadyExists) {
                        Log::channel('moxi_assessment_user_import_log')->info('User create issue. Error : moxi_unique_id is already exists. Data : ' . json_encode($data));

                        continue;
                    }

                    $data['created_by']             = $model::$superadminId;

                    $data['password']               = Hash::make($staticPassword);

                    $data['password_confirmation']  = $data['password'];

                    $data['email']                  = strtolower($data['email']);

                    $data['weight_unit']            = $weightUnits;

                    $data['role_id']                = $moxiRolesId;

                    // Check exists.
                    $check = $this->checkEmailExists($data['email']);

                    if (!empty($check) && !$check->isEmpty()) {
                        $count                  = $check->count();

                        $data['created_month']  = Carbon::now()->addMonth(($startMonth + ($count)));

                        $data['moxi_count']     = ($count + 1);

                        $data['email']          = $this->createTaskSpecificEmailId($data['email']);

                        $validator              = $model::validators($data, true);

                        if (!$validator) {
                            Log::channel('moxi_assessment_user_import_log')->info('User create issue. Error : ' . Session()->get('error') . '. Data : ' . json_encode($data));

                            continue;
                        }

                        $create                 = $model::create($data);
                    } else {
                        $data['created_month']  = date('Y-') . $startMonth . date('-d');

                        $data['moxi_count']     = 1;

                        $validator              = $model::validators($data, true);

                        if (!$validator) {
                            Log::channel('moxi_assessment_user_import_log')->info('User create issue. Error : ' . Session()->get('error') . '. Data : ' . json_encode($data));

                            continue;
                        }

                        $create                 = $model::create($data);
                    }

                    if ($create) {
                        $find = $model::find($create->id);
                        BaseController::createLog($find, __("Created Moxi Assessment client {$find->name}"), ModelLog::CREATE, [], $find->toArray());

                        $id = $create->id;

                        // Assign role
                        if (!empty($data['role_id'])) {
                            $role = Role::find($data['role_id']);
                            if (!empty($role)) {
                                $create->assignRole($role);
                            }
                        }

                        $tagData    = [
                            'user_id' => $id,
                            'tag_id'  => $clientTagId
                        ];

                        $validator = $modelClientTags::validator($tagData, true);

                        if (!$validator) {
                            Log::channel('moxi_assessment_user_import_log')->info('UserTag create issue. Error : ' . Session()->get('error') . '. Data : ' . json_encode($tagData));

                            continue;
                        }

                        $modelClientTags::updateOrCreate($tagData, $tagData);
                    }
                }

                if (!empty($userData[$index + 1])) {
                    sleep($sleepLoops);
                }
            }
        }
    }

    private function getData($type, $row)
    {
        $return = [];

        if (!empty($type) && !empty($row)) {
            switch ($type) {
                case 'name':
                    $return = (!empty($row[1])) ? (string)$row[1] : NULL;
                    break;
                case 'surname':
                    $return = (!empty($row[2])) ? (string)$row[2] : NULL;
                    break;
                case 'email':
                    $return = (!empty($row[3])) ? (string)$row[3] : NULL;
                    break;
                case 'contact':
                    $return = (!empty($row[4])) ? (string)$row[4] : NULL;
                    break;
                case 'gender':
                    $return = !empty($row[5]) ? ((strtolower($row[5]) == "male") ? "m" : (strtolower($row[5]) == "female" ? "f" : "n")) : "n";
                    break;
                case 'age':
                    $return = (!empty($row[6])) ? (int)$row[6] : 0;
                    break;
                case 'weight':
                    $return = (!empty($row[8])) ? (int)$row[8] : 0;
                    break;
                case 'height':
                    $return = (!empty($row[7])) ? (string)$row[7] : 0;
                    break;
                case 'pancreas_function':
                    $return = (!empty($row[30])) ? (int)$row[30] : 0;
                    break;
                case 'liver_congestion':
                    $return = (!empty($row[41])) ? (int)$row[41] : 0;
                    break;
                case 'adrenal_function':
                    $return = (!empty($row[52])) ? (int)$row[52] : 0;
                    break;
                case 'gut_function':
                    $return = (!empty($row[63])) ? (int)$row[63] : 0;
                    break;
                case 'moxi_unique_id':
                    $return = (!empty($row[75])) ? (int)$row[75] : 0;
                    break;
            }
        }

        return $return;
    }

    public function checkEmailExists(string $email)
    {
        if (empty($email)) {
            return false;
        }

        $emailRegex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (preg_match($emailRegex, $email)) {
            $checkExists = User::
                                where(function($query) use($email) {
                                    $query->where('email', $email)
                                          ->orWhereRaw("CONCAT(SUBSTRING(email, 1, LOCATE('+', email) - 1), SUBSTRING(email, LOCATE('@', email))) = '{$email}'");
                                })
                                ->get();
        }

        return $checkExists;
    }

    public function createTaskSpecificEmailId(string $email)
    {
        if (empty($email)) {
            return false;
        }

        $emailRegex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (preg_match($emailRegex, $email)) {
            $emailArray = explode("@", $email);

            $email      = $emailArray[0] . "+Shiv" . generateRandomString(6) . "@" . $emailArray[1];
        }

        return $email;
    }
}
