<?php
namespace App\Command;

use App\Entity\Factures;
use App\Repository\FacturesRepository;
use App\Service\EmailService;
use App\Service\InterractionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class DailyCheckInvoice extends Command
{
    protected static $defaultName = 'app:daily-check-invoices';
    private $factureRepository;
    private $emailService;
    private $interractionService;
    private $entityManager;

    private $twig;

    public function __construct(FacturesRepository $factureRepository, EmailService $emailService, InterractionService $interractionService, EntityManagerInterface $entityManager,Environment $twig)
    {
        parent::__construct();
        $this->factureRepository = $factureRepository;
        $this->emailService = $emailService;
        $this->interractionService = $interractionService;
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Check Invoice.')
            ->setHelp('Check Invoice.');
    }

    private function sendRelanceFactureEmail(Factures $facture): void
    {
        $subject = 'Relance facture';
        $template = 'email/relance_facture.html.twig';
        $recipient = $facture->getClient()->getEmail();

        $content = $this->twig->render($template, [
            'facture' => $facture,
        ]);

        $this->emailService->sendEmail($recipient, $subject, $content);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $factures = $this->factureRepository->findAll();

        foreach ($factures as $facture) {
            $interValRelance = $facture->getIdEntreprise()->getIntervalRelanceFactures();

            $now = new \DateTimeImmutable();
            $lastRelance = $facture->getLastRelance();
            $diff = $now->diff($lastRelance ? $lastRelance : $facture->getCreatedAt());
            $days = (int)$diff->format('%a');

            if ($facture->getStatut() != 'Payée' && $days > $interValRelance) {
                $output->writeln("Relance envoyée pour la facture" . $facture->getId());
                $this->sendRelanceFactureEmail($facture);
                $this->interractionService->createFactureInterraction($facture, 'Relance envoyée pour la facture' . $facture->getId());
                $facture->setLastRelance(new \DateTimeImmutable());

                $this->entityManager->persist($facture);
                $this->entityManager->flush();
            } 
        }

        return Command::SUCCESS;
    }
}