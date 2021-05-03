<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503214907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edl ADD wc_joints_com LONGTEXT DEFAULT NULL, ADD ch2_fenetre_com LONGTEXT DEFAULT NULL, ADD sejour_sonette_interphone INT DEFAULT NULL, ADD sejour_porte_serrurerie INT DEFAULT NULL, ADD sejour_porte_serrurerie_com LONGTEXT DEFAULT NULL, ADD sejour_sonette_interphone_com LONGTEXT DEFAULT NULL, ADD sejour_plafond INT DEFAULT NULL, ADD sejour_plafond_com LONGTEXT DEFAULT NULL, ADD sejour_revetement_muraux INT DEFAULT NULL, ADD sejour_revetement_muraux_com LONGTEXT DEFAULT NULL, ADD sejour_plinthes INT DEFAULT NULL, ADD sejour_plinthes_com LONGTEXT DEFAULT NULL, ADD sejour_sol INT DEFAULT NULL, ADD sejour_sol_com LONGTEXT DEFAULT NULL, ADD sejour_luminaire INT DEFAULT NULL, ADD sejour_luminaire_com LONGTEXT DEFAULT NULL, ADD sejour_interupt_prise INT DEFAULT NULL, ADD sejour_interupt_prise_com LONGTEXT DEFAULT NULL, ADD sejour_placard INT DEFAULT NULL, ADD sejour_placard_com LONGTEXT DEFAULT NULL, ADD sejour_fenetre INT DEFAULT NULL, ADD sejour_fenetre_com LONGTEXT DEFAULT NULL, ADD sejour_volet INT DEFAULT NULL, ADD sejour_faience INT DEFAULT NULL, ADD sejour_faience_com LONGTEXT DEFAULT NULL, ADD sejour_paillasse INT DEFAULT NULL, ADD sejour_paillasse_com LONGTEXT DEFAULT NULL, ADD sejour_evier INT DEFAULT NULL, ADD sejour_evier_com LONGTEXT DEFAULT NULL, ADD sejour_robinetterie INT DEFAULT NULL, ADD sejour_robinetterie_com LONGTEXT DEFAULT NULL, ADD sejour_vmc INT DEFAULT NULL, ADD sejour_vmc_com LONGTEXT DEFAULT NULL, ADD sejour_table_cuisson INT DEFAULT NULL, ADD sejour_table_cuisson_com LONGTEXT DEFAULT NULL, ADD sejour_frigo INT DEFAULT NULL, ADD sejour_frigo_com LONGTEXT DEFAULT NULL, ADD sejour_hotte INT DEFAULT NULL, ADD sejour_hotte_com LONGTEXT DEFAULT NULL, ADD sejour_reglette_lumineuse INT DEFAULT NULL, ADD sejour_reglette_lumineuse_com LONGTEXT DEFAULT NULL, ADD sejour_radiateur INT DEFAULT NULL, ADD sejour_radiateur_com LONGTEXT DEFAULT NULL, ADD sejour_meuble_cuisine_bas INT DEFAULT NULL, ADD sejour_meuble_cuisine_bas_com LONGTEXT DEFAULT NULL, ADD sejour_meuble_cuisine_haut INT DEFAULT NULL, ADD sejour_meuble_cuisine_haut_com LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edl ADD wc_joints_com LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD ch2_fenetre?_com LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP wc_joints_com, DROP ch2_fenetre_com, DROP sejour_sonette_interphone, DROP sejour_porte_serrurerie, DROP sejour_porte_serrurerie_com, DROP sejour_sonette_interphone_com, DROP sejour_plafond, DROP sejour_plafond_com, DROP sejour_revetement_muraux, DROP sejour_revetement_muraux_com, DROP sejour_plinthes, DROP sejour_plinthes_com, DROP sejour_sol, DROP sejour_sol_com, DROP sejour_luminaire, DROP sejour_luminaire_com, DROP sejour_interupt_prise, DROP sejour_interupt_prise_com, DROP sejour_placard, DROP sejour_placard_com, DROP sejour_fenetre, DROP sejour_fenetre_com, DROP sejour_volet, DROP sejour_faience, DROP sejour_faience_com, DROP sejour_paillasse, DROP sejour_paillasse_com, DROP sejour_evier, DROP sejour_evier_com, DROP sejour_robinetterie, DROP sejour_robinetterie_com, DROP sejour_vmc, DROP sejour_vmc_com, DROP sejour_table_cuisson, DROP sejour_table_cuisson_com, DROP sejour_frigo, DROP sejour_frigo_com, DROP sejour_hotte, DROP sejour_hotte_com, DROP sejour_reglette_lumineuse, DROP sejour_reglette_lumineuse_com, DROP sejour_radiateur, DROP sejour_radiateur_com, DROP sejour_meuble_cuisine_bas, DROP sejour_meuble_cuisine_bas_com, DROP sejour_meuble_cuisine_haut, DROP sejour_meuble_cuisine_haut_com');
    }
}
