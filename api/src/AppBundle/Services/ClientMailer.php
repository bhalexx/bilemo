<?php

namespace AppBundle\Services;

use AppBundle\Entity\Client;

class ClientMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $templating;

    public function __construct(\Swift_Mailer $mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendCredentialsEmail(Client $client)
    {
        $message = (new \Swift_Message('Welcome to Bilemo Galaxy!'))
            ->setFrom(['contact@bilemo.com' => 'Bilemo'])
            ->setTo($client->getApplication()->getEmail())
            ->setContentType('text/html')
            ->setBody(
                $this->templating->render(
                    'Email/client_creation.html.twig',
                    array(
                        'client_id' => $client->getPublicId(),
                        'client_secret' => $client->getSecret(),
                        'username' => $client->getApplication()->getUsername(),
                        'password' => $client->getApplication()->getPlainPassword()
                    )
                )
            );

            $this->mailer->send($message);
    }
}
