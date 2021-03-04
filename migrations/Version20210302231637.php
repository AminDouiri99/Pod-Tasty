<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302231637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podcast CHANGE podcast_image podcast_image VARCHAR(255) DEFAULT NULL, CHANGE podcast_source podcast_source VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE user_info_id_id user_info_id_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podcast CHANGE podcast_image podcast_image LONGBLOB DEFAULT NULL, CHANGE podcast_source podcast_source LONGBLOB NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE user_info_id_id user_info_id_id INT NOT NULL');
    }
}
