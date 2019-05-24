<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\Classes\Soap\Client;

class SoapClientController extends Controller
{
    public function index()
    {
        $input=['tank','john'];
        $account = new Client();

        $client = $account->getSoapClient();
        //$location = $client->getLocation();
        //dd($location);
        //dd($account);
        $result = $account->user_update();
        //dd($location);
        //$result = $client->__soapCall("update_user", $input, ['location' => $location]);

        //dd($client->getFunctions());
        //dd($client);
        print_r($result);
    }
}
