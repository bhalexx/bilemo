<?php

    namespace Tests\AppBundle\Controller;

    use AppBundle\Entity\Application;

    class ApplicationControllerTest extends SetupTest
    {
        public function testGetApplicationListWithoutToken()
        {
            $this->client->request('GET', '/api/applications');

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testGetApplicationListAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/applications', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testGetApplicationListAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/applications', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());
        }

        public function testViewApplicationWithoutToken()
        {
            $this->client->request('GET', '/api/applications/1');

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testViewApplicationNotExisting()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/applications/0', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(404, $response->getStatusCode());
        }

        public function testViewApplicationAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/applications/1', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testViewApplicationAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/applications/1', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());

            $application = json_decode($response->getContent(), true);
            $this->assertEquals("Bilemo", $application["username"]);
        }

        public function testCreateApplicationWithoutToken()
        {
            $application = array(
                "username" => "New application",
                "email" => "john.doe@mail.com",
                "uri" => "http://application.com",
                "roles" => ["ROLE_APPLICATION"]
            );

            $this->client->request('POST', '/api/applications', array(), array(), array(), json_encode($application));

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testCreateApplicationAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $application = array(
                "username" => "New application",
                "email" => "john.doe@mail.com",
                "uri" => "http://application.com",
                "roles" => ["ROLE_APPLICATION"]
            );

            $this->client->request('POST', '/api/applications', array(), array(), $headers, json_encode($application));

            $response = $this->client->getResponse();

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testCreateApplicationAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $application = array(
                "username" => "New application",
                "email" => "john.doe@mail.com",
                "uri" => "http://application.com",
                "roles" => ["ROLE_APPLICATION"]
            );

            $this->client->request('POST', '/api/applications', array(), array(), $headers, json_encode($application));

            $response = $this->client->getResponse();

            $this->assertEquals(201, $response->getStatusCode());
        }

        public function testUpdateApplicationWithoutToken()
        {
            $application = array(
                "username" => "New application name",
                "email" => "newpartner@mail.com",
                "uri" => "http://partner.com",
                "roles" => ["ROLE_APPLICATION"]
            );

            $this->client->request('PUT', '/api/applications/3', array(), array(), array(), json_encode($application));

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testUpdateApplicationAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $application = array(
                "username" => "New application name",
                "email" => "newpartner@mail.com",
                "uri" => "http://partner.com",
                "roles" => ["ROLE_APPLICATION"]
            );

            $this->client->request('PUT', '/api/applications/3', array(), array(), $headers, json_encode($application));

            $response = $this->client->getResponse();
            $application = json_decode($response->getContent(), true);

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testUpdateApplicationAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $application = array(
                "username" => "New application name",
                "email" => "newpartner@mail.com",
                "uri" => "http://partner.com",
                "roles" => ["ROLE_APPLICATION"]
            );

            $this->client->request('PUT', '/api/applications/3', array(), array(), $headers, json_encode($application));

            $response = $this->client->getResponse();
            $application = json_decode($response->getContent(), true);

            $this->assertEquals(200, $response->getStatusCode());

            $this->assertEquals("New application name", $application["username"]);
        }

        public function testDeleteApplicationWithoutToken()
        {
            $this->client->request('DELETE', '/api/applications/3', array(), array(), array());

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testDeleteApplicationAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('DELETE', '/api/applications/3', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testDeleteApplicationAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('DELETE', '/api/applications/3', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(204, $response->getStatusCode());
        }
    }