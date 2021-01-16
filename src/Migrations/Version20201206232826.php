<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206232826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentaire_actualite (id INT AUTO_INCREMENT NOT NULL, actualite_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, INDEX IDX_DB050EA0A2843073 (actualite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_categs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, bandeau VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, site_uid INT DEFAULT NULL, site_lang VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appartement (id INT AUTO_INCREMENT NOT NULL, residence_id INT DEFAULT NULL, numero INT DEFAULT NULL, loyer_etudiant INT DEFAULT NULL, loyer_hotel_semaine INT DEFAULT NULL, loyer_hotel_jour INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, surface INT DEFAULT NULL, etage INT DEFAULT NULL, exposition VARCHAR(255) DEFAULT NULL, statut INT DEFAULT NULL, disponibilite DATE DEFAULT NULL, date_disponibilite DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_71A6BD8D8B225FBD (residence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appartement_locataire (appartement_id INT NOT NULL, locataire_id INT NOT NULL, INDEX IDX_50DC719E1729BBA (appartement_id), INDEX IDX_50DC719D8A38199 (locataire_id), PRIMARY KEY(appartement_id, locataire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actualite_medias (id INT AUTO_INCREMENT NOT NULL, actualite_id INT DEFAULT NULL, nom_fichier VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, ordre VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, INDEX IDX_FBCB2B48A2843073 (actualite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents_client (id INT AUTO_INCREMENT NOT NULL, client_user_id INT NOT NULL, nom_fichier VARCHAR(255) DEFAULT NULL, INDEX IDX_34C13213F55397E8 (client_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE residence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auto_promo (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, periode INT DEFAULT NULL, date_d DATETIME DEFAULT NULL, date_f DATETIME DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, cible TINYINT(1) DEFAULT NULL, statut INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, zone INT DEFAULT NULL, ordre INT DEFAULT NULL, sous_titre VARCHAR(400) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, password_verify VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_historique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, cont_from VARCHAR(255) DEFAULT NULL, sujet VARCHAR(255) DEFAULT NULL, message LONGTEXT DEFAULT NULL, societe VARCHAR(255) DEFAULT NULL, tel INT DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, cp INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, site_uid INT DEFAULT NULL, site_lang VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appartement_medias (id INT AUTO_INCREMENT NOT NULL, appartement_id INT DEFAULT NULL, nom_fichier VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_88906E54E1729BBA (appartement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categs_documents (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categs_documents_documents_client (categs_documents_id INT NOT NULL, documents_client_id INT NOT NULL, INDEX IDX_28D960A7BFECD78 (categs_documents_id), INDEX IDX_28D960A779327E7A (documents_client_id), PRIMARY KEY(categs_documents_id, documents_client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locataire (id INT AUTO_INCREMENT NOT NULL, statut INT DEFAULT NULL, loyer INT DEFAULT NULL, reduction INT DEFAULT NULL, caution INT DEFAULT NULL, date_arivee VARCHAR(255) DEFAULT NULL, date_depart VARCHAR(255) DEFAULT NULL, date_resiliation VARCHAR(255) DEFAULT NULL, civilite VARCHAR(255) DEFAULT NULL, date_naissancce DATE DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, telephone_mobile INT DEFAULT NULL, telephone_fixe INT DEFAULT NULL, etablissement VARCHAR(255) DEFAULT NULL, immatriculation_vehicule VARCHAR(255) DEFAULT NULL, iban VARCHAR(255) DEFAULT NULL, code_swift VARCHAR(255) DEFAULT NULL, nom_cautionneur VARCHAR(255) DEFAULT NULL, prenom_cautionneur VARCHAR(255) DEFAULT NULL, adresse_cautionneur VARCHAR(255) DEFAULT NULL, ville_cautionneur VARCHAR(255) DEFAULT NULL, code_postal_cautionneur INT DEFAULT NULL, telephone_cautionneur INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, revenu_imposable INT DEFAULT NULL, age INT DEFAULT NULL, date_reservation VARCHAR(255) DEFAULT NULL, type_caution VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_medias (id INT AUTO_INCREMENT NOT NULL, catalogue_id_id INT DEFAULT NULL, nom_fichier VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, INDEX IDX_AEAC30E96758ECE6 (catalogue_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date DATE DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, sous_titre VARCHAR(255) DEFAULT NULL, texte LONGTEXT DEFAULT NULL, is_valid INT DEFAULT NULL, site_uid INT DEFAULT NULL, site_lang VARCHAR(255) DEFAULT NULL, INDEX IDX_54928197BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue (id INT AUTO_INCREMENT NOT NULL, categorie_id_id INT DEFAULT NULL, date_creation DATETIME DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, ingredients VARCHAR(255) DEFAULT NULL, conditionnement VARCHAR(255) DEFAULT NULL, is_online TINYINT(1) DEFAULT NULL, site_uid INT DEFAULT NULL, site_lang VARCHAR(10) DEFAULT NULL, ordre INT DEFAULT NULL, INDEX IDX_59A699F58A3C7387 (categorie_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actualite_categs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, site_uid INT DEFAULT NULL, site_lang VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire_actualite ADD CONSTRAINT FK_DB050EA0A2843073 FOREIGN KEY (actualite_id) REFERENCES actualite (id)');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D8B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE appartement_locataire ADD CONSTRAINT FK_50DC719E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appartement_locataire ADD CONSTRAINT FK_50DC719D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actualite_medias ADD CONSTRAINT FK_FBCB2B48A2843073 FOREIGN KEY (actualite_id) REFERENCES actualite (id)');
        $this->addSql('ALTER TABLE documents_client ADD CONSTRAINT FK_34C13213F55397E8 FOREIGN KEY (client_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appartement_medias ADD CONSTRAINT FK_88906E54E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE categs_documents_documents_client ADD CONSTRAINT FK_28D960A7BFECD78 FOREIGN KEY (categs_documents_id) REFERENCES categs_documents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categs_documents_documents_client ADD CONSTRAINT FK_28D960A779327E7A FOREIGN KEY (documents_client_id) REFERENCES documents_client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE catalogue_medias ADD CONSTRAINT FK_AEAC30E96758ECE6 FOREIGN KEY (catalogue_id_id) REFERENCES catalogue (id)');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_54928197BCF5E72D FOREIGN KEY (categorie_id) REFERENCES actualite_categs (id)');
        $this->addSql('ALTER TABLE catalogue ADD CONSTRAINT FK_59A699F58A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES catalogue_categs (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE catalogue DROP FOREIGN KEY FK_59A699F58A3C7387');
        $this->addSql('ALTER TABLE appartement_locataire DROP FOREIGN KEY FK_50DC719E1729BBA');
        $this->addSql('ALTER TABLE appartement_medias DROP FOREIGN KEY FK_88906E54E1729BBA');
        $this->addSql('ALTER TABLE categs_documents_documents_client DROP FOREIGN KEY FK_28D960A779327E7A');
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D8B225FBD');
        $this->addSql('ALTER TABLE documents_client DROP FOREIGN KEY FK_34C13213F55397E8');
        $this->addSql('ALTER TABLE categs_documents_documents_client DROP FOREIGN KEY FK_28D960A7BFECD78');
        $this->addSql('ALTER TABLE appartement_locataire DROP FOREIGN KEY FK_50DC719D8A38199');
        $this->addSql('ALTER TABLE commentaire_actualite DROP FOREIGN KEY FK_DB050EA0A2843073');
        $this->addSql('ALTER TABLE actualite_medias DROP FOREIGN KEY FK_FBCB2B48A2843073');
        $this->addSql('ALTER TABLE catalogue_medias DROP FOREIGN KEY FK_AEAC30E96758ECE6');
        $this->addSql('ALTER TABLE actualite DROP FOREIGN KEY FK_54928197BCF5E72D');
        $this->addSql('DROP TABLE commentaire_actualite');
        $this->addSql('DROP TABLE catalogue_categs');
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE appartement_locataire');
        $this->addSql('DROP TABLE actualite_medias');
        $this->addSql('DROP TABLE documents_client');
        $this->addSql('DROP TABLE residence');
        $this->addSql('DROP TABLE auto_promo');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE contact_historique');
        $this->addSql('DROP TABLE appartement_medias');
        $this->addSql('DROP TABLE categs_documents');
        $this->addSql('DROP TABLE categs_documents_documents_client');
        $this->addSql('DROP TABLE locataire');
        $this->addSql('DROP TABLE catalogue_medias');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE catalogue');
        $this->addSql('DROP TABLE actualite_categs');
    }
}
