<?php

namespace QRadar\Test\WebService;

use QRadar\WebService\Client;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class ClientTest extends \PHPUnit_Framework_TestCase {


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

		$headers = array();
		$headers['Content-Type'] = 'application/json';

		$body = json_encode($offenses);
		$status = 200;
		$response = new Response($status, $headers, $body);

		$plugin = new MockPlugin();
		$plugin->addResponse($response);

		$guzzleClient = new GuzzleClient();
		$guzzleClient->addSubscriber($plugin);

		$client = new Client($guzzleClient);
		
		$offenses = $client->getOffenses();

		$this->assertCount(2, $offenses);
		$this->assertInstanceOf('QRadar\Model\Offense', $offenses[0]);

		$this->assertEquals(1, $offenses[0]->id);
	}

	public function testTest() {
		$this->assertEquals(1, 1);
	}
}
