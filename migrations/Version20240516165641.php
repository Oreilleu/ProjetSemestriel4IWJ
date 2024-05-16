<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516165641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits DROP CONSTRAINT fk_be2ddf8c8dcc45e8');
        $this->addSql('DROP SEQUENCE cat_services_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE categories_produits_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories_produits (id INT NOT NULL, nom VARCHAR(100) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE cat_services');
        $this->addSql('ALTER TABLE clients DROP id_devis');
        $this->addSql('ALTER TABLE lignes_devis ADD id_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE lignes_devis ADD CONSTRAINT FK_2FD8E619AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FD8E619AABEFE2C ON lignes_devis (id_produit_id)');
        $this->addSql('DROP INDEX idx_be2ddf8c8dcc45e8');
        $this->addSql('ALTER TABLE produits RENAME COLUMN id_cat_services_id TO id_categorie_produits_id');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC35813C6 FOREIGN KEY (id_categorie_produits_id) REFERENCES categories_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CC35813C6 ON produits (id_categorie_produits_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE produits DROP CONSTRAINT FK_BE2DDF8CC35813C6');
        $this->addSql('DROP SEQUENCE categories_produits_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE cat_services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cat_services (id INT NOT NULL, nom VARCHAR(100) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE categories_produits');
        $this->addSql('DROP INDEX IDX_BE2DDF8CC35813C6');
        $this->addSql('ALTER TABLE produits RENAME COLUMN id_categorie_produits_id TO id_cat_services_id');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT fk_be2ddf8c8dcc45e8 FOREIGN KEY (id_cat_services_id) REFERENCES cat_services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_be2ddf8c8dcc45e8 ON produits (id_cat_services_id)');
        $this->addSql('ALTER TABLE clients ADD id_devis INT NOT NULL');
        $this->addSql('ALTER TABLE lignes_devis DROP CONSTRAINT FK_2FD8E619AABEFE2C');
        $this->addSql('DROP INDEX IDX_2FD8E619AABEFE2C');
        $this->addSql('ALTER TABLE lignes_devis DROP id_produit_id');
    }
}
