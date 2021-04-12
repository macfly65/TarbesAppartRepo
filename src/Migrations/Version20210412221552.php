<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412221552 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categs_documents_documents_client DROP FOREIGN KEY FK_28D960A7BFECD78');
        $this->addSql('ALTER TABLE categs_documents_documents_client DROP FOREIGN KEY FK_28D960A779327E7A');
        $this->addSql('CREATE TABLE document_locataire (id INT AUTO_INCREMENT NOT NULL, locataire_id INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_D644DE4AD8A38199 (locataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_locataire ADD CONSTRAINT FK_D644DE4AD8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id)');
        $this->addSql('DROP TABLE categs_documents');
        $this->addSql('DROP TABLE categs_documents_documents_client');
        $this->addSql('DROP TABLE documents_client');
        $this->addSql('ALTER TABLE locataire CHANGE user_id user_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categs_documents (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categs_documents_documents_client (categs_documents_id INT NOT NULL, documents_client_id INT NOT NULL, INDEX IDX_28D960A7BFECD78 (categs_documents_id), INDEX IDX_28D960A779327E7A (documents_client_id), PRIMARY KEY(categs_documents_id, documents_client_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE documents_client (id INT AUTO_INCREMENT NOT NULL, client_user_id INT NOT NULL, nom_fichier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_34C13213F55397E8 (client_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE categs_documents_documents_client ADD CONSTRAINT FK_28D960A779327E7A FOREIGN KEY (documents_client_id) REFERENCES documents_client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categs_documents_documents_client ADD CONSTRAINT FK_28D960A7BFECD78 FOREIGN KEY (categs_documents_id) REFERENCES categs_documents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents_client ADD CONSTRAINT FK_34C13213F55397E8 FOREIGN KEY (client_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE document_locataire');
        $this->addSql('ALTER TABLE locataire CHANGE user_id user_id INT DEFAULT NULL');
    }
}
