<?php

	namespace AppBundle\Command;

	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Input\ArrayInput;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Yaml\Yaml;
	use AppBundle\Entity\Os;
	use AppBundle\Entity\Manufacturer;
	use AppBundle\Entity\Application;
	use AppBundle\Entity\Mobile;
	use AppBundle\Entity\Feature;
	use AppBundle\Entity\Picture;

	class LoadFixturesCommand extends ContainerAwareCommand
	{
		private $em;
		private $picturesFixtures = __DIR__.'/../DataFixtures/Pictures/';
		private $picturesDestination = __DIR__.'/../../../web/uploads';

		protected function configure()
		{
			$this
				// Command's name (after php bin/console)
				->setName('bilemo:fixtures:load')
				// Short description shown while running "php bin/console list"
				->setDescription('Loads Bilemo fixtures to your database')
				// Full command description shown when running the command with "--help" option
		        ->setHelp('This command loads fixtures')
    		;
		}

		protected function execute(InputInterface $input, OutputInterface $output)
		{
			$this->em = $this->getContainer()->get('doctrine')->getManager();

			$application = $this->getApplication();
        	$application->setAutoExit(false);

        	// Drop database
        	$output->writeln([
	            '===================================================',
	            'Dropping database',
	            '===================================================',
	            '',
	        ]);

        	$options = array('command' => 'doctrine:database:drop', '--force' => true);
        	$application->run(new ArrayInput($options));

        	// Prepare database environment
        	$output->writeln([
	            '',
	            '===================================================',
	            'Create database',
	            '===================================================',
	            '',
	        ]);

       		$options = array('command' => 'doctrine:database:create', '--if-not-exists' => true);	        
        	$application->run(new ArrayInput($options));

        	// Create database schema
        	$output->writeln([
        		'',
	            '===================================================',
	            'Create database schema',
	            '===================================================',
	            '',
	        ]);

       		$options = array('command' => 'doctrine:schema:create');	        	
	        $application->run(new ArrayInput($options));

	        //Load fixtures
	        $output->writeln([
	            '',
	            '===================================================',
	            'Load fixtures',
	            '===================================================',
	            '',
	        ]);

	        //Load & create fixtures from YAML file
	        $this->loadFixtures($output);

  			//Feedback end
	        $output->writeln([
	            '',
	            'Everything was successfully loaded.',
	            '',
	            '===================================================',
	            ''
	        ]);
		}

		/**
		 * Loads fixtures
		 * @param  OutputInterface $output
		 */
		public function loadFixtures(OutputInterface $output)
		{
			$kernel = $this->getApplication()->getKernel();

			//Parse fixtures YAML files
			$applications = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/applications.yml'), true));
			$oss = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/os.yml'), true));
			$manufacturers = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/manufacturers.yml'), true));
			$mobiles = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/mobiles.yml'), true));

			//Browse applications fixtures
			foreach ($applications as $data) {
				$application = new Application();
				$application
					->setUserName($data['username'])
					->setEmail($data['email'])
					->setUri($data['uri'])
					->setRoles($data['roles']);

				if (isset($data['password'])) {
					$application->setPlainPassword($data['password']);
				}
				
				$this->em->persist($application);
				
				$clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
		        $client = $clientManager->createClient();
		        $client->setRedirectUris(array($application->getUri()));
		        $client->setAllowedGrantTypes(array('password', 'refresh_token'));
		        $client->setApplication($application);
		        $clientManager->updateClient($client);				
			}

			//Browse OS fixtures
			foreach ($oss as $data) {
				$os = new Os();
				$os->setName($data['name']);

				$this->em->persist($os);				
			}

			//Browse manufacturers fixtures
			foreach ($manufacturers as $data) {
				$manufacturer = new Manufacturer();
				$manufacturer->setName($data['name']);

				$this->em->persist($manufacturer);				
			}

			$this->em->flush();

			//Browse mobiles fixtures
			foreach ($mobiles as $data) {
				foreach ($data['models'] as $model) {
					$mobile = new Mobile();
					$mobile
						->setName($data['name'].' '.$model['colorName'])
						->setOs($this->em->getRepository('AppBundle:Os')->findOneByName($data['os']))
						->setManufacturer($this->em->getRepository('AppBundle:Manufacturer')->findOneByName($data['manufacturer']))
						->setPrice($model['price'])
						->setColorName($model['colorName'])
						->setColorCode($model['colorCode'])
						->setMemory($model['memory'])
						->setStock($model['stock']);

					foreach ($data['features'] as $featureData) {
						$feature = new Feature();
						$feature
							->setName($featureData['name'])
							->setValue($featureData['value']);

						$mobile->addFeature($feature);
					}

					//Upload mobile's pictures
					$folder = $this->picturesFixtures.'/'.$model['picturesFolder'];
					$handle = opendir($folder);

					while(($file = readdir($handle)) !== false) {
		                if ($file != '.' && $file != '..'){
		                	$picture = $this->uploadPicture($file, $folder);

		                    $mobile->addPicture($picture);
		                }
		            }

		            $this->em->persist($mobile);
				}				
			}

			$this->em->flush();
		}

		/**
		 * Uploads picture in folder
		 * @param File $file
		 * @param string $folder
		 */
		private function uploadPicture($file, $folder){
			//Get file extension
	        $ext = substr(strrchr($file,'.'), 1);
	        //Create random file name
	        $newName = sha1(uniqid(mt_rand(), true)).'.'.$ext;
	        
	        //Create Picture entity
	        $picture = new Picture();
	        //Set path with new filename
	        $picture->setPath($newName);
	        //Copy file in folder
	        copy($folder.'/'.$file, $this->picturesDestination.'/'.$picture->getPath());
	        
	        return $picture;
	    }
	}