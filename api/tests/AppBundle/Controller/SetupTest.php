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
			$bilemoClient = $this->em->getRepository('AppBundle:Client')->findOneByApplication($bilemoApplication);

			return $this->getAccessToken($bilemoApplication, $bilemoClient);
		}

		protected function getPartnerAccessToken()
		{
			$partnerApplication = $this->em->getRepository('AppBundle:Application')->findOneByUsername('OpenMobileRooms');
			$partnerClient = $this->em->getRepository('AppBundle:Client')->findOneByApplication($partnerApplication);

			return $this->getAccessToken($partnerApplication, $partnerClient);
		}

		protected function getAccessToken($application, $client)
		{
			$oauthHeaders = [
	            "client_id" 	=> $client->getPublicId(),
	            "client_secret" => $client->getSecret(),
	            "grant_type" 	=> 'password',
	            "username" 		=> $application->getUsername(),
	            "password" 		=> 'password'
	        ];

			$this->client->request('GET', '/oauth/v2/token', $oauthHeaders);

	        $data = $this->client->getResponse()->getContent();
	        $json = json_decode($data);
	        $accessToken = $json->{'access_token'};

	        return $accessToken;
		}

		protected function getHeaders($accessToken)
		{
			return array(
                "CONTENT_TYPE" => "application/json",
                "HTTP_Authorization" => "Bearer ".$accessToken
            );
		}

		protected function tearDown()
		{
			$this->client = null;
			$this->container = null;
			$this->em = null;
		}
	}

