<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108185823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE convocatoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, activa INT NOT NULL, fecha_inicio_solicitud DATETIME NOT NULL, fecha_fin_solicitud DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merito (id INT AUTO_INCREMENT NOT NULL, solicitud_id INT NOT NULL, organismo VARCHAR(255) NOT NULL, categoria_id INT NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, estado INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_44141D991CB9D6E4 (solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, telefono VARCHAR(25) DEFAULT NULL, dni VARCHAR(25) DEFAULT NULL, fecha_nacimiento DATE NOT NULL, INDEX IDX_51E5B69B3397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solicitud (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, convocatoria_id INT NOT NULL, fecha_entrada DATETIME NOT NULL, INDEX IDX_96D27CC0F5F88DB9 (persona_id), INDEX IDX_96D27CC04EE93BE6 (convocatoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merito ADD CONSTRAINT FK_44141D991CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC04EE93BE6 FOREIGN KEY (convocatoria_id) REFERENCES convocatoria (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merito DROP FOREIGN KEY FK_44141D991CB9D6E4');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B3397707A');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0F5F88DB9');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC04EE93BE6');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE convocatoria');
        $this->addSql('DROP TABLE merito');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE solicitud');
    }
}
