<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516121416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, id_entreprise_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6491A867E8F ON "user" (id_entreprise_id)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6491A867E8F FOREIGN KEY (id_entreprise_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles DROP CONSTRAINT fk_b63e2ec73a4a985e');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT fk_1483a5e91a867e8f');
        $this->addSql('ALTER TABLE rapport_financiers DROP CONSTRAINT fk_bef7fb107f449e57');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE rapport_financiers');
        $this->addSql('ALTER TABLE entreprises DROP id_devis');
        $this->addSql('ALTER TABLE entreprises DROP id_rapports_financiers');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE roles (userd_id_id INT NOT NULL, description VARCHAR(100) NOT NULL, PRIMARY KEY(userd_id_id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, id_entreprise_id INT NOT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_1483a5e91a867e8f ON users (id_entreprise_id)');
        $this->addSql('CREATE TABLE rapport_financiers (id_id INT NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT fk_b63e2ec73a4a985e FOREIGN KEY (userd_id_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT fk_1483a5e91a867e8f FOREIGN KEY (id_entreprise_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rapport_financiers ADD CONSTRAINT fk_bef7fb107f449e57 FOREIGN KEY (id_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6491A867E8F');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE entreprises ADD id_devis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprises ADD id_rapports_financiers INT DEFAULT NULL');
    }
}
