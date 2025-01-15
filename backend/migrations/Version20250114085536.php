<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114085536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, body TEXT NOT NULL, image TEXT DEFAULT NULL, created_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 ON post (slug)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('COMMENT ON COLUMN post.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(150) NOT NULL, roles JSON NOT NULL, slug VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATE NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DA76ED395');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE "user"');
    }
}
