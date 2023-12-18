<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213133125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours_app_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cours_app_id INT DEFAULT NULL, is_finished TINYINT(1) NOT NULL, INDEX IDX_4F295D34A76ED395 (user_id), INDEX IDX_4F295D3416073045 (cours_app_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_app (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_app_user (id INT AUTO_INCREMENT NOT NULL, exercice_app_id INT DEFAULT NULL, user_id INT DEFAULT NULL, is_finished TINYINT(1) NOT NULL, INDEX IDX_202797CAA008F637 (exercice_app_id), INDEX IDX_202797CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours_app_user ADD CONSTRAINT FK_4F295D34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours_app_user ADD CONSTRAINT FK_4F295D3416073045 FOREIGN KEY (cours_app_id) REFERENCES cours_app (id)');
        $this->addSql('ALTER TABLE exercice_app_user ADD CONSTRAINT FK_202797CAA008F637 FOREIGN KEY (exercice_app_id) REFERENCES exercice_app (id)');
        $this->addSql('ALTER TABLE exercice_app_user ADD CONSTRAINT FK_202797CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_app_user DROP FOREIGN KEY FK_4F295D34A76ED395');
        $this->addSql('ALTER TABLE cours_app_user DROP FOREIGN KEY FK_4F295D3416073045');
        $this->addSql('ALTER TABLE exercice_app_user DROP FOREIGN KEY FK_202797CAA008F637');
        $this->addSql('ALTER TABLE exercice_app_user DROP FOREIGN KEY FK_202797CAA76ED395');
        $this->addSql('DROP TABLE cours_app_user');
        $this->addSql('DROP TABLE exercice_app');
        $this->addSql('DROP TABLE exercice_app_user');
    }
}
