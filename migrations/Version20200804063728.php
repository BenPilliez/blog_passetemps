<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804063728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_tags (post_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_A6E9F32D4B89032C (post_id), INDEX IDX_A6E9F32D8D7B4FB4 (tags_id), PRIMARY KEY(post_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32D4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA21214B7 FOREIGN KEY (categories_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA21214B7 ON post (categories_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA21214B7');
        $this->addSql('ALTER TABLE post_tags DROP FOREIGN KEY FK_A6E9F32D8D7B4FB4');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE post_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA21214B7 ON post');
        $this->addSql('ALTER TABLE post DROP categories_id');
    }
}
