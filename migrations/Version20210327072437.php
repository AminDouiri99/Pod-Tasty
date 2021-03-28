<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210327072437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8FE51BB7A76ED395 ON user_podcast (user_id)');
        $this->addSql('DROP INDEX podcast_id ON user_podcast');
        $this->addSql('CREATE INDEX IDX_8FE51BB7786136AB ON user_podcast (podcast_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_podcast DROP FOREIGN KEY FK_8FE51BB7A76ED395');
        $this->addSql('ALTER TABLE user_podcast DROP FOREIGN KEY FK_8FE51BB7786136AB');
        $this->addSql('DROP INDEX IDX_8FE51BB7A76ED395 ON user_podcast');
        $this->addSql('ALTER TABLE user_podcast DROP FOREIGN KEY FK_8FE51BB7786136AB');
        $this->addSql('DROP INDEX idx_8fe51bb7786136ab ON user_podcast');
        $this->addSql('CREATE INDEX podcast_id ON user_podcast (podcast_id)');
        $this->addSql('ALTER TABLE user_podcast ADD CONSTRAINT FK_8FE51BB7786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE');
    }
}
