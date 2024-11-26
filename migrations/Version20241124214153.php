<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124214153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quinque_categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, abreviatura VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_convocatoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, activa INT NOT NULL, fecha_inicio_solicitud DATETIME NOT NULL, fecha_fin_solicitud DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_departamento (id INT AUTO_INCREMENT NOT NULL, codigo_rpt VARCHAR(15) DEFAULT NULL, codigo_uxxi VARCHAR(15) DEFAULT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_merito_estado (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, color VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_merito (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, estado_id INT NOT NULL, solicitud_id INT NOT NULL, organismo VARCHAR(255) NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, dedicacion INT NOT NULL, observaciones LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_ADA6EC433397707A (categoria_id), INDEX IDX_ADA6EC439F5A440B (estado_id), INDEX IDX_ADA6EC431CB9D6E4 (solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_persona (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, departamento_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, telefono VARCHAR(25) DEFAULT NULL, dni VARCHAR(25) DEFAULT NULL, fecha_nacimiento DATE NOT NULL, email VARCHAR(180) DEFAULT NULL, INDEX IDX_370A3FA03397707A (categoria_id), INDEX IDX_370A3FA05A91C08D (departamento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_solicitud (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, convocatoria_id INT NOT NULL, estado_id INT NOT NULL, fecha_entrada DATETIME NOT NULL, INDEX IDX_3E04CE93F5F88DB9 (persona_id), INDEX IDX_3E04CE934EE93BE6 (convocatoria_id), INDEX IDX_3E04CE939F5A440B (estado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quinque_solicitud_estado (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, color VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quinque_merito ADD CONSTRAINT FK_ADA6EC433397707A FOREIGN KEY (categoria_id) REFERENCES quinque_categoria (id)');
        $this->addSql('ALTER TABLE quinque_merito ADD CONSTRAINT FK_ADA6EC439F5A440B FOREIGN KEY (estado_id) REFERENCES quinque_merito_estado (id)');
        $this->addSql('ALTER TABLE quinque_merito ADD CONSTRAINT FK_ADA6EC431CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES quinque_solicitud (id)');
        $this->addSql('ALTER TABLE quinque_persona ADD CONSTRAINT FK_370A3FA03397707A FOREIGN KEY (categoria_id) REFERENCES quinque_categoria (id)');
        $this->addSql('ALTER TABLE quinque_persona ADD CONSTRAINT FK_370A3FA05A91C08D FOREIGN KEY (departamento_id) REFERENCES quinque_departamento (id)');
        $this->addSql('ALTER TABLE quinque_solicitud ADD CONSTRAINT FK_3E04CE93F5F88DB9 FOREIGN KEY (persona_id) REFERENCES quinque_persona (id)');
        $this->addSql('ALTER TABLE quinque_solicitud ADD CONSTRAINT FK_3E04CE934EE93BE6 FOREIGN KEY (convocatoria_id) REFERENCES quinque_convocatoria (id)');
        $this->addSql('ALTER TABLE quinque_solicitud ADD CONSTRAINT FK_3E04CE939F5A440B FOREIGN KEY (estado_id) REFERENCES quinque_solicitud_estado (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quinque_merito DROP FOREIGN KEY FK_ADA6EC433397707A');
        $this->addSql('ALTER TABLE quinque_merito DROP FOREIGN KEY FK_ADA6EC439F5A440B');
        $this->addSql('ALTER TABLE quinque_merito DROP FOREIGN KEY FK_ADA6EC431CB9D6E4');
        $this->addSql('ALTER TABLE quinque_persona DROP FOREIGN KEY FK_370A3FA03397707A');
        $this->addSql('ALTER TABLE quinque_persona DROP FOREIGN KEY FK_370A3FA05A91C08D');
        $this->addSql('ALTER TABLE quinque_solicitud DROP FOREIGN KEY FK_3E04CE93F5F88DB9');
        $this->addSql('ALTER TABLE quinque_solicitud DROP FOREIGN KEY FK_3E04CE934EE93BE6');
        $this->addSql('ALTER TABLE quinque_solicitud DROP FOREIGN KEY FK_3E04CE939F5A440B');
        $this->addSql('DROP TABLE quinque_categoria');
        $this->addSql('DROP TABLE quinque_convocatoria');
        $this->addSql('DROP TABLE quinque_departamento');
        $this->addSql('DROP TABLE quinque_merito_estado');
        $this->addSql('DROP TABLE quinque_merito');
        $this->addSql('DROP TABLE quinque_persona');
        $this->addSql('DROP TABLE quinque_solicitud');
        $this->addSql('DROP TABLE quinque_solicitud_estado');
    }
}
