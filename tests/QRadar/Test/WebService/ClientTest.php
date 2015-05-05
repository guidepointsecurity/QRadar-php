<?php

namespace QRadar\Test\WebService;

use QRadar\WebService\Client;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class ClientTest extends \PHPUnit_Framework_TestCase {


    private $testToken = 'abcd-efgh-hijk-lmnop';

    public function testGetOffenses() {
        $offenses = array(
            array(
                'id' => 1,
                'description' => '',
                'assigned_to' => '',
                'categories' => array('', ''),
                'category_count' => 2,
                'policy_category_count' => 0,
                'security_category_count' => 0,
                'close_time' => 0,
                'closing_user' => '',
                'closing_reason_id' => 0,
                'credibility' => 0,
                'relevance' => 0,
                'severity' => 0,
                'magnitude' => 0,
                'destination_networks' => array('', ''),
                'source_network' => 'abc',
                'device_count' => 0,
                'event_count' => 0,
                'flow_count' => 0,
                'inactive' => true,
                'last_udpated_time' => 0,
                'local_destination_count' => 0,
                'offense_source' => 'xyz',
                'offense_type' => 0,
                'protected' => false,
                'follow_up' => false,
                'remote_destination_count' => 0,
                'source_count' => 0,
                'start_time' => 0,
                'status' => 'open',
                'username_count' => 0
                ),
            array(
                'id' => 2
                )
            );
        
        $response = $this->response($offenses);
        $client = $this->client($response);
        
        $offenses = $client->getOffenses();

        $this->assertCount(2, $offenses);
        $this->assertInstanceOf('QRadar\Model\Offense', $offenses[0]);
        $this->assertEquals(1, $offenses[0]->id);
    }

    public function testHasCorrectOffenseUri() {
        $response = $this->response(array());
        $plugin = new MockPlugin();
        $plugin->addResponse($response);

        $guzzleClient = new GuzzleClient();
        $guzzleClient->addSubscriber($plugin);

        $client = new Client('localhost', $this->testToken, $guzzleClient);
        $client->getOffenses();

        $all_requests = $plugin->getReceivedRequests();
        $request = $all_requests[0];
        
        $this->assertEquals(
            'https://localhost/api/siem/offenses',
            $request->getUrl()
        );

        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    public function testHasCorrectOffenseDetailUri() {
        $response = $this->response(array());
        $plugin = new MockPlugin();
        $plugin->addResponse($response);

        $guzzleClient = new GuzzleClient();
        $guzzleClient->addSubscriber($plugin);

        $client = new Client('localhost', $this->testToken, $guzzleClient);
        $client->getOffenseDetail(123);

        $all_requests = $plugin->getReceivedRequests();
        $request = $all_requests[0];
        
        $this->assertEquals(
            'https://localhost/api/siem/offenses/123',
            $request->getUrl()
        );

        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    public function testClientSetsAuthorizationToken() {
        $response = $this->response(array());
        $client = $this->client($response);
        
        $guzzleClient = $client->getClient();
                
        $this->assertTrue($guzzleClient->get('/')->hasHeader('sec'));        
        
        $sec_header = $guzzleClient->get('/')->getHeader('sec');
        $this->assertEquals($this->testToken, $sec_header);
    }

    public function testClientSetsExperimentalHeader() {
        $response = $this->response(array());
        $client = $this->client($response);
        
        $guzzleClient = $client->getClient();
                
        $this->assertTrue($guzzleClient->get('/')->hasHeader('allow-experimental'));                
        $header = $guzzleClient->get('/')->getHeader('allow-experimental');
        $values = $header->toArray();

        // For the allow-experimental header to work, the value MUST be a string
        // and not a boolean value. If this is not set properly, a bunch of
        // endpoints will return a 404.
        $experimental_value = $values[0];
        $this->assertTrue(is_string($experimental_value));
        $this->assertEquals('true', $experimental_value);
    }

    public function testClientSetsVersionHeader() {
        $response = $this->response(array());
        $client = $this->client($response);
        
        $guzzleClient = $client->getClient();
                
        $this->assertTrue($guzzleClient->get('/')->hasHeader('version'));                
        $header = $guzzleClient->get('/')->getHeader('version');
        $this->assertTrue($header->hasValue('3.0'));   
    }

    public function testGetOffenseDetail() {
        $offense_detail = array(
            'id' => 1,
            'description' => '',
            'assigned_to' => '',
            'categories' => array('', ''),
            'category_count' => 2,
            'policy_category_count' => 0,
            'security_category_count' => 0,
            'close_time' => 0,
            'closing_user' => '',
            'closing_reason_id' => 0,
            'credibility' => 0,
            'relevance' => 0,
            'severity' => 0,
            'magnitude' => 0,
            'destination_networks' => array('', ''),
            'source_network' => 'abc',
            'device_count' => 0,
            'event_count' => 0,
            'flow_count' => 0,
            'inactive' => true,
            'last_udpated_time' => 0,
            'local_destination_count' => 0,
            'offense_source' => 'xyz',
            'offense_type' => 0,
            'protected' => false,
            'follow_up' => false,
            'remote_destination_count' => 0,
            'source_count' => 0,
            'start_time' => 0,
            'status' => 'open',
            'username_count' => 0
            );        
        
        $response = $this->response($offense_detail);
        $client = $this->client($response);
        
        $offense = $client->getOffenseDetail(1);

        $this->assertInstanceOf('QRadar\Model\Offense', $offense);
        $this->assertEquals(1, $offense->id);
    }

    private function client($response) {
        $plugin = new MockPlugin();
        $plugin->addResponse($response);

        $guzzleClient = new GuzzleClient();
        $guzzleClient->addSubscriber($plugin);

        $client = new Client('localhost', $this->testToken, $guzzleClient);
        return $client;   
    }

    private function response($body) {
        $headers = array();
        $headers['Content-Type'] = 'application/json';        
        $status = 200;
        return new Response($status, $headers, json_encode($body));   
    }

    public function testTest() {
        $this->assertEquals(1, 1);
    }
}
