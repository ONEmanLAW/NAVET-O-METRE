<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261008112155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actor ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE actor ADD COLUMN updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD COLUMN updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD COLUMN updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE rating ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE rating ADD COLUMN updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__actor AS SELECT id, name FROM actor');
        $this->addSql('DROP TABLE actor');
        $this->addSql('CREATE TABLE actor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO actor (id, name) SELECT id, name FROM __temp__actor');
        $this->addSql('DROP TABLE __temp__actor');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, title FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO category (id, title) SELECT id, title FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, description, release_date FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, release_date INTEGER NOT NULL)');
        $this->addSql('INSERT INTO movie (id, title, description, release_date) SELECT id, title, description, release_date FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE TEMPORARY TABLE __temp__rating AS SELECT id, score, rated_at, user_id, movie_id FROM rating');
        $this->addSql('DROP TABLE rating');
        $this->addSql('CREATE TABLE rating (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, score SMALLINT NOT NULL, rated_at DATETIME NOT NULL, user_id INTEGER NOT NULL, movie_id INTEGER NOT NULL, CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D88926228F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO rating (id, score, rated_at, user_id, movie_id) SELECT id, score, rated_at, user_id, movie_id FROM __temp__rating');
        $this->addSql('DROP TABLE __temp__rating');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_RATING_USER_MOVIE ON rating (user_id, movie_id)');
        $this->addSql('CREATE INDEX IDX_D8892622A76ED395 ON rating (user_id)');
        $this->addSql('CREATE INDEX IDX_D88926228F93B6FC ON rating (movie_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
}
