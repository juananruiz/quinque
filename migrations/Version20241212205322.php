<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212205322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE unidad (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, codigo VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merito DROP FOREIGN KEY FK_44141D991CB9D6E4');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B3397707A');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B5A91C08D');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC04EE93BE6');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0F5F88DB9');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE convocatoria');
        $this->addSql('DROP TABLE departamento');
        $this->addSql('DROP TABLE merito');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3DD5D165C2');
        $this->addSql('ALTER TABLE peticion_solicitud ADD id_estado INT NOT NULL, ADD fecha_creacion DATETIME NOT NULL');
        $this->addSql('ALTER TABLE peticion_solicitud ADD CONSTRAINT FK_F59CC3D6A540E FOREIGN KEY (id_estado) REFERENCES estado (id)');
        $this->addSql('ALTER TABLE peticion_solicitud ADD CONSTRAINT FK_F59CC3DD5D165C2 FOREIGN KEY (id_unidad) REFERENCES unidad (id)');
        $this->addSql('CREATE INDEX IDX_F59CC3D6A540E ON peticion_solicitud (id_estado)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3DD5D165C2');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE convocatoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, activa INT NOT NULL, fecha_inicio_solicitud DATETIME NOT NULL, fecha_fin_solicitud DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE departamento (id INT AUTO_INCREMENT NOT NULL, codigo_rpt VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, codigo_uxxi VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE merito (id INT AUTO_INCREMENT NOT NULL, solicitud_id INT NOT NULL, organismo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, categoria_id INT NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, estado INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_44141D991CB9D6E4 (solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, departamento_id INT DEFAULT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, apellidos VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telefono VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, dni VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, fecha_nacimiento DATE NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_51E5B69B3397707A (categoria_id), INDEX IDX_51E5B69B5A91C08D (departamento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE solicitud (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, convocatoria_id INT NOT NULL, fecha_entrada DATETIME NOT NULL, INDEX IDX_96D27CC0F5F88DB9 (persona_id), INDEX IDX_96D27CC04EE93BE6 (convocatoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE merito ADD CONSTRAINT FK_44141D991CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC04EE93BE6 FOREIGN KEY (convocatoria_id) REFERENCES convocatoria (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE unidad');
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3D6A540E');
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3DD5D165C2');
        $this->addSql('DROP INDEX IDX_F59CC3D6A540E ON peticion_solicitud');
        $this->addSql('ALTER TABLE peticion_solicitud DROP id_estado, DROP fecha_creacion');
        $this->addSql('ALTER TABLE peticion_solicitud ADD CONSTRAINT FK_F59CC3DD5D165C2 FOREIGN KEY (id_unidad) REFERENCES app_unidad (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
