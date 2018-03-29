<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\OAuthServerBundle\Entity\ClientManager;
use AppBundle\Entity\Os;
use AppBundle\Entity\Manufacturer;
use AppBundle\Entity\Application;
use AppBundle\Entity\User;
use AppBundle\Entity\Mobile;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Picture;

class LoadFixturesCommand extends Command
{
    private $em;
    private $clientManager;
    private $picturesFixtures = __DIR__.'/../DataFixtures/Pictures/';
    private $picturesDestination = __DIR__.'/../../../web/uploads';
    private $application;
    private $tokenStorage;
    private $session;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, SessionInterface $session, ClientManager $clientManager)
    {
        parent::__construct();

        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->clientManager = $clientManager;
    }

    protected function configure()
    {
        $this
            // Command's name (after php bin/console)
            ->setName('bilemo:fixtures:load')
            // Short description shown while running "php bin/console list"
            ->setDescription('Loads Bilemo fixtures to your database')
            // Option environment - default: dev
            ->addOption('environment', null, InputOption::VALUE_OPTIONAL, 'Application environment', 'dev')
            // Full command description shown when running the command with "--help" option
            ->setHelp('This command loads fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->application = $this->getApplication();
        $this->application->setAutoExit(false);

        if ($input->getOption('environment') === 'test') {
            // Drop database
            $output->writeln('Dropping database');
            $this->dropDatabase();
        }

        // Prepare database environment
        $output->writeln('Create database');
        $this->createDatabase();

        // Create database schema
        $output->writeln('Create database schema');
        $this->createDatabaseSchema();

        //Load fixtures
        $output->writeln('Load fixtures');
        $this->loadFixtures();

        //Feedback end
        $output->writeln('Everything was successfully loaded!');
    }

    /**
     * Executes command to drop database
     */
    private function dropDatabase()
    {
        $options = array('command' => 'doctrine:database:drop', '--force' => true);
        $this->application->run(new ArrayInput($options));
    }

    /**
     * Executes command to create database
     */
    private function createDatabase()
    {
        $options = array('command' => 'doctrine:database:create', '--if-not-exists' => true);
        $this->application->run(new ArrayInput($options));
    }

    /**
     * Executes command to create database schema
     */
    private function createDatabaseSchema()
    {
        $options = array('command' => 'doctrine:schema:create');
        $this->application->run(new ArrayInput($options));
    }

    /**
     * Loads fixtures
     */
    public function loadFixtures()
    {
        $kernel = $this->getApplication()->getKernel();

        //Parse fixtures YAML files
        $applications = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/applications.yml'), true));
        $oss = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/os.yml'), true));
        $manufacturers = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/manufacturers.yml'), true));
        $mobiles = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/mobiles.yml'), true));

        //Browse applications fixtures
        $this->loadApplications($applications);

        //Browse OS fixtures
        $this->loadOss($oss);

        //Browse manufacturers fixtures
        $this->loadManufacturers($manufacturers);

        //Need to flush to be able to link oss and manufacturers to mobiles
        $this->em->flush();

        //Browse mobiles fixtures
        $this->loadMobiles($mobiles);

        //Flush
        $this->em->flush();
    }

    /**
     * Loads applications
     * @param array $applications
     */
    private function loadApplications($applications)
    {
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

            //Load users and their application
            $this->loadUsers($application, $data['users']);

            $this->em->persist($application);

            $client = $this->clientManager->createClient();
            $client->setRedirectUris(array($application->getUri()));
            $client->setAllowedGrantTypes(array('password', 'refresh_token'));
            $client->setApplication($application);
            $this->clientManager->updateClient($client);
        }
    }

    /**
     * Loads OS
     * @param array $oss
     */
    private function loadOss($oss)
    {
        foreach ($oss as $data) {
            $os = new Os();
            $os->setName($data['name']);

            $this->em->persist($os);
        }
    }

    /**
     * Loads manufacturers
     * @param array $manufacturers
     */
    private function loadManufacturers($manufacturers)
    {
        foreach ($manufacturers as $data) {
            $manufacturer = new Manufacturer();
            $manufacturer->setName($data['name']);

            $this->em->persist($manufacturer);
        }
    }

    /**
     * Loads mobiles
     * @param array $mobiles
     */
    private function loadMobiles($mobiles)
    {
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

                //Add features to mobile
                $this->addFeatures($mobile, $data['features']);

                //Add pictures to mobile
                $this->addPictures($mobile, $model);

                $this->em->persist($mobile);
            }
        }
    }

    /**
     * Adds features to mobile
     * @param Mobile $mobile
     * @param array $features
     */
    private function addFeatures($mobile, $features)
    {
        foreach ($features as $featureData) {
            $feature = new Feature();
            $feature
                ->setName($featureData['name'])
                ->setValue($featureData['value']);

            $mobile->addFeature($feature);
        }
    }

    /**
     * Adds pictures to mobile
     * @param Mobile $mobile
     * @param array $pictures
     */
    private function addPictures($mobile, $model)
    {
        $folder = $this->picturesFixtures.'/'.$model['picturesFolder'];
        $handle = opendir($folder);

        while(($file = readdir($handle)) !== false) {
            if ($file != '.' && $file != '..'){
                //Upload mobile's pictures
                $picture = $this->uploadPicture($file, $folder);

                $mobile->addPicture($picture);
            }
        }
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
        //Create folder if not exist
        if(!is_dir($this->picturesDestination)){
            mkdir($this->picturesDestination, 0777);
        }
        //Copy file in folder
        copy($folder.'/'.$file, $this->picturesDestination.'/'.$picture->getPath());

        return $picture;
    }

    /**
     * Loads users to application
     * @param Application $application
     * @param array $users
     */
    private function loadUsers($application, $users)
    {
        foreach ($users as $userData) {
            $user = new User();
            $user
                ->setUsername($userData['username'])
                ->setFirstname($userData['firstname'])
                ->setLastname($userData['lastname'])
                ->setEmail($userData['email'])
                ->setPhone($userData['phone']);

            $this->setApplicationToken($application);

            $this->em->persist($user);
        }

    }

    /**
     * Sets application token
     * @param  Application $application
     */
    private function setApplicationToken($application)
    {
        $token = new UsernamePasswordToken($application, null, 'api', $application->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_api', serialize($token));
    }
}
