<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116110314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activities (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, seance_id INT DEFAULT NULL, sheet_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_B5F1AFE57ECF78B0 (cours_id), INDEX IDX_B5F1AFE5E3797A94 (seance_id), INDEX IDX_B5F1AFE58B1206A5 (sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE57ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE5E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE58B1206A5 FOREIGN KEY (sheet_id) REFERENCES sheet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activities DROP FOREIGN KEY FK_B5F1AFE57ECF78B0');
        $this->addSql('ALTER TABLE activities DROP FOREIGN KEY FK_B5F1AFE5E3797A94');
        $this->addSql('ALTER TABLE activities DROP FOREIGN KEY FK_B5F1AFE58B1206A5');
        $this->addSql('DROP TABLE activities');
    }
}
