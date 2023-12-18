<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212103821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_instrument (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_9BD8AF31CF11D9C (instrument_id), INDEX IDX_9BD8AF31A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_instrument ADD CONSTRAINT FK_9BD8AF31CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE user_instrument ADD CONSTRAINT FK_9BD8AF31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_instrument DROP FOREIGN KEY FK_9BD8AF31CF11D9C');
        $this->addSql('ALTER TABLE user_instrument DROP FOREIGN KEY FK_9BD8AF31A76ED395');
        $this->addSql('DROP TABLE user_instrument');
    }
}
