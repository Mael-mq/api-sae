<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122105438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files ADD activities_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540592A4DB562 FOREIGN KEY (activities_id) REFERENCES activities (id)');
        $this->addSql('CREATE INDEX IDX_63540592A4DB562 ON files (activities_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_63540592A4DB562');
        $this->addSql('DROP INDEX IDX_63540592A4DB562 ON files');
        $this->addSql('ALTER TABLE files DROP activities_id');
    }
}
