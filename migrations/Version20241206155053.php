<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241206155053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud CHANGE contenido contenido LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE quinque_convocatoria ADD delega_firma_fecha DATETIME DEFAULT NULL, ADD delega_firma_persona VARCHAR(255) DEFAULT NULL, ADD delega_firma_cargo VARCHAR(255) DEFAULT NULL, CHANGE fecha_resolucion fecha_resolucion DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud CHANGE contenido contenido LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE quinque_convocatoria DROP delega_firma_fecha, DROP delega_firma_persona, DROP delega_firma_cargo, CHANGE fecha_resolucion fecha_resolucion DATE DEFAULT NULL');
    }
}
