<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241020192532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud ADD persona_id INT NOT NULL');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_38905893F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('CREATE INDEX IDX_38905893F5F88DB9 ON solicitud (persona_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_38905893F5F88DB9');
        $this->addSql('DROP INDEX IDX_38905893F5F88DB9 ON solicitud');
        $this->addSql('ALTER TABLE solicitud DROP persona_id');
    }
}
