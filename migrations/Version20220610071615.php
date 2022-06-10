<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610071615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet_pp (id INT AUTO_INCREMENT NOT NULL, relation_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, archive TINYINT(1) NOT NULL, lien_projet VARCHAR(255) NOT NULL, main_image VARCHAR(255) NOT NULL, lien_github VARCHAR(255) NOT NULL, INDEX IDX_811BFF173256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_pp (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, actif TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_26B4AEF05126AC48 (mail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet_pp ADD CONSTRAINT FK_811BFF173256915B FOREIGN KEY (relation_id) REFERENCES user_pp (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_pp DROP FOREIGN KEY FK_811BFF173256915B');
        $this->addSql('DROP TABLE projet_pp');
        $this->addSql('DROP TABLE user_pp');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
