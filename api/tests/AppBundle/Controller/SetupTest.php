<?php

	namespace Tests\AppBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

	abstract class SetupTest extends WebTestCase
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

		protected function getBilemoAccessToken()
		{
			$bilemoApplication = $this->em->getRepository('AppBundle:Application')->findOneByUsername('Bilemo');
			$bilemo = $this->em->getRepository('AppBundle:Client')->findOneByApplication($bilemoApplication);

			$oauthHeaders = [
	            "client_id" 	=> $bilemo->getPublicId(),
	            "client_secret" => $bilemo->getSecret(),
	            "grant_type" 	=> "password",
	            "username" 		=> $bilemoApplication->getUsername(),
	            "password" 		=> 'password'
	        ];	 

			$this->client->request('GET', '/oauth/v2/token', $oauthHeaders);

	        $data = $this->client->getResponse()->getContent();
	        $json = json_decode($data);
	        $accessToken = $json->{'access_token'};

	        return $accessToken;
		}

		protected function tearDown()
		{
			$this->client = null;
			$this->container = null;
			$this->em = null;
		}
	}

