<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240602104216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE categories_produits_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE clients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE devis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE entreprises_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE factures_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE interractions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lignes_devis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lots_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mode_paiements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE paiements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE produits_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rapports_financiers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relances_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories_produits (id INT NOT NULL, nom VARCHAR(100) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, numero_siret VARCHAR(255) NOT NULL, cp VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, pays VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN clients.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE devis (id INT NOT NULL, id_lots_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description VARCHAR(255) NOT NULL, statut INT NOT NULL, taxe DOUBLE PRECISION NOT NULL, total_ht DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8B27C52B1A9DB84F ON devis (id_lots_id)');
        $this->addSql('CREATE TABLE entreprises (id INT NOT NULL, nom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, tel VARCHAR(14) NOT NULL, email VARCHAR(100) NOT NULL, numero_siret VARCHAR(100) NOT NULL, rib VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN entreprises.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE factures (id INT NOT NULL, id_devis_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, statut INT NOT NULL, taxe DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, total_ht DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_647590B1105164F ON factures (id_devis_id)');
        $this->addSql('COMMENT ON COLUMN factures.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE interractions (id INT NOT NULL, id_devis_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_964094481105164F ON interractions (id_devis_id)');
        $this->addSql('CREATE TABLE lignes_devis (id INT NOT NULL, id_devis_id INT NOT NULL, id_produit_id INT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FD8E6191105164F ON lignes_devis (id_devis_id)');
        $this->addSql('CREATE INDEX IDX_2FD8E619AABEFE2C ON lignes_devis (id_produit_id)');
        $this->addSql('CREATE TABLE lots (id INT NOT NULL, id_client_id INT NOT NULL, superficie DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_916087CE99DED506 ON lots (id_client_id)');
        $this->addSql('CREATE TABLE mode_paiements (id INT NOT NULL, id_facture_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_866E03AADAA76EDF ON mode_paiements (id_facture_id)');
        $this->addSql('CREATE TABLE paiements (id INT NOT NULL, id_facture_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E1B02E12DAA76EDF ON paiements (id_facture_id)');
        $this->addSql('CREATE TABLE produits (id INT NOT NULL, id_categorie_produits_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prix DOUBLE PRECISION NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CC35813C6 ON produits (id_categorie_produits_id)');
        $this->addSql('CREATE TABLE rapports_financiers (id INT NOT NULL, id_entreprise_id INT NOT NULL, montant_total DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4E93AF061A867E8F ON rapports_financiers (id_entreprise_id)');
        $this->addSql('COMMENT ON COLUMN rapports_financiers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE relances (id INT NOT NULL, id_devis_id INT NOT NULL, date_rappel TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, statut VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C953F1371105164F ON relances (id_devis_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, id_entreprise_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6491A867E8F ON "user" (id_entreprise_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B1A9DB84F FOREIGN KEY (id_lots_id) REFERENCES lots (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B1105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interractions ADD CONSTRAINT FK_964094481105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lignes_devis ADD CONSTRAINT FK_2FD8E6191105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lignes_devis ADD CONSTRAINT FK_2FD8E619AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lots ADD CONSTRAINT FK_916087CE99DED506 FOREIGN KEY (id_client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mode_paiements ADD CONSTRAINT FK_866E03AADAA76EDF FOREIGN KEY (id_facture_id) REFERENCES factures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiements ADD CONSTRAINT FK_E1B02E12DAA76EDF FOREIGN KEY (id_facture_id) REFERENCES factures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC35813C6 FOREIGN KEY (id_categorie_produits_id) REFERENCES categories_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rapports_financiers ADD CONSTRAINT FK_4E93AF061A867E8F FOREIGN KEY (id_entreprise_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relances ADD CONSTRAINT FK_C953F1371105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6491A867E8F FOREIGN KEY (id_entreprise_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE categories_produits_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE clients_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE devis_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE entreprises_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE factures_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE interractions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lignes_devis_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lots_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mode_paiements_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE paiements_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE produits_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rapports_financiers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relances_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B1A9DB84F');
        $this->addSql('ALTER TABLE factures DROP CONSTRAINT FK_647590B1105164F');
        $this->addSql('ALTER TABLE interractions DROP CONSTRAINT FK_964094481105164F');
        $this->addSql('ALTER TABLE lignes_devis DROP CONSTRAINT FK_2FD8E6191105164F');
        $this->addSql('ALTER TABLE lignes_devis DROP CONSTRAINT FK_2FD8E619AABEFE2C');
        $this->addSql('ALTER TABLE lots DROP CONSTRAINT FK_916087CE99DED506');
        $this->addSql('ALTER TABLE mode_paiements DROP CONSTRAINT FK_866E03AADAA76EDF');
        $this->addSql('ALTER TABLE paiements DROP CONSTRAINT FK_E1B02E12DAA76EDF');
        $this->addSql('ALTER TABLE produits DROP CONSTRAINT FK_BE2DDF8CC35813C6');
        $this->addSql('ALTER TABLE rapports_financiers DROP CONSTRAINT FK_4E93AF061A867E8F');
        $this->addSql('ALTER TABLE relances DROP CONSTRAINT FK_C953F1371105164F');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6491A867E8F');
        $this->addSql('DROP TABLE categories_produits');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE entreprises');
        $this->addSql('DROP TABLE factures');
        $this->addSql('DROP TABLE interractions');
        $this->addSql('DROP TABLE lignes_devis');
        $this->addSql('DROP TABLE lots');
        $this->addSql('DROP TABLE mode_paiements');
        $this->addSql('DROP TABLE paiements');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE rapports_financiers');
        $this->addSql('DROP TABLE relances');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
