<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241117172701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quinque_merito ADD observaciones LONGTEXT DEFAULT NULL, CHANGE dedicacion dedicacion INT NOT NULL');
        $this->addSql('ALTER TABLE quinque_merito ADD CONSTRAINT FK_ADA6EC433397707A FOREIGN KEY (categoria_id) REFERENCES quinque_categoria (id)');
        $this->addSql('CREATE INDEX IDX_ADA6EC433397707A ON quinque_merito (categoria_id)');
        $this->addSql('ALTER TABLE quinque_merito RENAME INDEX idx_44141d999f5a440b TO IDX_ADA6EC439F5A440B');
        $this->addSql('ALTER TABLE quinque_merito RENAME INDEX idx_44141d991cb9d6e4 TO IDX_ADA6EC431CB9D6E4');
        $this->addSql('ALTER TABLE quinque_persona RENAME INDEX idx_51e5b69b3397707a TO IDX_370A3FA03397707A');
        $this->addSql('ALTER TABLE quinque_persona RENAME INDEX idx_51e5b69b5a91c08d TO IDX_370A3FA05A91C08D');
        $this->addSql('ALTER TABLE quinque_solicitud RENAME INDEX idx_96d27cc0f5f88db9 TO IDX_3E04CE93F5F88DB9');
        $this->addSql('ALTER TABLE quinque_solicitud RENAME INDEX idx_96d27cc04ee93be6 TO IDX_3E04CE934EE93BE6');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quinque_solicitud RENAME INDEX idx_3e04ce934ee93be6 TO IDX_96D27CC04EE93BE6');
        $this->addSql('ALTER TABLE quinque_solicitud RENAME INDEX idx_3e04ce93f5f88db9 TO IDX_96D27CC0F5F88DB9');
        $this->addSql('ALTER TABLE quinque_persona RENAME INDEX idx_370a3fa03397707a TO IDX_51E5B69B3397707A');
        $this->addSql('ALTER TABLE quinque_persona RENAME INDEX idx_370a3fa05a91c08d TO IDX_51E5B69B5A91C08D');
        $this->addSql('ALTER TABLE quinque_merito DROP FOREIGN KEY FK_ADA6EC433397707A');
        $this->addSql('DROP INDEX IDX_ADA6EC433397707A ON quinque_merito');
        $this->addSql('ALTER TABLE quinque_merito DROP observaciones, CHANGE dedicacion dedicacion INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quinque_merito RENAME INDEX idx_ada6ec431cb9d6e4 TO IDX_44141D991CB9D6E4');
        $this->addSql('ALTER TABLE quinque_merito RENAME INDEX idx_ada6ec439f5a440b TO IDX_44141D999F5A440B');
    }
}
