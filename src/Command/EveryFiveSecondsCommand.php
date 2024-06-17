<?php
namespace App\Command;

use App\Repository\FacturesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EveryFiveSecondsCommand extends Command
{
    // Le nom de la commande (la partie après "bin/console")
    protected static $defaultName = 'app:daily-check-invoices';
    private $factureRepository;

    public function __construct(FacturesRepository $factureRepository)
    {
        parent::__construct();
        $this->factureRepository = $factureRepository;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Exécute une tâche toutes les cinq secondes.')
            ->setHelp('Cette commande vous permet de tester la planification...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $factures = $this->factureRepository->findAll();

        $output->writeln("Ca fonctionne");

        foreach ($factures as $facture) {
            $createdAtFacture = $facture->getCreatedAt()->format('Y-m-d H:i:s'); 
            $interValRelance = $facture->getIdEntreprise()->getIntervalRelanceFactures();

            $output->writeln("CreatedAtFacture $createdAtFacture");
            $output->writeln("Interval de relance $interValRelance.");

            // Créer un objet DateTime pour la date actuelle
            $now = new \DateTime();
            // Créer un objet DateTime à partir de createdAtFacture
            $createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $createdAtFacture);
            // Calculer la différence
            $diff = $now->diff($createdAt);
            // Convertir la différence en jours
            $days = (int)$diff->format('%r%a');

            if ($days > $interValRelance) {
                $output->writeln("La facture a été créée il y a plus de 7 jours.");
                // Faire une relance
                // Ajouter une interraction
            } 
            // $idFacture = $facture->getId();
            // $emailClient = $facture->getClient()->getEmail();
            // $statut = $facture->getStatut();
            // $haveToRelance = 
            // $output->writeln("CreatedAtFacture $createdAtFacture");
            // $output->writeln("Interval de relance $interValRelance.");
            // Effectuez ici votre vérification sur chaque facture
            // Par exemple, vérifier si une facture est impayée et loguer ou prendre des actions appropriées
        }

        return Command::SUCCESS;
    }
}