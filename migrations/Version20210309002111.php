<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309002111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, channel_name VARCHAR(255) NOT NULL, channel_description VARCHAR(255) NOT NULL, channel_creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, notification_date DATETIME NOT NULL, notification_title VARCHAR(255) NOT NULL, notification_description VARCHAR(255) NOT NULL, is_viewed TINYINT(1) NOT NULL, INDEX IDX_BF5476CA9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, playlist_name VARCHAR(255) NOT NULL, playlist_description VARCHAR(255) NOT NULL, playlist_creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcast (id INT AUTO_INCREMENT NOT NULL, playlist_id_id INT DEFAULT NULL, podcast_name VARCHAR(255) NOT NULL, comments_allowed INT NOT NULL, podcast_description VARCHAR(255) DEFAULT NULL, podcast_image VARCHAR(255) DEFAULT NULL, podcast_views INT DEFAULT NULL, podcast_date DATETIME NOT NULL, podcast_source VARCHAR(255) NOT NULL, INDEX IDX_D7E805BDDC588714 (playlist_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcast_comment (id INT AUTO_INCREMENT NOT NULL, podcast_id_id INT NOT NULL, user_id_id INT NOT NULL, comment_text VARCHAR(255) NOT NULL, comment_date DATETIME NOT NULL, INDEX IDX_3E356566C5B772 (podcast_id_id), INDEX IDX_3E3565669D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcast_review (id INT AUTO_INCREMENT NOT NULL, podcast_id_id INT NOT NULL, user_id_id INT NOT NULL, rating INT NOT NULL, INDEX IDX_3238D4A8C5B772 (podcast_id_id), INDEX IDX_3238D4A89D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, podcast_id_id INT NOT NULL, user_id_id INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_CE606404C5B772 (podcast_id_id), INDEX IDX_CE6064049D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_info_id_id INT DEFAULT NULL, channel_id_id INT DEFAULT NULL, user_email VARCHAR(255) NOT NULL, user_password VARCHAR(255) NOT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649D27A9D4E (user_info_id_id), UNIQUE INDEX UNIQ_8D93D649C86596CF (channel_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_channel (user_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_FAF4904DA76ED395 (user_id), INDEX IDX_FAF4904D72F5A1AA (channel_id), PRIMARY KEY(user_id, channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_info (id INT AUTO_INCREMENT NOT NULL, user_last_name VARCHAR(255) NOT NULL, user_first_name VARCHAR(255) NOT NULL, user_image VARCHAR(255) DEFAULT NULL, user_gender VARCHAR(255) NOT NULL, user_birth_date DATETIME NOT NULL, user_bio VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE podcast ADD CONSTRAINT FK_D7E805BDDC588714 FOREIGN KEY (playlist_id_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE podcast_comment ADD CONSTRAINT FK_3E356566C5B772 FOREIGN KEY (podcast_id_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE podcast_comment ADD CONSTRAINT FK_3E3565669D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE podcast_review ADD CONSTRAINT FK_3238D4A8C5B772 FOREIGN KEY (podcast_id_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE podcast_review ADD CONSTRAINT FK_3238D4A89D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404C5B772 FOREIGN KEY (podcast_id_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064049D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D27A9D4E FOREIGN KEY (user_info_id_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C86596CF FOREIGN KEY (channel_id_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE user_channel ADD CONSTRAINT FK_FAF4904DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_channel ADD CONSTRAINT FK_FAF4904D72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C86596CF');
        $this->addSql('ALTER TABLE user_channel DROP FOREIGN KEY FK_FAF4904D72F5A1AA');
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BDDC588714');
        $this->addSql('ALTER TABLE podcast_comment DROP FOREIGN KEY FK_3E356566C5B772');
        $this->addSql('ALTER TABLE podcast_review DROP FOREIGN KEY FK_3238D4A8C5B772');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404C5B772');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA9D86650F');
        $this->addSql('ALTER TABLE podcast_comment DROP FOREIGN KEY FK_3E3565669D86650F');
        $this->addSql('ALTER TABLE podcast_review DROP FOREIGN KEY FK_3238D4A89D86650F');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064049D86650F');
        $this->addSql('ALTER TABLE user_channel DROP FOREIGN KEY FK_FAF4904DA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D27A9D4E');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE podcast');
        $this->addSql('DROP TABLE podcast_comment');
        $this->addSql('DROP TABLE podcast_review');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_channel');
        $this->addSql('DROP TABLE user_info');
    }
}
