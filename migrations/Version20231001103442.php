<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001103442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consomation ADD consomation_predit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consomation ADD CONSTRAINT FK_B2D1504B7F82657A FOREIGN KEY (consomation_predit_id) REFERENCES consomation_predit (id)');
        $this->addSql('CREATE INDEX IDX_B2D1504B7F82657A ON consomation (consomation_predit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consomation DROP FOREIGN KEY FK_B2D1504B7F82657A');
        $this->addSql('DROP INDEX IDX_B2D1504B7F82657A ON consomation');
        $this->addSql('ALTER TABLE consomation DROP consomation_predit_id');
    }
}
