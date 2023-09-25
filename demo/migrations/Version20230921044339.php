<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230921044339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, director VARCHAR(255) NOT NULL, budget NUMERIC(10, 2) NOT NULL, crew VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, category LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, rating INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_D88926228F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rating_id INT NOT NULL, INDEX IDX_BDDB3D1FA76ED395 (user_id), INDEX IDX_BDDB3D1FA32EFC6 (rating_id), PRIMARY KEY(user_id, rating_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926228F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1FA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926228F93B6FC');
        $this->addSql('ALTER TABLE user_rating DROP FOREIGN KEY FK_BDDB3D1FA76ED395');
        $this->addSql('ALTER TABLE user_rating DROP FOREIGN KEY FK_BDDB3D1FA32EFC6');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_rating');
    }
}
