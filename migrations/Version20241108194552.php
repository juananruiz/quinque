<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108194552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departamento (id INT AUTO_INCREMENT NOT NULL, codigo_rpt VARCHAR(15) DEFAULT NULL, codigo_uxxi VARCHAR(15) DEFAULT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona ADD departamento_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id)');
        $this->addSql('CREATE INDEX IDX_51E5B69B5A91C08D ON persona (departamento_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B5A91C08D');
        $this->addSql('DROP TABLE departamento');
        $this->addSql('DROP INDEX IDX_51E5B69B5A91C08D ON persona');
        $this->addSql('ALTER TABLE persona DROP departamento_id');
    }
}
