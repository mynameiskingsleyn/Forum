<?php
namespace Forum\Classes\Soap;

use \SoapClient;
use Illuminate\Support\Facades\Storage;

class Client
{
    private $location;
    private $wsdlUrl;

    public function __construct()
    {
        $this->wsdlUrl=null;
        //$url = ;
        $this->location = Route('SoapServer.index');
        //$this->location = 'https://webdev.admin.miamioh.edu/phpapps/chalkAndWire/accountCreation/accountCreationService.php';
      //$this->location = 'https://localhost:9443/accounts/chalkandwire/accountCreation/accountCreationService';
      //$this->location = route('ChalkSoapServer');
      //$this->location = $url.config('chalkandwire.route_path').'/accountCreation/accountCreationService';
    }

    /**
     * @param mixed $wsdlUrl
     */
    public function setWsdlUrl($wsdlUrl)
    {
        $this->wsdlUrl = $wsdlUrl;
    }
    /**
     * @return mixed
     */
    public function getWsdlUrl()
    {
        return $this->wsdlUrl;
    }


    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getSoapClient()
    {
        //dd($this->location);
        $soapClient = null;
        if (!$soapClient) {
            try {
                ini_set('soap.wsdl_cache_enabled', '0');   // disable WSDL cache
                ini_set('soap.wsdl_cache_ttl', 0);
                $soapClient = new SoapClient(
                  $this->wsdlUrl,
                  [
                      //'uri' => config('chalkandwire.route_path').'/accountCreation/accountCreationService',
                      //'location' => url(config('chalkandwire.route_path').'/accountCreation/accountCreationService'),
                      'location' => $this->location,
                      'uri'=> $this->location,
                      'trace' => 1,
                      'soap_version' => SOAP_1_2,
                      'encoding' => 'UTF8',
                      'stream_context' => stream_context_create(
                          [
                            'ssl'=> [
                              'verify_peer'=>false,
                              'verify_peer_name'=>false,
                            ]
                          ]

                        )
                  ]
              );
            } catch (Exception $e) {
                Log::error('client error');
                $this->errorExit('Error encountered while creating SOAP client: ' . $e->getMessage());
            }
        }
        //dd($soapClient);
        //dd($soapClient->getFunctions());
        //$soapClient->url = $this->uri;
        $this->instance = $soapClient;
        return $soapClient;
    }
    public function getDate($id_array =[])
    {
        //return "new stuff to try";
        //dd('here yall');
        //$class_methods = get_class_methods($this->instance);
        //dd($class_methods);

        //dd($this->instance->__getFunctions());
        //dd($class_methods);
        //return $this->instance->FahrenheitToCelsius($id_array);
        return $this->instance->getDate($id_array);
        //return $this->instance->__soapCall('getDate', $id_array);
        // try {
        //     return $this->instance->__soapCall('getData', $id_array);
        // } catch (\SoapFault $fault) {
        //     dd('Soap fault occured '.$fault);
        // }
    }
    public function user_update()
    {
        $this->instance->user_update();
    }
}
