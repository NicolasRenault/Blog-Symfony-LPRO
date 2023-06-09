<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112211047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, title, content, created_at, updated_at, author, nb_views, published FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, author VARCHAR(128) NOT NULL COLLATE BINARY, nb_views INTEGER NOT NULL, published BOOLEAN NOT NULL, CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, title, content, created_at, updated_at, author, nb_views, published) SELECT id, title, content, created_at, updated_at, author, nb_views, published FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('DROP INDEX IDX_DE8D79BF7294869C');
        $this->addSql('DROP INDEX IDX_DE8D79BF12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asso_article_categorie AS SELECT article_id, category_id FROM asso_article_categorie');
        $this->addSql('DROP TABLE asso_article_categorie');
        $this->addSql('CREATE TABLE asso_article_categorie (article_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(article_id, category_id), CONSTRAINT FK_DE8D79BF7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DE8D79BF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asso_article_categorie (article_id, category_id) SELECT article_id, category_id FROM __temp__asso_article_categorie');
        $this->addSql('DROP TABLE __temp__asso_article_categorie');
        $this->addSql('CREATE INDEX IDX_DE8D79BF7294869C ON asso_article_categorie (article_id)');
        $this->addSql('CREATE INDEX IDX_DE8D79BF12469DE2 ON asso_article_categorie (category_id)');
        $this->addSql('DROP INDEX IDX_9474526C7294869C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, article_id, title, author, created_at, message FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, author VARCHAR(128) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, message CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, article_id, title, author, created_at, message) SELECT id, article_id, title, author, created_at, message FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C7294869C ON comment (article_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, title, content, created_at, updated_at, author, nb_views, published FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, author VARCHAR(128) NOT NULL, nb_views INTEGER NOT NULL, published BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO article (id, title, content, created_at, updated_at, author, nb_views, published) SELECT id, title, content, created_at, updated_at, author, nb_views, published FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('DROP INDEX IDX_DE8D79BF7294869C');
        $this->addSql('DROP INDEX IDX_DE8D79BF12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asso_article_categorie AS SELECT article_id, category_id FROM asso_article_categorie');
        $this->addSql('DROP TABLE asso_article_categorie');
        $this->addSql('CREATE TABLE asso_article_categorie (article_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(article_id, category_id))');
        $this->addSql('INSERT INTO asso_article_categorie (article_id, category_id) SELECT article_id, category_id FROM __temp__asso_article_categorie');
        $this->addSql('DROP TABLE __temp__asso_article_categorie');
        $this->addSql('CREATE INDEX IDX_DE8D79BF7294869C ON asso_article_categorie (article_id)');
        $this->addSql('CREATE INDEX IDX_DE8D79BF12469DE2 ON asso_article_categorie (category_id)');
        $this->addSql('DROP INDEX IDX_9474526C7294869C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, article_id, title, author, created_at, message FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, message CLOB NOT NULL)');
        $this->addSql('INSERT INTO comment (id, article_id, title, author, created_at, message) SELECT id, article_id, title, author, created_at, message FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C7294869C ON comment (article_id)');
    }
}
