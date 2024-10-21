<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241020200048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quinquenio_merito (id INT AUTO_INCREMENT NOT NULL, quinquenio_solicitud_id INT NOT NULL, organismo VARCHAR(255) NOT NULL, categoria_id INT NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, estado INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_10AEEF771B7987B9 (quinquenio_solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quinquenio_merito ADD CONSTRAINT FK_10AEEF771B7987B9 FOREIGN KEY (quinquenio_solicitud_id) REFERENCES quinquenio_solicitud (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quinquenio_merito DROP FOREIGN KEY FK_10AEEF771B7987B9');
        $this->addSql('DROP TABLE quinquenio_merito');
    }
}
