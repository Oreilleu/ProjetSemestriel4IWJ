<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424153508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lots_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lots (id INT NOT NULL, id_client_id INT NOT NULL, superficie DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_916087CE99DED506 ON lots (id_client_id)');
        $this->addSql('ALTER TABLE lots ADD CONSTRAINT FK_916087CE99DED506 FOREIGN KEY (id_client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE lots_id_seq CASCADE');
        $this->addSql('ALTER TABLE lots DROP CONSTRAINT FK_916087CE99DED506');
        $this->addSql('DROP TABLE lots');
    }
}
