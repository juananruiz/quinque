<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113173918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estado (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, color VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categoria CHANGE abreviatura abreviatura VARCHAR(6) NOT NULL');
        $this->addSql('ALTER TABLE merito CHANGE estado estado_id INT NOT NULL');
        $this->addSql('ALTER TABLE merito ADD CONSTRAINT FK_44141D999F5A440B FOREIGN KEY (estado_id) REFERENCES estado (id)');
        $this->addSql('CREATE INDEX IDX_44141D999F5A440B ON merito (estado_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merito DROP FOREIGN KEY FK_44141D999F5A440B');
        $this->addSql('DROP TABLE estado');
        $this->addSql('ALTER TABLE categoria CHANGE abreviatura abreviatura VARCHAR(6) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_44141D999F5A440B ON merito');
        $this->addSql('ALTER TABLE merito CHANGE estado_id estado INT NOT NULL');
    }
}
