<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811082405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, rates INT DEFAULT NULL, nb_rates INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD rates_id INT DEFAULT NULL, DROP rating, DROP nb_rate');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D877DAA6F FOREIGN KEY (rates_id) REFERENCES rating (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D877DAA6F ON post (rates_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D877DAA6F');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D877DAA6F ON post');
        $this->addSql('ALTER TABLE post ADD rating INT NOT NULL, ADD nb_rate INT NOT NULL, DROP rates_id');
    }
}
