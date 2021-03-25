<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325192036 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pt (id INT AUTO_INCREMENT NOT NULL, prestataire_id INT DEFAULT NULL, appartement_id INT DEFAULT NULL, date_demande DATE DEFAULT NULL, date_lecture_presta DATE DEFAULT NULL, date_fin_travaux DATE DEFAULT NULL, date_validation DATE DEFAULT NULL, statut INT DEFAULT NULL, coment_locataire LONGTEXT DEFAULT NULL, coment_gestionaire_to_locataire LONGTEXT DEFAULT NULL, coment_gestionaire_to_prestataire LONGTEXT DEFAULT NULL, coment_presta LONGTEXT DEFAULT NULL, INDEX IDX_398EDE2CBE3DB2B7 (prestataire_id), INDEX IDX_398EDE2CE1729BBA (appartement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, tel INT DEFAULT NULL, statut INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pt ADD CONSTRAINT FK_398EDE2CBE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id)');
        $this->addSql('ALTER TABLE pt ADD CONSTRAINT FK_398EDE2CE1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pt DROP FOREIGN KEY FK_398EDE2CBE3DB2B7');
        $this->addSql('DROP TABLE pt');
        $this->addSql('DROP TABLE prestataire');
    }
}
