<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212163801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE custom_sheet (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, score_key VARCHAR(255) DEFAULT NULL, difficulty VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, INDEX IDX_4431698BCF11D9C (instrument_id), INDEX IDX_4431698BF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vault_custom_sheet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, custom_sheet_id INT DEFAULT NULL, is_favorite TINYINT(1) DEFAULT NULL, INDEX IDX_1465883BA76ED395 (user_id), INDEX IDX_1465883B6EC0E1C1 (custom_sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE custom_sheet ADD CONSTRAINT FK_4431698BCF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE custom_sheet ADD CONSTRAINT FK_4431698BF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vault_custom_sheet ADD CONSTRAINT FK_1465883BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vault_custom_sheet ADD CONSTRAINT FK_1465883B6EC0E1C1 FOREIGN KEY (custom_sheet_id) REFERENCES custom_sheet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_sheet DROP FOREIGN KEY FK_4431698BCF11D9C');
        $this->addSql('ALTER TABLE custom_sheet DROP FOREIGN KEY FK_4431698BF675F31B');
        $this->addSql('ALTER TABLE vault_custom_sheet DROP FOREIGN KEY FK_1465883BA76ED395');
        $this->addSql('ALTER TABLE vault_custom_sheet DROP FOREIGN KEY FK_1465883B6EC0E1C1');
        $this->addSql('DROP TABLE custom_sheet');
        $this->addSql('DROP TABLE vault_custom_sheet');
    }
}
