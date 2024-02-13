<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213144721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_sheet DROP FOREIGN KEY FK_4431698BCF11D9C');
        $this->addSql('DROP INDEX IDX_4431698BCF11D9C ON custom_sheet');
        $this->addSql('ALTER TABLE custom_sheet ADD instrument VARCHAR(255) DEFAULT NULL, DROP instrument_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_sheet ADD instrument_id INT DEFAULT NULL, DROP instrument');
        $this->addSql('ALTER TABLE custom_sheet ADD CONSTRAINT FK_4431698BCF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4431698BCF11D9C ON custom_sheet (instrument_id)');
    }
}
