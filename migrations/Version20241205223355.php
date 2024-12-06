<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241205223355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud CHANGE contenido contenido LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE quinque_convocatoria ADD fecha_resolucion DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud CHANGE contenido contenido LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE quinque_convocatoria DROP fecha_resolucion');
    }
}
