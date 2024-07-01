<?php

namespace App\Command;

use App\Entity\Entreprises;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:load-fixtures-admin',
    description: 'Add an admin user and entreprise fixtures to the database',
)]
class LoadAdminFixturesCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Load initial fixtures into the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $faker = Factory::create('fr_FR');


        $io->title('Loading fixtures');

        $entreprise = new Entreprises();
        $entreprise->setNom($faker->company);
        $entreprise->setAdresse($faker->address);
        do {
            $phoneNumber = preg_replace('/\D/', '', $faker->phoneNumber);
        } while (strlen($phoneNumber) < 10);

        $phoneNumber = '0' . substr($phoneNumber, 0, 9);
        $entreprise->setTel($phoneNumber);
        $entreprise->setEmail($faker->companyEmail);
        $entreprise->setNumeroSiret($faker->numerify('##############'));
        $entreprise->setIntervalRelanceDevis($faker->numberBetween(1, 30));
        $entreprise->setIntervalRelanceFactures($faker->numberBetween(1, 30));
        $entreprise->setCreatedAt(new \DateTimeImmutable());
        $entreprise->setVille($faker->city);
        $entreprise->setPays($faker->country);
        $entreprise->setCp($faker->postcode);

        $this->entityManager->persist($entreprise);

        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'test')
        );
        $user->setIdEntreprise($entreprise);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        $io->success('Fixtures have been loaded successfully.');

        return Command::SUCCESS;
    }
}
