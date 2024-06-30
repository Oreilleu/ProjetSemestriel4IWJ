<?php
namespace App\Command;

use App\Entity\Devis;
use App\Entity\Factures;
use App\Repository\DevisRepository;
use App\Repository\FacturesRepository;
use App\Service\EmailService;
use App\Service\InterractionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class DailyCheckDevis extends Command
{
    protected static $defaultName = 'app:daily-check-devis';
    private $devisRepository;
    private $emailService;
    private $interractionService;
    private $entityManager;

    private $twig;

    public function __construct(DevisRepository $devisRepository, EmailService $emailService, InterractionService $interractionService, EntityManagerInterface $entityManager, Environment $twig)
    {
        parent::__construct();
        $this->devisRepository = $devisRepository;
        $this->emailService = $emailService;
        $this->interractionService = $interractionService;
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Check devis.')
            ->setHelp('Check devis.');
    }

    private function sendRelanceDevisEmail(Devis $devis): void
    {
        $subject = 'Relance devis';
        $template = 'email/relance_devis.html.twig';
        $recipient = $devis->getClient()->getEmail();

        $content = $this->twig->render($template, [
            'devis' => $devis,
        ]);

        $this->emailService->sendEmail($recipient, $subject, $content);

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $devis = $this->devisRepository->findAll();

        foreach ($devis as $devi) {
            $interValRelance = $devi->getIdEntreprise()->getIntervalRelanceDevis();

            $now = new \DateTimeImmutable();
            $lastRelance = $devi->getLastRelance();
            $diff = $now->diff($lastRelance ? $lastRelance : $devi->getCreatedAt());
            $days = (int)$diff->format('%a');

            if ($devi->getStatut() != 'Accepté' && $days > $interValRelance) {
                $output->writeln('Relance envoyée pour le devis ' . $devi->getId());
                $this->sendRelanceDevisEmail($devi);
                $this->interractionService->createDevisInterraction($devi, 'Relance envoyée pour le devis' . $devi->getId());
                $devi->setLastRelance(new \DateTimeImmutable());

                $this->entityManager->persist($devi);
                $this->entityManager->flush();
            } 
        }

        return Command::SUCCESS;
    }
}