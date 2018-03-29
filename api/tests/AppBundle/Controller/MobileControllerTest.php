<?php

    namespace Tests\AppBundle\Controller;

    use AppBundle\Entity\Mobile;

    class MobileControllerTest extends SetupTest
    {
        public function testGetMobileListWithoutToken()
        {
            $uri = sprintf('/api/mobiles?limit=%d&offset=%s', 5, 1);

            $this->client->request('GET', $uri);

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testGetMobileList()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);
            $uri = sprintf('/api/mobiles?limit=%d&offset=%s', 5, 1);

            $this->client->request('GET', $uri, array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());
        }

        public function testViewMobileWithoutToken()
        {
            $this->client->request('GET', '/api/mobiles/1');

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testViewMobileNotExisting()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/mobiles/0', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(404, $response->getStatusCode());
        }

        public function testViewMobile()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/mobiles/1', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());

            $mobile = json_decode($response->getContent(), true);
            $this->assertEquals("iPhone SE Argent", $mobile["name"]);
        }
    }