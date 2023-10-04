<?php

namespace App\Http\Controllers;

use Cleantalk\Cleantalk;
use Cleantalk\CleantalkRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $us_state_abbrevs_names = array(
        'AL' => 'ALABAMA',
        'AK' => 'ALASKA',
        'AS' => 'AMERICAN SAMOA',
        'AZ' => 'ARIZONA',
        'AR' => 'ARKANSAS',
        'CA' => 'CALIFORNIA',
        'CO' => 'COLORADO',
        'CT' => 'CONNECTICUT',
        'DE' => 'DELAWARE',
        'DC' => 'DISTRICT OF COLUMBIA',
        'FM' => 'FEDERATED STATES OF MICRONESIA',
        'FL' => 'FLORIDA',
        'GA' => 'GEORGIA',
        'GU' => 'GUAM GU',
        'HI' => 'HAWAII',
        'ID' => 'IDAHO',
        'IL' => 'ILLINOIS',
        'IN' => 'INDIANA',
        'IA' => 'IOWA',
        'KS' => 'KANSAS',
        'KY' => 'KENTUCKY',
        'LA' => 'LOUISIANA',
        'ME' => 'MAINE',
        'MH' => 'MARSHALL ISLANDS',
        'MD' => 'MARYLAND',
        'MA' => 'MASSACHUSETTS',
        'MI' => 'MICHIGAN',
        'MN' => 'MINNESOTA',
        'MS' => 'MISSISSIPPI',
        'MO' => 'MISSOURI',
        'MT' => 'MONTANA',
        'NE' => 'NEBRASKA',
        'NV' => 'NEVADA',
        'NH' => 'NEW HAMPSHIRE',
        'NJ' => 'NEW JERSEY',
        'NM' => 'NEW MEXICO',
        'NY' => 'NEW YORK',
        'NC' => 'NORTH CAROLINA',
        'ND' => 'NORTH DAKOTA',
        'MP' => 'NORTHERN MARIANA ISLANDS',
        'OH' => 'OHIO',
        'OK' => 'OKLAHOMA',
        'OR' => 'OREGON',
        'PW' => 'PALAU',
        'PA' => 'PENNSYLVANIA',
        'PR' => 'PUERTO RICO',
        'RI' => 'RHODE ISLAND',
        'SC' => 'SOUTH CAROLINA',
        'SD' => 'SOUTH DAKOTA',
        'TN' => 'TENNESSEE',
        'TX' => 'TEXAS',
        'UT' => 'UTAH',
        'VT' => 'VERMONT',
        'VI' => 'VIRGIN ISLANDS',
        'VA' => 'VIRGINIA',
        'WA' => 'WASHINGTON',
        'WV' => 'WEST VIRGINIA',
        'WI' => 'WISCONSIN',
        'WY' => 'WYOMING',
        'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
        'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
        'AP' => 'ARMED FORCES PACIFIC'
    );

    public function cleanTalkHelper($request, $email, $phone, $name, $message)
    {
        $_SESSION['ct_submit_time'] = time();

        $config_url = env('CLEAN_TALK_URL','http://moderate.cleantalk.org/api2.0/');
        $auth_key = env('CLEAN_TALK_API','mymy2era7azydep');

        //        dd($config_url, $auth_key);

        // The facility in which to store the query parameters
        $ct_request = new CleantalkRequest();

        $ct_request->auth_key = $auth_key;
        $ct_request->agent = 'php-api';
        $ct_request->sender_email = $email;
        $ct_request->phone = $phone;
        $ct_request->sender_ip = $_SERVER['REMOTE_ADDR'] ?? request()->ip();
        $ct_request->sender_nickname = $name;
        $ct_request->js_on = 1;
        $ct_request->allow_links = 0;
        $ct_request->stoplist_check = 1;
        $ct_request->submit_time = time() - (int)$_SESSION['ct_submit_time'];
        $ct_request->message = $message;
        $ct = new Cleantalk();
        $ct->server_url = $config_url;
        $ct->auth_key = $auth_key;
        $ct->agent = 'php-api';
        // Check
        try {
            $ct_result = $ct->isAllowMessage($ct_request);

        } catch (\Exception $exception) {
            $ct_result = (object)['allow' => 1];
        }
        if (gettype($ct_result) == "boolean"){
            $ct_result = (object)['allow' => 1];

        }
        return $ct_result;

    }



    function getKeyByValue($value)
    {
        foreach ($this->us_state_abbrevs_names as $key => $val) {
            if ($val == strtoupper($value)) {
                return $key;
            }
        }
        return null; // value not found
    }

    function getValueByKey($key)
    {
        $value = null;
        try {
            $value = $this->us_state_abbrevs_names[$key];
        } catch (\Exception $exception) {

        }
        return \Str::title($value);
    }


}
