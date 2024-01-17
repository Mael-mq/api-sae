<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117175404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours ADD instrument_id INT DEFAULT NULL, ADD difficulty VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CCF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CCF11D9C ON cours (instrument_id)');
        $this->addSql('ALTER TABLE seance ADD description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP description');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CCF11D9C');
        $this->addSql('DROP INDEX IDX_FDCA8C9CCF11D9C ON cours');
        $this->addSql('ALTER TABLE cours DROP instrument_id, DROP difficulty');
    }
}
