<?php

namespace App\Libraries;

use Google_Client;
use Google_Service_Sheets;

class GoogleLibrary
{
    private $client, $authConfigPath, $tokenPath;
    public  $sheetId = false;

    public function __construct()
    {
        $this->client         = new Google_Client();
        $this->authConfigPath = storage_path(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION', NULL));
        // $this->tokenPath      = storage_path('token.json');
        $this->sheetId        = env('GOOGLE_SHEET_ID', false);
    }

    public function getService()
    {
        $client  = $this->getClient();
        $service = new Google_Service_Sheets($client);

        return $service;
    }

    public function getClient()
    {
        $client = $this->client;
        $client->setApplicationName(env('GOOGLE_APPLICATION_NAME', 'Brad Davidson'));
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');

        /*
         * The JSON auth file can be provided to the Google Client in two ways, one is as a string which is assumed to be the
         * path to the json file. This is a nice way to keep the creds out of the environment.
         *
         * The second option is as an array. For this example I'll pull the JSON from an environment variable, decode it, and
         * pass along.
         */
        $client->setAuthConfig($this->authConfigPath);

        return $client;
    }
}
