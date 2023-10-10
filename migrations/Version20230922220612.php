<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922220612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appareil (id INT AUTO_INCREMENT NOT NULL, appareil_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, client_id VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consomation (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, appareil_id_id INT NOT NULL, date DATETIME NOT NULL, consomation DOUBLE PRECISION NOT NULL, INDEX IDX_B2D1504BDC2902E0 (client_id_id), INDEX IDX_B2D1504BECC97C37 (appareil_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consomation_predit (id INT AUTO_INCREMENT NOT NULL, consomation DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consomation ADD CONSTRAINT FK_B2D1504BDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE consomation ADD CONSTRAINT FK_B2D1504BECC97C37 FOREIGN KEY (appareil_id_id) REFERENCES appareil (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consomation DROP FOREIGN KEY FK_B2D1504BDC2902E0');
        $this->addSql('ALTER TABLE consomation DROP FOREIGN KEY FK_B2D1504BECC97C37');
        $this->addSql('DROP TABLE appareil');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE consomation');
        $this->addSql('DROP TABLE consomation_predit');
    }
}
