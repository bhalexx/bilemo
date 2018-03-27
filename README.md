# Bilemo API
Version 1.0.0

This project is a REST API for Bilemo to allow partners to display their mobile phones catalog.
This API was built with **Symfony 3.4**.
It's the 7th [OpenClassRooms](https://openclassrooms.com/) PHP/Symfony Developer project.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c42fc2dbed964964985ca34c03c99d7c)](https://www.codacy.com/app/bhalexx/bilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bhalexx/bilemo&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/7669fe651a7626557f7e/maintainability)](https://codeclimate.com/github/bhalexx/bilemo/maintainability)
[![Build Status](https://travis-ci.org/bhalexx/bilemo.svg?branch=master)](https://travis-ci.org/bhalexx/bilemo)

---

As you can see there are two folders:
- [diagrams folder](https://github.com/bhalexx/bilemo/tree/master/diagrams) where you will find project related usefull diagrams.
- [api folder](https://github.com/bhalexx/bilemo/tree/master/api) where you will find REST API source code.

## API instructions
Below the instructions to use Bilemo REST API from your application.

### Prerequisites
- PHP >=7.0.10
- MySQL
- PHPUnit 6.5
- [Composer](https://getcomposer.org/) to install Symfony 3.4 and project dependencies

### Dependencies
This project uses:
- [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle) to simplify REST API creation
- [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle) to manage Bilemo partners OAuthentication
- [JMSSerializerBundle](https://github.com/schmittjoh/JMSSerializerBundle) to easily serialize, and deserialize data (needed by BazingaHateoasBundle)
- [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle) to generate API documentation
- [BazingaHateoasBundle](https://github.com/willdurand/BazingaHateoasBundle) to implement representations for HATEOAS REST

Those dependencies are included in composer.json.

### Installation
1. Clone this repository on your local machine by using this command line in your folder `git clone https://github.com/bhalexx/bilemo.git`.
2. In project folder open a new terminal window and execute command line `composer install`.
3. Rename `bilemo/app/config/parameters.yml.dist` in `bilemo/app/config/parameters.dist` and edit database parameters with yours.
4. Execute command line `php bin/console bilemo:fixtures:load`. This command will create database and load some fixtures.
5. Your project is now up to date!

### Authentication to access API
This API is restricted to Bilemo partners. When a Bilemo admin adds your application as a new partner, your credentials are sent by email.

#### Become a Bilemo partner
_**For demo**_ you can also run this command to create your application as a **Bilemo partner**:

`php bin/console bilemo:partner:create {YourApplicationName} {YourPassword} {YourEmail} {YourRedirectURI}`

This command will output:
```
username: {YourApplicationName}
password: {YourPassword}
client_id: {YourClientId}
client_secret: {YourClientSecret}
```
Now that you're a Bilemo partner, you need to get an access token to access API methods.

Set `Content-Type: application/json` in your request headers and do a POST request on `/oauth/v2/token` with those JSON parameters in request body:
```
{
  "grant_type": "password",
  "client_id": "{YourClientId}",
  "client_secret": "{YourClientSecret}",
  "username": "{YourApplicationName}",
  "password": "{YourPassword}"
}
```

You will get an `access_token` and a `refresh_token` and you can now access API endpoints by setting those parameters in each request headers:
```
Content-Type: application/json
Authorization: Bearer {YourAccessToken}
```

Your access token expires after 10 minutes. To get a new access token use your refresh token in your POST request headers on `/oauth/v2/token` with those parameters:
```
{
  "grant_type": "refresh_token",
  "client_id": "{YourClientId}",
  "client_secret": "{YourClientSecret}",
  "refresh_token": "{YourRefreshToken}"
}
```

Now you'ready to enjoy! Refer to documentation to use API endpoints according to your needs.

#### Become a Bilemo admin
_**Still for demo**_ you can also create your application as a **Bilemo administrator** by running the same command with the optional parameter `admin`:

`php bin/console bilemo:partner:create {YourApplicationName} {YourPassword} {YourEmail} {YourRedirectURI} admin`

### Documentation
This API project is as documented as possible, so you can find:
- a full documentation of API methods by adding `/api/doc` at the end of your API URI.
- and some [diagrams](https://github.com/bhalexx/bilemo/tree/master/diagrams)

### Related projects
Two other projects were created to show how to use this API:
- [Bilemo Admin](https://github.com/bhalexx/bilemo_admin) - the Bilemo administration application
- [OpenMobileRooms](https://github.com/bhalexx/openmobilerooms) - a Bilemo B2B partner application

