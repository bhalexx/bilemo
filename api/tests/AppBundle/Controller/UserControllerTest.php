<?php

    namespace Tests\AppBundle\Controller;

    use AppBundle\Entity\User;

    class UserControllerTest extends SetupTest
    {
        public function testGetUserListWithoutToken()
        {
            $this->client->request('GET', '/api/users');

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testGetUserListAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/users', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testGetUserListAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/users', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());
        }

        public function testViewUserWithoutToken()
        {
            $this->client->request('GET', '/api/users/1');

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testViewUserNotExisting()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/users/0', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(404, $response->getStatusCode());
        }

        public function testViewUserAsPartner()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/users/1', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(403, $response->getStatusCode());
        }

        public function testViewUserAsBilemo()
        {
            $accessToken = $this->getBilemoAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('GET', '/api/users/1', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());

            $user = json_decode($response->getContent(), true);
            $this->assertEquals("bhalexx", $user["username"]);
        }

        public function testCreateUserWithoutToken()
        {
            $user = array(
                "username" => "New user",
                "firstname" => "John",
                "lastname" => "Doe",
                "email" => "john.doe@mail.com"
            );

            $this->client->request('POST', '/api/users', array(), array(), array(), json_encode($user));

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testCreateUser()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $user = array(
                "username" => "New user",
                "firstname" => "John",
                "lastname" => "Doe",
                "email" => "john.doe@mail.com"
            );

            $this->client->request('POST', '/api/users', array(), array(), $headers, json_encode($user));

            $response = $this->client->getResponse();

            $this->assertEquals(201, $response->getStatusCode());
        }

        public function testUpdateUserWithoutToken()
        {
            $user = array(
                "username" => "johndoe",
                "firstname" => "John",
                "lastname" => "Doe",
                "email" => "john.doe@mail.com",
                "phone" => "0601010101"
            );

            $this->client->request('PUT', '/api/users/1', array(), array(), array(), json_encode($user));

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testUpdateUser()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $user = array(
                "username" => "johndoe",
                "firstname" => "John",
                "lastname" => "Doe",
                "email" => "john.doe@mail.com",
                "phone" => "0601010101"
            );

            $this->client->request('PUT', '/api/users/1', array(), array(), $headers, json_encode($user));

            $response = $this->client->getResponse();
            $user = json_decode($response->getContent(), true);

            $this->assertEquals(200, $response->getStatusCode());

            $this->assertEquals("johndoe", $user["username"]);
        }

        public function testDeleteUserWithoutToken()
        {
            $this->client->request('DELETE', '/api/users/1', array(), array(), array());

            $response = $this->client->getResponse();

            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testDeleteUser()
        {
            $accessToken = $this->getPartnerAccessToken();
            $headers = $this->getHeaders($accessToken);

            $this->client->request('DELETE', '/api/users/1', array(), array(), $headers);

            $response = $this->client->getResponse();

            $this->assertEquals(204, $response->getStatusCode());
        }
    }