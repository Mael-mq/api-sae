<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221095917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sheet (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_873C91E2CF11D9C (instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vault_sheet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, sheet_id INT DEFAULT NULL, INDEX IDX_DAA45D99A76ED395 (user_id), INDEX IDX_DAA45D998B1206A5 (sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sheet ADD CONSTRAINT FK_873C91E2CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE vault_sheet ADD CONSTRAINT FK_DAA45D99A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vault_sheet ADD CONSTRAINT FK_DAA45D998B1206A5 FOREIGN KEY (sheet_id) REFERENCES sheet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sheet DROP FOREIGN KEY FK_873C91E2CF11D9C');
        $this->addSql('ALTER TABLE vault_sheet DROP FOREIGN KEY FK_DAA45D99A76ED395');
        $this->addSql('ALTER TABLE vault_sheet DROP FOREIGN KEY FK_DAA45D998B1206A5');
        $this->addSql('DROP TABLE sheet');
        $this->addSql('DROP TABLE vault_sheet');
    }
}
