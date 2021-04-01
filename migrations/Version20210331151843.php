<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331151843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, post_image VARCHAR(255) DEFAULT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, story_image VARCHAR(255) NOT NULL, INDEX IDX_EB5604387E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story_user_info (story_id INT NOT NULL, user_info_id INT NOT NULL, INDEX IDX_48B247EFAA5D4036 (story_id), INDEX IDX_48B247EF586DFF2 (user_info_id), PRIMARY KEY(story_id, user_info_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tag_style VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_podcast (tag_id INT NOT NULL, podcast_id INT NOT NULL, INDEX IDX_E5D92817BAD26311 (tag_id), INDEX IDX_E5D92817786136AB (podcast_id), PRIMARY KEY(tag_id, podcast_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_podcast (user_id INT NOT NULL, podcast_id INT NOT NULL, INDEX IDX_8FE51BB7A76ED395 (user_id), INDEX IDX_8FE51BB7786136AB (podcast_id), PRIMARY KEY(user_id, podcast_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_info_user_info (user_info_source INT NOT NULL, user_info_target INT NOT NULL, INDEX IDX_77FD1FFA3B4CE9B0 (user_info_source), INDEX IDX_77FD1FFA22A9B93F (user_info_target), PRIMARY KEY(user_info_source, user_info_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB5604387E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE story_user_info ADD CONSTRAINT FK_48B247EFAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_user_info ADD CONSTRAINT FK_48B247EF586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_podcast ADD CONSTRAINT FK_E5D92817BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_podcast ADD CONSTRAINT FK_E5D92817786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_info_user_info ADD CONSTRAINT FK_77FD1FFA3B4CE9B0 FOREIGN KEY (user_info_source) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_info_user_info ADD CONSTRAINT FK_77FD1FFA22A9B93F FOREIGN KEY (user_info_target) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE podcast ADD currently_live INT NOT NULL, ADD comments_allowed INT NOT NULL, ADD currently_watching INT DEFAULT NULL, CHANGE podcast_source podcast_source VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD desactive_account TINYINT(1) NOT NULL, ADD github_id VARCHAR(255) DEFAULT NULL, CHANGE user_password user_password VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE story_user_info DROP FOREIGN KEY FK_48B247EFAA5D4036');
        $this->addSql('ALTER TABLE tag_podcast DROP FOREIGN KEY FK_E5D92817BAD26311');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE story_user_info');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_podcast');
        $this->addSql('DROP TABLE user_podcast');
        $this->addSql('DROP TABLE user_info_user_info');
        $this->addSql('ALTER TABLE podcast DROP currently_live, DROP comments_allowed, DROP currently_watching, CHANGE podcast_source podcast_source VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP desactive_account, DROP github_id, CHANGE user_password user_password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
