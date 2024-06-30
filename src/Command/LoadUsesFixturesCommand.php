<?php

namespace App\Command;

use App\Entity\CategoriesProduits;
use App\Entity\Clients;
use App\Entity\Entreprises;
use App\Entity\Lots;
use App\Entity\Produits;
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
    name: 'app:load-fixtures-users',
    description: 'Add users fixtures to the database',
)]
class LoadUsesFixturesCommand extends Command
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
            ->setDescription('Load initial users fixtures into the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $faker = Factory::create('fr_FR');

        $io->title('Loading users fixtures');

        for ($i = 1; $i <= 5; $i++) {
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
            $entreprise->setCp($faker->postcode);
            $entreprise->setVille($faker->city);
            $entreprise->setPays($faker->country);
            $entreprise->setIntervalRelanceDevis(7);
            $entreprise->setIntervalRelanceFactures(7);
            $entreprise->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($entreprise);
            $this->entityManager->flush();

            $user = new User();
            $user->setEmail('admin' . $i . '@entreprise.com');
            $user->setRoles(['ROLE_ADMIN_ENTREPRISE']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'test')
            );
            $user->setIdEntreprise($entreprise);

            $this->entityManager->persist($user);

            for ($j = 1; $j <= 10; $j++) {
                $client = new Clients();
                $client->setNom($faker->lastName);
                $client->setPrenom($faker->firstName);
                $client->setAdresse($faker->address);
                do {
                    $clientPhoneNumber = preg_replace('/\D/', '', $faker->phoneNumber);
                } while (strlen($clientPhoneNumber) < 10);

                $clientPhoneNumber = '0' . substr($clientPhoneNumber, 0, 9);
                $client->setTel($clientPhoneNumber);
                $client->setEmail($faker->email);
                $client->setNumeroSiret($faker->numerify('##############'));
                $client->setCp($faker->postcode);
                $client->setVille($faker->city);
                $client->setPays($faker->country);
                $client->setCreatedAt(new \DateTimeImmutable());
                $client->setIdEntreprise($entreprise);

                $this->entityManager->persist($client);


                for ($k = 1; $k <= 2; $k++) {
                    $lot = new Lots();
                    $lot->setIdClient($client);
                    $lot->setIdEntreprise($entreprise);
                    $lot->setSuperficie($faker->randomFloat(2, 50, 500));
                    $lot->setType($faker->randomElement(['Appartement', 'Maison', 'Terrain','Local commercial','Bureau']));
                    $lot->setAdresse($faker->address);
                    $lot->setCp($faker->postcode);
                    $lot->setVille($faker->city);
                    $lot->setPays($faker->country);

                    $this->entityManager->persist($lot);
                }
            }

            for ($l = 1; $l <= 5; $l++) {
                $categorie = new CategoriesProduits();
                $categorie->setNom($faker->randomElement(['Styles de Décoration', 'Types de Pièces', 'Éléments de Décoration']));
                $categorie->setIdEntreprise($entreprise);

                $this->entityManager->persist($categorie);

                $produit = new Produits();
                $produit->setNom($faker->randomElement(['Canapé', 'Lit', 'Table basse', 'Suspension', 'Vase', 'Tapisserie murale']));
                $produit->setPrix($faker->randomFloat(2, 1, 100));
                $produit->setIdCategorieProduits($categorie);
                $produit->setIdEntreprise($entreprise);

                $this->entityManager->persist($produit);
            }
        }

        $this->entityManager->flush();

        $io->success('Users fixtures have been loaded successfully.');

        return Command::SUCCESS;
    }
}
