<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206112228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(50) NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BEAB6C245F37A13B (token), INDEX IDX_BEAB6C24A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE password_token ADD CONSTRAINT FK_BEAB6C24A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE files ADD activities_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540592A4DB562 FOREIGN KEY (activities_id) REFERENCES activities (id)');
        $this->addSql('CREATE INDEX IDX_63540592A4DB562 ON files (activities_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE password_token DROP FOREIGN KEY FK_BEAB6C24A76ED395');
        $this->addSql('DROP TABLE password_token');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_63540592A4DB562');
        $this->addSql('DROP INDEX IDX_63540592A4DB562 ON files');
        $this->addSql('ALTER TABLE files DROP activities_id');
    }
}
