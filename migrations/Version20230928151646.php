<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928151646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appareil ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appareil ADD CONSTRAINT FK_456A601A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_456A601A19EB6921 ON appareil (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appareil DROP FOREIGN KEY FK_456A601A19EB6921');
        $this->addSql('DROP INDEX IDX_456A601A19EB6921 ON appareil');
        $this->addSql('ALTER TABLE appareil DROP client_id');
    }
}
