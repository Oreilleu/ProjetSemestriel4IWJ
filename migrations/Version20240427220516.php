<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427220516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis ADD id_lots_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD total_ht DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE devis DROP created_at');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B1A9DB84F FOREIGN KEY (id_lots_id) REFERENCES lots (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8B27C52B1A9DB84F ON devis (id_lots_id)');
        $this->addSql('ALTER TABLE factures ADD total_ht DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE factures DROP total_ht');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B1A9DB84F');
        $this->addSql('DROP INDEX IDX_8B27C52B1A9DB84F');
        $this->addSql('ALTER TABLE devis ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE devis DROP id_lots_id');
        $this->addSql('ALTER TABLE devis DROP total_ht');
        $this->addSql('COMMENT ON COLUMN devis.created_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
