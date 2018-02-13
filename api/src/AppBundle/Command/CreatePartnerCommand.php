<?php

	namespace AppBundle\Command;

	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Doctrine\ORM\EntityManagerInterface;
	use FOS\OAuthServerBundle\Entity\ClientManager;
	use AppBundle\Entity\Application;
	use AppBundle\Entity\Client;

	class CreatePartnerCommand extends Command
	{
		private $em;
		private $clientManager;

		public function __construct(EntityManagerInterface $em, ClientManager $clientManager)
		{
			parent::__construct();

			$this->em = $em;
			$this->clientManager = $clientManager;
		}

		protected function configure()
		{
			$this
				->setName('bilemo:partner:create')
				->setDescription('Create a new Bilemo partner application')
				->addArgument('name', InputArgument::REQUIRED, 'The name of your application')
				->addArgument('password', InputArgument::REQUIRED, 'Your password')
				->addArgument('email', InputArgument::REQUIRED, 'The name of your application')
				->addArgument('redirectURI', InputArgument::REQUIRED, 'Your redirect URI')
		        ->setHelp('This command allows you to create a new Bilemo partner application')
    		;
		}

		protected function execute(InputInterface $input, OutputInterface $output)
		{
        	// Create application
			$application = new Application();
			$application
				->setUsername($input->getArgument('name'))
				->setPlainPassword($input->getArgument('password'))
				->setEmail($input->getArgument('email'))
				->setUri($input->getArgument('redirectURI'))
				->setRoles(['ROLE_APPLICATION']);
			//Save application
			$this->em->persist($application);
			$this->em->flush();

			//Create FOSOAuth client
			$client = $this->clientManager->createClient();
		   	$client->setRedirectUris(array($application->getUri()));
	        $client->setAllowedGrantTypes(array('password', 'refresh_token'));
	        $client->setApplication($application);
	        //Save FOSOAuth client
	        $this->clientManager->updateClient($client);

	        $output->writeln("");
	        $output->writeln("Client credentials");
	        $output->writeln("==================");
	        $output->writeln("");

	        $output->writeln(array(
	        	sprintf("username: <comment>%s</comment>", $application->getUsername()),
	        	sprintf("password: <comment>%s</comment>", $application->getPlainPassword()),
	        	sprintf("client_id: <comment>%s</comment>", $client->getPublicId()),
	        	sprintf("client_secret: <comment>%s</comment>", $client->getSecret()),
	        	""
	        ));

	        return 0;
		}
	}