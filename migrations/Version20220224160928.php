<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224160928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clientdebug (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, cin INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, telephone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E01CF4DEE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE admin');
        $this->addSql('ALTER TABLE adresse ADD id_client_debug_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816644AF351 FOREIGN KEY (id_client_debug_id) REFERENCES clientdebug (id)');
        $this->addSql('CREATE INDEX IDX_C35F0816644AF351 ON adresse (id_client_debug_id)');
        $this->addSql('ALTER TABLE commande ADD id_client_debug_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D644AF351 FOREIGN KEY (id_client_debug_id) REFERENCES clientdebug (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D644AF351 ON commande (id_client_debug_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27BCF5E72D, ADD INDEX IDX_29A5EC27BCF5E72D (categorie_id)');
        $this->addSql('ALTER TABLE produit ADD prix NUMERIC(5, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816644AF351');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D644AF351');
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_880E0D76F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE clientdebug');
        $this->addSql('DROP INDEX IDX_C35F0816644AF351 ON adresse');
        $this->addSql('ALTER TABLE adresse DROP id_client_debug_id, CHANGE code_postale code_postale VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categorie CHANGE nom nom VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE client CHANGE nom nom VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_6EEAA67D644AF351 ON commande');
        $this->addSql('ALTER TABLE commande DROP id_client_debug_id');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27BCF5E72D, ADD UNIQUE INDEX UNIQ_29A5EC27BCF5E72D (categorie_id)');
        $this->addSql('ALTER TABLE produit DROP prix, CHANGE nom nom VARCHAR(200) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE desc_court desc_court VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE desc_long desc_long VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
