<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307195117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist ADD channel_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112DC86596CF FOREIGN KEY (channel_id_id) REFERENCES channel (id)');
        $this->addSql('CREATE INDEX IDX_D782112DC86596CF ON playlist (channel_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112DC86596CF');
        $this->addSql('DROP INDEX IDX_D782112DC86596CF ON playlist');
        $this->addSql('ALTER TABLE playlist DROP channel_id_id');
    }
}
