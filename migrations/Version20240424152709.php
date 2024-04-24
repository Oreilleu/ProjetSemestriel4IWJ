<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424152709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP CONSTRAINT fk_7332e1691105164f');
        $this->addSql('DROP INDEX idx_7332e1691105164f');
        $this->addSql('ALTER TABLE services DROP id_devis_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE services ADD id_devis_id INT NOT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT fk_7332e1691105164f FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7332e1691105164f ON services (id_devis_id)');
    }
}
