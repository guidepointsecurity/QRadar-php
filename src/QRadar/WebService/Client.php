<?php

namespace QRadar\WebService;

use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

class Client {

    private $host;
    private $token;
    private $guzzleClient;

    /**
    * Constructor.
    *
    * @param string $host Hostname for API
    * @param string $token QRadar API Token to use
    * @param object $guzzleClient Optional Guzzle Client to use (for unit testing)
    */
    public function __construct($host, $token = null, $guzzleClient = null) {
        $this->host = $host;
        $this->token = $token;
        $this->guzzleClient = $guzzleClient;
    }

    /**
    * This method calls the QRadar siem/offenses endpoint.
    * 
    * @return array \QRadar\Model\Offense
    */
    public function getOffenses() {
        $uri = implode('/', array($this->baseUri(), 'siem', 'offenses'));
        
        $client = $this->getClient();
        $request = $client->get($uri);
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
        $uri = implode('/', array($this->baseUri(), 'siem', 'offenses', $offense_id));

        $client = $this->getClient();        
        $request = $client->get($uri);
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
            'Version' => '3.0',
            'Allow-Experimental' => 'true', 
            'SEC' => $this->token
        );
        $client->setDefaultHeaders($default_headers);
        return $client;
    }

    /**
     * Set's the internal Guzzle client used by the QRadar Client for future requests.
     * 
     * @param Guzzle\Http\Client $client modified Guzzle\Http\Client 
     */
    public function setClient($guzzleClient) {
        $this->guzzleClient = $guzzleClient;
    }

    private function baseUri() {
        return 'https://' . $this->host . '/api';
    }
}