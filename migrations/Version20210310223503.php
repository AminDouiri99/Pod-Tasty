<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310223503 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_podcast');
        $this->addSql('ALTER TABLE channel ADD channel_status INT NOT NULL');
        $this->addSql('ALTER TABLE podcast DROP comments_allowed');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_podcast (user_id INT NOT NULL, podcast_id INT NOT NULL, INDEX IDX_8FE51BB7A76ED395 (user_id), INDEX IDX_8FE51BB7786136AB (podcast_id), PRIMARY KEY(user_id, podcast_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE channel DROP channel_status');
        $this->addSql('ALTER TABLE podcast ADD comments_allowed INT NOT NULL');
    }
}
