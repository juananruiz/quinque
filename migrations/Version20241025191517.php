<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025191517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE merito (id INT AUTO_INCREMENT NOT NULL, solicitud_id INT NOT NULL, organismo VARCHAR(255) NOT NULL, categoria_id INT NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, estado INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_44141D991CB9D6E4 (solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solicitud (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, fecha_entrada DATETIME NOT NULL, INDEX IDX_96D27CC0F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merito ADD CONSTRAINT FK_44141D991CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id)');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE quinquenio_merito DROP FOREIGN KEY FK_10AEEF771B7987B9');
        $this->addSql('ALTER TABLE quinquenio_solicitud DROP FOREIGN KEY FK_38905893F5F88DB9');
        $this->addSql('DROP TABLE quinquenio_merito');
        $this->addSql('DROP TABLE quinquenio_solicitud');
        $this->addSql('ALTER TABLE persona DROP fecha_nacimiento, DROP dni');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quinquenio_merito (id INT AUTO_INCREMENT NOT NULL, quinquenio_solicitud_id INT NOT NULL, organismo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, categoria_id INT NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, estado INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX IDX_10AEEF771B7987B9 (quinquenio_solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quinquenio_solicitud (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, fecha_entrada DATETIME NOT NULL, INDEX IDX_38905893F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE quinquenio_merito ADD CONSTRAINT FK_10AEEF771B7987B9 FOREIGN KEY (quinquenio_solicitud_id) REFERENCES quinquenio_solicitud (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE quinquenio_solicitud ADD CONSTRAINT FK_38905893F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE merito DROP FOREIGN KEY FK_44141D991CB9D6E4');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0F5F88DB9');
        $this->addSql('DROP TABLE merito');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql('ALTER TABLE persona ADD fecha_nacimiento DATE DEFAULT NULL, ADD dni VARCHAR(25) NOT NULL');
    }
}
