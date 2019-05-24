<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\Classes\Soap\Server;

class SoapServerController extends Controller
{
    protected $uri;
    public function __construct()
    {
        ini_set('load.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        ini_set('default_socket_timeout', 300);
        ini_set('max_execution_time', 0);
        //ini_set()
        $this->uri = Route('SoapServer.index');
    }
    public function index()
    {
        $params = array(
                //'uri'=>url('/soap/server')
                'uri'=>$this->uri
              );
        $server = new \SoapServer(null, $params);
        $server->setClass(Server::class);
        $server->handle();

        //dd($server->getFunctions());
    }
}
