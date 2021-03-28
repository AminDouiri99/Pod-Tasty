<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210326213856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_info_user_info (user_info_source INT NOT NULL, user_info_target INT NOT NULL, INDEX IDX_77FD1FFA3B4CE9B0 (user_info_source), INDEX IDX_77FD1FFA22A9B93F (user_info_target), PRIMARY KEY(user_info_source, user_info_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_info_user_info ADD CONSTRAINT FK_77FD1FFA3B4CE9B0 FOREIGN KEY (user_info_source) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_info_user_info ADD CONSTRAINT FK_77FD1FFA22A9B93F FOREIGN KEY (user_info_target) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP roles');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_info_user_info');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL');
    }
}
