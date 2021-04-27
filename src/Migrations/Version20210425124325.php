<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425124325 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE edl (id INT AUTO_INCREMENT NOT NULL, entree_sonette_interphone INT DEFAULT NULL, entree_sonette_interphone_com LONGTEXT DEFAULT NULL, entree_porte_serrurerie INT DEFAULT NULL, entree_porte_serrurerie_com LONGTEXT DEFAULT NULL, entree_plafond INT DEFAULT NULL, entree_plafond_com LONGTEXT DEFAULT NULL, entree_revetements_muraux INT DEFAULT NULL, entree_revetements_muraux_com LONGTEXT DEFAULT NULL, entree_plinthes INT DEFAULT NULL, entree_plinthes_com LONGTEXT DEFAULT NULL, entree_sol INT DEFAULT NULL, entree_sol_com LONGTEXT DEFAULT NULL, entree_luminaire INT DEFAULT NULL, entree_luminaire_com LONGTEXT DEFAULT NULL, entree_interupt_prise INT DEFAULT NULL, entree_interupt_prise_com LONGTEXT DEFAULT NULL, entree_placard INT DEFAULT NULL, entree_placard_com LONGTEXT DEFAULT NULL, entree_fenetre INT DEFAULT NULL, entree_fenetre_com LONGTEXT DEFAULT NULL, entree_volet INT DEFAULT NULL, entree_volet_com LONGTEXT DEFAULT NULL, sdb_porte_serrurerie INT DEFAULT NULL, sdb_porte_serrurerie_com LONGTEXT DEFAULT NULL, sdb_plafond INT DEFAULT NULL, sdb_plafond_com LONGTEXT DEFAULT NULL, sdb_revetements_muraux INT DEFAULT NULL, sdb_revetements_muraux_com LONGTEXT DEFAULT NULL, sdb_plinthes INT DEFAULT NULL, sdb_plinthes_com LONGTEXT DEFAULT NULL, sdb_sol INT DEFAULT NULL, sdb_sol_com LONGTEXT DEFAULT NULL, sdb_luminaire INT DEFAULT NULL, sdb_luminaire_com LONGTEXT DEFAULT NULL, sdb_interupt_prise INT DEFAULT NULL, sdb_interupt_prise_com LONGTEXT DEFAULT NULL, sdb_radiateur INT DEFAULT NULL, sdb_radiateur_com LONGTEXT DEFAULT NULL, sdb_placard INT DEFAULT NULL, sdb_placard_com LONGTEXT DEFAULT NULL, sdb_fenetre INT DEFAULT NULL, sdb_fenetre_com LONGTEXT DEFAULT NULL, sdb_volet INT DEFAULT NULL, sdb_volet_com LONGTEXT DEFAULT NULL, sdb_lavabo INT DEFAULT NULL, sdb_lavabo_com LONGTEXT DEFAULT NULL, sdb_robinetterie_lavabo INT DEFAULT NULL, sdb_robinetterie_lavabo_com LONGTEXT DEFAULT NULL, sdb_douche INT DEFAULT NULL, sdb_douche_com LONGTEXT DEFAULT NULL, sdb_robinetterie_douche INT DEFAULT NULL, sdb_robinetterie_douche_com LONGTEXT DEFAULT NULL, sdb_paroi_douche INT DEFAULT NULL, sdb_paroi_douche_com LONGTEXT DEFAULT NULL, sdb_baignoire INT DEFAULT NULL, sdb_baignoire_com LONGTEXT DEFAULT NULL, sdb_robinetterie_baignoire INT DEFAULT NULL, sdb_robinetterie_baignoire_com LONGTEXT DEFAULT NULL, sdb_faience INT DEFAULT NULL, sdb_faience_com LONGTEXT DEFAULT NULL, sdb_joints INT DEFAULT NULL, sdb_joints_com LONGTEXT DEFAULT NULL, wc_porte_serrurerie INT DEFAULT NULL, wc_porte_serrurerie_com LONGTEXT DEFAULT NULL, wc_plafond INT DEFAULT NULL, wc_plafond_com LONGTEXT DEFAULT NULL, wc_revetement_muraux INT DEFAULT NULL, wc_revetement_muraux_com LONGTEXT DEFAULT NULL, wc_plinthes INT DEFAULT NULL, wc_plinthes_com LONGTEXT DEFAULT NULL, wc_sol INT DEFAULT NULL, wc_sol_com LONGTEXT DEFAULT NULL, wc_luminaire INT DEFAULT NULL, wc_luminaire_com LONGTEXT DEFAULT NULL, wc_interupt_prise INT DEFAULT NULL, wc_interupt_prise_com LONGTEXT DEFAULT NULL, wc_radiateur INT DEFAULT NULL, wc_radiateur_com LONGTEXT DEFAULT NULL, wc_cuvette_mecanisme INT NOT NULL, wc_cuvette_mecanisme_com LONGTEXT DEFAULT NULL, wc_abattant INT DEFAULT NULL, wc_abattant_com LONGTEXT DEFAULT NULL, wc_fenetre INT DEFAULT NULL, wc_fenetre_com LONGTEXT DEFAULT NULL, wc_volet INT DEFAULT NULL, wc_volet_com LONGTEXT DEFAULT NULL, wc_faiences INT DEFAULT NULL, wc_faiences_com LONGTEXT DEFAULT NULL, wc_joints INT DEFAULT NULL, wc_jointsâ_com LONGTEXT DEFAULT NULL, ch1_porte_serrurerie INT DEFAULT NULL, ch1_porte_serrurerie_com LONGTEXT DEFAULT NULL, ch1_plafond INT DEFAULT NULL, ch1_plafond_com LONGTEXT DEFAULT NULL, ch1_revetements_muraux INT DEFAULT NULL, ch1_revetements_muraux_com LONGTEXT DEFAULT NULL, ch1_plinthes INT DEFAULT NULL, ch1_plinthes_com LONGTEXT DEFAULT NULL, ch1_sol INT DEFAULT NULL, ch1_sol_com LONGTEXT DEFAULT NULL, ch1_luminaire INT DEFAULT NULL, ch1_luminaire_com LONGTEXT DEFAULT NULL, ch1_interupt_prise INT DEFAULT NULL, ch1_interupt_prise_com LONGTEXT DEFAULT NULL, ch1_radiateur INT DEFAULT NULL, ch1_radiateur_com INT DEFAULT NULL, ch1_placard INT DEFAULT NULL, ch1_placard_com LONGTEXT DEFAULT NULL, ch1_fenetre INT DEFAULT NULL, ch1_fenetre_com LONGTEXT DEFAULT NULL, ch1_volet INT DEFAULT NULL, ch1_volet_com LONGTEXT DEFAULT NULL, ch2_porte_serrurerie INT DEFAULT NULL, ch2_porte_serrurerie_com LONGTEXT DEFAULT NULL, ch2_plafond INT DEFAULT NULL, ch2_plafond_com LONGTEXT DEFAULT NULL, ch2_revetements_muraux INT DEFAULT NULL, ch2_revetements_muraux_com LONGTEXT DEFAULT NULL, ch2_plinthes INT DEFAULT NULL, ch2_plinthes_com LONGTEXT DEFAULT NULL, ch2_sol INT DEFAULT NULL, ch2_sol_com LONGTEXT DEFAULT NULL, ch2_luminaire INT DEFAULT NULL, ch2_luminaire_com LONGTEXT DEFAULT NULL, ch2_interupt_prise INT DEFAULT NULL, ch2_interupt_prise_com LONGTEXT DEFAULT NULL, ch2_radiateur INT DEFAULT NULL, ch2_radiateur_com LONGTEXT DEFAULT NULL, ch2_placard INT DEFAULT NULL, ch2_placard_com LONGTEXT DEFAULT NULL, ch2_fenetre INT DEFAULT NULL, ch2_fenetreâ_com LONGTEXT DEFAULT NULL, ch2_volet INT DEFAULT NULL, ch2_volet_com LONGTEXT DEFAULT NULL, ch3_porte_serrurerie INT DEFAULT NULL, ch3_porte_serrurerie_com LONGTEXT DEFAULT NULL, ch3_plafond INT DEFAULT NULL, ch3_plafond_com LONGTEXT DEFAULT NULL, ch3_revetements_muraux INT DEFAULT NULL, ch3_revetements_muraux_com LONGTEXT DEFAULT NULL, ch3_plinthes INT DEFAULT NULL, ch3_plinthes_com LONGTEXT DEFAULT NULL, ch3_sol INT DEFAULT NULL, ch3_sol_com LONGTEXT DEFAULT NULL, ch3_luminaire INT DEFAULT NULL, ch3_luminaire_com LONGTEXT DEFAULT NULL, ch3_interupt_prise INT DEFAULT NULL, ch3_interupt_prise_com LONGTEXT DEFAULT NULL, ch3_radiateur INT DEFAULT NULL, ch3_radiateur_com LONGTEXT DEFAULT NULL, ch3_placard INT DEFAULT NULL, ch3_placard_com LONGTEXT DEFAULT NULL, ch3_fenetre INT DEFAULT NULL, ch3_fenetre_com LONGTEXT DEFAULT NULL, ch3_volet INT DEFAULT NULL, ch3_volet_com LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE edl');
    }
}
