<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230930154440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consomation_predit ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consomation_predit ADD CONSTRAINT FK_29DC00C419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_29DC00C419EB6921 ON consomation_predit (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consomation_predit DROP FOREIGN KEY FK_29DC00C419EB6921');
        $this->addSql('DROP INDEX IDX_29DC00C419EB6921 ON consomation_predit');
        $this->addSql('ALTER TABLE consomation_predit DROP client_id');
    }
}
