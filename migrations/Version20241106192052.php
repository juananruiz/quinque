<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106192052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE convocatoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, fecha_inicio_solicitudes DATETIME NOT NULL, fecha_fin_solicitudes DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona CHANGE fecha_nacimiento fecha_nacimiento DATE NOT NULL');
        $this->addSql('ALTER TABLE solicitud CHANGE convocatoria convocatoria VARCHAR(25) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE convocatoria');
        $this->addSql('ALTER TABLE persona CHANGE fecha_nacimiento fecha_nacimiento DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud CHANGE convocatoria convocatoria VARCHAR(25) DEFAULT NULL');
    }
}
