<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207145500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours_app (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, difficulty VARCHAR(255) DEFAULT NULL, INDEX IDX_69A564A8CF11D9C (instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours_app ADD CONSTRAINT FK_69A564A8CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_app DROP FOREIGN KEY FK_69A564A8CF11D9C');
        $this->addSql('DROP TABLE cours_app');
    }
}
