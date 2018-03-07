<?php

	namespace Tests\AppBundle\Controller;

	use AppBundle\Entity\Manufacturer;
	use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

	class ManufacturerControllerTest extends WebTestCase
	{
		public function testGetManufacturerListWithoutToken()
	    {
	        $this->client->request('GET', '/api/manufacturers');

	        $response = $this->client->getResponse();

	        $this->assertEquals(401, $response->getStatusCode());
	    }

	    // public function testGetManufacturerList()
	    // {
	    //     $accessToken = $this->getBilemoAccessToken();

	    //     $headers = array(
	    //         "CONTENT_TYPE" => "application/json",
	    //         "HTTP_Authorization" => "Bearer ".$accessToken
	    //     );

	    //     $this->client->request('GET', '/api/manufacturers', array(), array(), $headers);

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(200, $response->getStatusCode());
	    // }

	    // public function testViewManufacturerWithoutToken()
	    // {
	    // 	$this->client->request('GET', '/api/manufacturers/1');

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(401, $response->getStatusCode());
	    // }

	    // public function testViewManufacturerNotExisting()
	    // {
	    // 	$accessToken = $this->getBilemoAccessToken();

	    //     $headers = array(
	    //         "CONTENT_TYPE" => "application/json",
	    //         "HTTP_Authorization" => "Bearer ".$accessToken
	    //     );

	    // 	$this->client->request('GET', '/api/manufacturers/0', array(), array(), $headers);

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(404, $response->getStatusCode());
	    // }

	    // public function testViewManufacturer()
	    // {
	    // 	$accessToken = $this->getBilemoAccessToken();

	    //     $headers = array(
	    //         "CONTENT_TYPE" => "application/json",
	    //         "HTTP_Authorization" => "Bearer ".$accessToken
	    //     );

	    //     $this->client->request('GET', '/api/manufacturers/1', array(), array(), $headers);

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(200, $response->getStatusCode());

	    //     $manufacturer = json_decode($response->getContent(), true);
     //    	$this->assertEquals("Apple", $manufacturer["name"]);
	    // }

	    // public function testCreateManufacturerWithoutToken()
	    // {
	    // 	$manufacturer = array(
	    //     	"name" => "New manufacturer"
	    //     );

	    // 	$this->client->request('POST', '/api/manufacturers', array(), array(), array(), json_encode($manufacturer));

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(401, $response->getStatusCode());
	    // }	

	    // public function testCreateManufacturer()
	    // {
	    // 	$accessToken = $this->getBilemoAccessToken();

	    //     $headers = array(
	    //         "CONTENT_TYPE" => "application/json",
	    //         "HTTP_Authorization" => "Bearer ".$accessToken
	    //     );

	    //     $manufacturer = array(
	    //     	"name" => "New manufacturer"
	    //     );

	    // 	$this->client->request('POST', '/api/manufacturers', array(), array(), $headers, json_encode($manufacturer));

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(201, $response->getStatusCode());
	    // }

	    // public function testUpdateManufacturerWithoutToken()
	    // {
	    // 	$newManufacturer = array(
	    //     	"name" => "Appleee"
	    //     );

	    // 	$this->client->request('PUT', '/api/manufacturers/1', array(), array(), array(), json_encode($newManufacturer));

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(401, $response->getStatusCode());
	    // }

	    // public function testUpdateManufacturer()
	    // {
	    // 	$accessToken = $this->getBilemoAccessToken();

	    //     $headers = array(
	    //         "CONTENT_TYPE" => "application/json",
	    //         "HTTP_Authorization" => "Bearer ".$accessToken
	    //     );

	    //     $newManufacturer = array(
	    //     	"name" => "Appleee"
	    //     );

	    // 	$this->client->request('PUT', '/api/manufacturers/1', array(), array(), $headers, json_encode($newManufacturer));

	    //     $response = $this->client->getResponse();
	    //     $manufacturer = json_decode($response->getContent(), true);

	    //     $this->assertEquals(200, $response->getStatusCode());

     //    	$this->assertEquals("Appleee", $manufacturer["name"]);
	    // }

	    // public function testDeleteManufacturerWithoutToken()
	    // {
	    // 	$this->client->request('DELETE', '/api/manufacturers/1', array(), array(), array());

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(401, $response->getStatusCode());
	    // }

	    // public function testDeleteManufacturer()
	    // {
	    // 	$accessToken = $this->getBilemoAccessToken();

	    //     $headers = array(
	    //         "CONTENT_TYPE" => "application/json",
	    //         "HTTP_Authorization" => "Bearer ".$accessToken
	    //     );

	    // 	$this->client->request('DELETE', '/api/manufacturers/1', array(), array(), $headers);

	    //     $response = $this->client->getResponse();

	    //     $this->assertEquals(204, $response->getStatusCode());
	    // }    
	}