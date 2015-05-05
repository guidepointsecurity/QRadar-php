<?php

namespace QRadar\WebService;

use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

class Client {

    private $token;
    private $guzzleClient;

    /**
    * Constructor.
    *
    * @param string $token QRadar API Token to use
    * @param object $guzzleClient Optional Guzzle Client to use (for unit testing)
    */
    public function __construct($token = null, $guzzleClient = null) {
        $this->token = $token;
        $this->guzzleClient = $guzzleClient;
    }

    /**
    * This method calls the QRadar siem/offenses endpoint.
    * 
    * @return array \QRadar\Model\Offense
    */
    public function getOffenses() {
        $client = $this->getClient();
        $request = $client->get('siem/offenses');
        $response = $request->send();
        $response_objects = $response->json();      
        $offense_instances = array();
        foreach ($response_objects as $o) {
            $class = "QRadar\\Model\\Offense";
            $offense = new $class($o);
            array_push($offense_instances, $offense);
        }
        return $offense_instances;
    }

    public function getOffenseDetail($offense_id) {
        $client = $this->getClient();
        $url = "siem/offenses/$offense_id";
        $request = $client->get($url);
        $response = $request->send();
        $response_object = $response->json();       
        $class = "QRadar\\Model\\Offense";
        $offense = new $class($response_object);            
        return $offense;    
    }

    public function getClient() {
        $client = $this->guzzleClient ? $this->guzzleClient : new GuzzleClient();
        $default_headers = array(
            'Accept' => 'application/json', 
            'SEC' => $this->token
        );
        $client->setDefaultHeaders($default_headers);
        return $client;
    }
}