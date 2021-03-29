<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329181532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_user (tag_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_639C69FFBAD26311 (tag_id), INDEX IDX_639C69FFA76ED395 (user_id), PRIMARY KEY(tag_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_user ADD CONSTRAINT FK_639C69FFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_user ADD CONSTRAINT FK_639C69FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_podcast DROP FOREIGN KEY FK_8FE51BB7786136AB');
        $this->addSql('DROP INDEX fk_8fe51bb7786136ab ON user_podcast');
        $this->addSql('CREATE INDEX IDX_8FE51BB7786136AB ON user_podcast (podcast_id)');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_user DROP FOREIGN KEY FK_639C69FFBAD26311');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_user');
        $this->addSql('ALTER TABLE user_podcast DROP FOREIGN KEY FK_8FE51BB7786136AB');
        $this->addSql('DROP INDEX idx_8fe51bb7786136ab ON user_podcast');
        $this->addSql('CREATE INDEX FK_8FE51BB7786136AB ON user_podcast (podcast_id)');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
    }
}
