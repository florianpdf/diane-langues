<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\User;
use App\Entity\WebsiteConfig;
use App\Repository\UserRepository;
use App\Repository\WebsiteConfigRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function Symfony\Component\String\u;

/**
 * A console command that creates users and stores them in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:add-user
 *
 * To output detailed information, increase the command verbosity:
 *
 *     $ php bin/console app:add-user -vv
 *
 * See https://symfony.com/doc/current/console.html
 *
 * We use the default services.yaml configuration, so command classes are registered as services.
 * See https://symfony.com/doc/current/console/commands_as_services.html
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class AddUserCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:initialize-config';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $users;
    private $websiteConfig;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Validator $validator, UserRepository $users, WebsiteConfigRepository $websiteConfig)
    {
        
        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->users = $users;
        $this->websiteConfig = $websiteConfig;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->checkIfUserExist();

        $this
            ->setDescription('Creates users and stores them in the database')
            ->setHelp($this->getCommandHelp())
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user for admin connection')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user for admin connection')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the new user')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'The firstname of the new user')
            ->addArgument('website-name', InputArgument::OPTIONAL, 'Name of the Website')
        ;
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('username') 
            && null !== $input->getArgument('password') 
            && null !== $input->getArgument('email') 
            && null !== $input->getArgument('name')
            && null !== $input->getArgument('firstname')
            && null !== $input->getArgument('website-name')
        ) {
            return;
        }

        // Ask for the username if it's not defined
        $username = $input->getArgument('username');
        if (null !== $username) {
            $this->io->text(' > <info>Username for admin</info>: '.$username);
        } else {
            $username = $this->io->ask('Username for admin', null, [$this->validator, 'validateUsername']);
            $input->setArgument('username', $username);
        }

        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null !== $password) {
            $this->io->text(' > <info>Password for admin</info>: '.u('*')->repeat(u($password)->length()));
        } else {
            $password = $this->io->askHidden('Password for admin (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }

        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: '.$email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }

        // Ask for the name if it's not defined
        $name = $input->getArgument('name');
        if (null !== $name) {
            $this->io->text(' > <info>Your last name</info>: '.$name);
        } else {
            $name = $this->io->ask('Your last name', null, [$this->validator, 'validateName']);
            $input->setArgument('name', $name);
        }

        // Ask for the firstName if it's not defined
        $firstName = $input->getArgument('firstname');
        if (null !== $firstName) {
            $this->io->text(' > <info>Your first Name</info>: '.$firstName);
        } else {
            $firstName = $this->io->ask('Your first Name', null, [$this->validator, 'validateFirstName']);
            $input->setArgument('firstname', $firstName);
        }

        // Ask for the websiteName if it's not defined
        $websiteName = $input->getArgument('website-name');
        if (null !== $websiteName) {
            $this->io->text(' > <info>The Website Name</info>: '.$websiteName);
        } else {
            $websiteName = $this->io->ask('The Website Name', null, [$this->validator, 'validateWebsiteName']);
            $input->setArgument('website-name', $websiteName);
        }
        
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');
        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $firstName = $input->getArgument('firstname');
        $websiteName = $input->getArgument('website-name');

        // make sure to validate the user data is correct
        $this->validateUserData($username, $plainPassword, $email, $name, $firstName, $websiteName);

        // create the user and encode its password
        $user = new User();
        $user->setUsername($username);
        $user->setRoles(['ROLE_ADMIN']);

        // See https://symfony.com/doc/current/security.html#c-encoding-passwords
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);

        $websiteConfig = new WebsiteConfig();
        $websiteConfig->setName($name);
        $websiteConfig->setFirstname($firstName);
        $websiteConfig->setEmail($email);
        $websiteConfig->setWebSiteTitle($websiteName);

        $this->entityManager->persist($websiteConfig);

        $this->entityManager->flush();

        $this->io->success(sprintf('%s and website configuration was successfully created', $user->getUsername()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return 0;
    }

    private function checkIfUserExist(){
        // first check if a user with the same username already exists.
        $existingUser = $this->users->findOneBy([]);

        if (null !== $existingUser) {
            throw new RuntimeException(
                sprintf(
                    'There is already a user & configuration registered for the website with the "%s" username, please go to /admin, connect and configure', 
                    $existingUser->getUsername()
                ));
        }
    }

    private function validateUserData($username, $plainPassword, $email, $name, $firstName, $websiteName): void
    {
        // validate other field if is not this input means interactive.
        $this->validator->validatePassword($plainPassword);
        $this->validator->validateUsername($username);
        $this->validator->validateEmail($email);
        $this->validator->validateName($name);
        $this->validator->validateFirstName($firstName);
        $this->validator->validateWebsiteName($websiteName);
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command creates new users and saves them in the database:

  <info>php %command.full_name%</info> <comment>username password email</comment>

By default the command creates regular users. To create administrator users,
add the <comment>--admin</comment> option:

  <info>php %command.full_name%</info> username password email <comment>--admin</comment>

If you omit any of the three required arguments, the command will ask you to
provide the missing values:

  # command will ask you for the email
  <info>php %command.full_name%</info> <comment>username password</comment>

  # command will ask you for the email and password
  <info>php %command.full_name%</info> <comment>username</comment>

  # command will ask you for all arguments
  <info>php %command.full_name%</info>

HELP;
    }
}
