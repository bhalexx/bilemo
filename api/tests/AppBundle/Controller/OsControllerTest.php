<?php

	namespace Tests\AppBundle\Controller;

	use AppBundle\Entity\Os;
	use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

	class OsControllerTest extends WebTestCase
	{
		protected $client;
		protected $container;
		protected $em;

		protected function setUp()
		{
			$this->client = static::createClient(); 
			$this->container = $this->client->getContainer();
	        $this->em = $this->container->get('doctrine')->getManager();	        
		}

		protected function tearDown()
		{
			$this->client = null;
			$this->container = null;
			$this->em = null;
		}

		public function testGetOsListWithoutToken()
	    {
	        $this->client->request('GET', '/api/os');

	        $response = $this->client->getResponse();

	        $this->assertEquals(401, $response->getStatusCode());
	    }

	 //    public function testGetOsList()
	 //    {
	 //        $accessToken = $this->getBilemoAccessToken();

	 //        $headers = array(
	 //            "CONTENT_TYPE" => "application/json",
	 //            "HTTP_Authorization" => "Bearer ".$accessToken
	 //        );

	 //        $this->client->request('GET', '/api/os', array(), array(), $headers);

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(200, $response->getStatusCode());
	 //    }

	 //    public function testViewOsWithoutToken()
	 //    {
	 //    	$this->client->request('GET', '/api/os/1');

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(401, $response->getStatusCode());
	 //    }

	 //    public function testViewOsNotExisting()
	 //    {
	 //    	$accessToken = $this->getBilemoAccessToken();

	 //        $headers = array(
	 //            "CONTENT_TYPE" => "application/json",
	 //            "HTTP_Authorization" => "Bearer ".$accessToken
	 //        );

	 //    	$this->client->request('GET', '/api/os/0', array(), array(), $headers);

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(404, $response->getStatusCode());
	 //    }

	 //    public function testViewOs()
	 //    {
	 //    	$accessToken = $this->getBilemoAccessToken();

	 //        $headers = array(
	 //            "CONTENT_TYPE" => "application/json",
	 //            "HTTP_Authorization" => "Bearer ".$accessToken
	 //        );

	 //        $this->client->request('GET', '/api/os/1', array(), array(), $headers);

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(200, $response->getStatusCode());

	 //        $os = json_decode($response->getContent(), true);
  //       	$this->assertEquals("iOS", $os["name"]);
	 //    }

	 //    public function testCreateOsWithoutToken()
	 //    {
	 //    	$os = array(
	 //        	"name" => "New OS"
	 //        );

	 //    	$this->client->request('POST', '/api/os', array(), array(), array(), json_encode($os));

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(401, $response->getStatusCode());
	 //    } 

	 //    public function testCreateOs()
	 //    {
	 //    	$accessToken = $this->getBilemoAccessToken();

	 //        $headers = array(
	 //            "CONTENT_TYPE" => "application/json",
	 //            "HTTP_Authorization" => "Bearer ".$accessToken
	 //        );

	 //        $os = array(
	 //        	"name" => "New OS"
	 //        );

	 //    	$this->client->request('POST', '/api/os', array(), array(), $headers, json_encode($os));

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(201, $response->getStatusCode());
	 //    }    

	 //    public function testUpdateOsWithoutToken()
	 //    {
	 //    	$newOs = array(
	 //        	"name" => "iOSSS"
	 //        );

	 //    	$this->client->request('PUT', '/api/os/1', array(), array(), array(), json_encode($newOs));

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(401, $response->getStatusCode());
	 //    }

	 //    public function testUpdateOs()
	 //    {
	 //    	$accessToken = $this->getBilemoAccessToken();

	 //        $headers = array(
	 //            "CONTENT_TYPE" => "application/json",
	 //            "HTTP_Authorization" => "Bearer ".$accessToken
	 //        );

	 //        $newOs = array(
	 //        	"name" => "iOSSS"
	 //        );

	 //    	$this->client->request('PUT', '/api/os/1', array(), array(), $headers, json_encode($newOs));

	 //        $response = $this->client->getResponse();
	 //        $os = json_decode($response->getContent(), true);

	 //        $this->assertEquals(200, $response->getStatusCode());

  //       	$this->assertEquals("iOSSS", $os["name"]);
	 //    }

	 //    public function testDeleteOsWithoutToken()
	 //    {
	 //    	$this->client->request('DELETE', '/api/os/1', array(), array(), array());

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(401, $response->getStatusCode());
	 //    }

	 //    public function testDeleteOs()
	 //    {
	 //    	$accessToken = $this->getBilemoAccessToken();

	 //        $headers = array(
	 //            "CONTENT_TYPE" => "application/json",
	 //            "HTTP_Authorization" => "Bearer ".$accessToken
	 //        );

	 //    	$this->client->request('DELETE', '/api/os/1', array(), array(), $headers);

	 //        $response = $this->client->getResponse();

	 //        $this->assertEquals(204, $response->getStatusCode());
	 //    }
	}