<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261007123719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE actor_movie (actor_id INTEGER NOT NULL, movie_id INTEGER NOT NULL, PRIMARY KEY (actor_id, movie_id), CONSTRAINT FK_39DA19FB10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_39DA19FB8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_39DA19FB10DAF24A ON actor_movie (actor_id)');
        $this->addSql('CREATE INDEX IDX_39DA19FB8F93B6FC ON actor_movie (movie_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, description, release_date FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, release_date INTEGER NOT NULL)');
        $this->addSql('INSERT INTO movie (id, title, description, release_date) SELECT id, title, description, release_date FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE actor_movie');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, description, release_date FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, release_date INTEGER NOT NULL)');
        $this->addSql('INSERT INTO movie (id, title, description, release_date) SELECT id, title, description, release_date FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }
}
