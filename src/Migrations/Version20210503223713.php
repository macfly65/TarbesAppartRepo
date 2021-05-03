<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503223713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edl ADD couloir_porte_serrurerie INT DEFAULT NULL, ADD couloir_porte_serrurerie�_com LONGTEXT DEFAULT NULL, ADD couloir_plafond INT DEFAULT NULL, ADD couloir_plafond_com LONGTEXT DEFAULT NULL, ADD couloir_revetement_muraux INT DEFAULT NULL, ADD couloir_revetement_muraux_com LONGTEXT DEFAULT NULL, ADD couloir_plinthes INT DEFAULT NULL, ADD couloir_plinthes_com LONGTEXT DEFAULT NULL, ADD couloir_sol INT DEFAULT NULL, ADD couloir_sol_com LONGTEXT DEFAULT NULL, ADD couloir_luminaire INT DEFAULT NULL, ADD couloir_luminaire_com INT DEFAULT NULL, ADD couloir_interupt_prise INT DEFAULT NULL, ADD couloir_interupt_prise_com LONGTEXT DEFAULT NULL, ADD couloir_fenetre INT DEFAULT NULL, ADD couloir_fenetre_com LONGTEXT DEFAULT NULL, ADD couloir_volet INT DEFAULT NULL, ADD couloir_volet_com LONGTEXT DEFAULT NULL, DROP wc_joints_com, DROP ch2_fenetre_com');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edl ADD wc_joints?_com LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD ch2_fenetre?_com LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP couloir_porte_serrurerie, DROP couloir_porte_serrurerie_com, DROP couloir_plafond, DROP couloir_plafond_com, DROP couloir_revetement_muraux, DROP couloir_revetement_muraux_com, DROP couloir_plinthes, DROP couloir_plinthes_com, DROP couloir_sol, DROP couloir_sol_com, DROP couloir_luminaire, DROP couloir_luminaire_com, DROP couloir_interupt_prise, DROP couloir_interupt_prise_com, DROP couloir_fenetre, DROP couloir_fenetre_com, DROP couloir_volet, DROP couloir_volet_com');
    }
}
