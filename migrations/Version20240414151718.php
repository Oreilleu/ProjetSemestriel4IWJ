<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414151718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cat_services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE details_services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE devis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE factures_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE interractions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mode_paiements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE paiements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relances_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cat_services (id INT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE details_services (id INT NOT NULL, id_service_id INT NOT NULL, id_devis_id INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_196492E248D62931 ON details_services (id_service_id)');
        $this->addSql('CREATE INDEX IDX_196492E21105164F ON details_services (id_devis_id)');
        $this->addSql('CREATE TABLE devis (id INT NOT NULL, id_client_id INT NOT NULL, id_entreprise_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description VARCHAR(255) NOT NULL, statut INT NOT NULL, taxe DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8B27C52B99DED506 ON devis (id_client_id)');
        $this->addSql('CREATE INDEX IDX_8B27C52B1A867E8F ON devis (id_entreprise_id)');
        $this->addSql('COMMENT ON COLUMN devis.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE factures (id INT NOT NULL, id_devis_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, statut INT NOT NULL, taxe DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_647590B1105164F ON factures (id_devis_id)');
        $this->addSql('COMMENT ON COLUMN factures.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE interractions (id INT NOT NULL, id_devis_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_964094481105164F ON interractions (id_devis_id)');
        $this->addSql('CREATE TABLE mode_paiements (id INT NOT NULL, id_facture_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_866E03AADAA76EDF ON mode_paiements (id_facture_id)');
        $this->addSql('CREATE TABLE paiements (id INT NOT NULL, id_facture_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E1B02E12DAA76EDF ON paiements (id_facture_id)');
        $this->addSql('CREATE TABLE rapport_financiers (id_id INT NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('CREATE TABLE relances (id INT NOT NULL, id_devis_id INT NOT NULL, date_rappel TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, statut VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C953F1371105164F ON relances (id_devis_id)');
        $this->addSql('CREATE TABLE roles (userd_id_id INT NOT NULL, description VARCHAR(100) NOT NULL, PRIMARY KEY(userd_id_id))');
        $this->addSql('CREATE TABLE services (id INT NOT NULL, id_cat_services_id INT NOT NULL, id_devis_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prix INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7332E1698DCC45E8 ON services (id_cat_services_id)');
        $this->addSql('CREATE INDEX IDX_7332E1691105164F ON services (id_devis_id)');
        $this->addSql('ALTER TABLE details_services ADD CONSTRAINT FK_196492E248D62931 FOREIGN KEY (id_service_id) REFERENCES services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE details_services ADD CONSTRAINT FK_196492E21105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B99DED506 FOREIGN KEY (id_client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B1A867E8F FOREIGN KEY (id_entreprise_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B1105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interractions ADD CONSTRAINT FK_964094481105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mode_paiements ADD CONSTRAINT FK_866E03AADAA76EDF FOREIGN KEY (id_facture_id) REFERENCES factures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiements ADD CONSTRAINT FK_E1B02E12DAA76EDF FOREIGN KEY (id_facture_id) REFERENCES factures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rapport_financiers ADD CONSTRAINT FK_BEF7FB107F449E57 FOREIGN KEY (id_id) REFERENCES entreprises (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relances ADD CONSTRAINT FK_C953F1371105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC73A4A985E FOREIGN KEY (userd_id_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1698DCC45E8 FOREIGN KEY (id_cat_services_id) REFERENCES cat_services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1691105164F FOREIGN KEY (id_devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cat_services_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE details_services_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE devis_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE factures_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE interractions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mode_paiements_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE paiements_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relances_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE services_id_seq CASCADE');
        $this->addSql('ALTER TABLE details_services DROP CONSTRAINT FK_196492E248D62931');
        $this->addSql('ALTER TABLE details_services DROP CONSTRAINT FK_196492E21105164F');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B99DED506');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B1A867E8F');
        $this->addSql('ALTER TABLE factures DROP CONSTRAINT FK_647590B1105164F');
        $this->addSql('ALTER TABLE interractions DROP CONSTRAINT FK_964094481105164F');
        $this->addSql('ALTER TABLE mode_paiements DROP CONSTRAINT FK_866E03AADAA76EDF');
        $this->addSql('ALTER TABLE paiements DROP CONSTRAINT FK_E1B02E12DAA76EDF');
        $this->addSql('ALTER TABLE rapport_financiers DROP CONSTRAINT FK_BEF7FB107F449E57');
        $this->addSql('ALTER TABLE relances DROP CONSTRAINT FK_C953F1371105164F');
        $this->addSql('ALTER TABLE roles DROP CONSTRAINT FK_B63E2EC73A4A985E');
        $this->addSql('ALTER TABLE services DROP CONSTRAINT FK_7332E1698DCC45E8');
        $this->addSql('ALTER TABLE services DROP CONSTRAINT FK_7332E1691105164F');
        $this->addSql('DROP TABLE cat_services');
        $this->addSql('DROP TABLE details_services');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE factures');
        $this->addSql('DROP TABLE interractions');
        $this->addSql('DROP TABLE mode_paiements');
        $this->addSql('DROP TABLE paiements');
        $this->addSql('DROP TABLE rapport_financiers');
        $this->addSql('DROP TABLE relances');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE services');
    }
}
