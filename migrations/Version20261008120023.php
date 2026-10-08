<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261008120023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_following (follower_id INTEGER NOT NULL, followed_id INTEGER NOT NULL, PRIMARY KEY (follower_id, followed_id), CONSTRAINT FK_715F0007AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_715F0007D956F010 FOREIGN KEY (followed_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_715F0007AC24F853 ON user_following (follower_id)');
        $this->addSql('CREATE INDEX IDX_715F0007D956F010 ON user_following (followed_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_following');
    }
}
