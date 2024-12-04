<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204194555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud ADD CONSTRAINT FK_F59CC3D6FE5CFB8 FOREIGN KEY (id_solicitante) REFERENCES app_persona (id)');
        $this->addSql('ALTER TABLE peticion_solicitud ADD CONSTRAINT FK_F59CC3DBEA15FED FOREIGN KEY (id_asignado) REFERENCES app_persona (id)');
        $this->addSql('ALTER TABLE peticion_solicitud ADD CONSTRAINT FK_F59CC3DD5D165C2 FOREIGN KEY (id_unidad) REFERENCES app_unidad (id)');
        $this->addSql('CREATE INDEX IDX_F59CC3D6FE5CFB8 ON peticion_solicitud (id_solicitante)');
        $this->addSql('CREATE INDEX IDX_F59CC3DBEA15FED ON peticion_solicitud (id_asignado)');
        $this->addSql('CREATE INDEX IDX_F59CC3DD5D165C2 ON peticion_solicitud (id_unidad)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3D6FE5CFB8');
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3DBEA15FED');
        $this->addSql('ALTER TABLE peticion_solicitud DROP FOREIGN KEY FK_F59CC3DD5D165C2');
        $this->addSql('DROP INDEX IDX_F59CC3D6FE5CFB8 ON peticion_solicitud');
        $this->addSql('DROP INDEX IDX_F59CC3DBEA15FED ON peticion_solicitud');
        $this->addSql('DROP INDEX IDX_F59CC3DD5D165C2 ON peticion_solicitud');
    }
}
