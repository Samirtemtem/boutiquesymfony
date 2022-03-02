<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301102749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, id_client_debug_id INT DEFAULT NULL, code_postale VARCHAR(10) NOT NULL, par_defaut TINYINT(1) NOT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_C35F0816644AF351 (id_client_debug_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, nom_image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientdebug (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, cin INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, telephone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E01CF4DEE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_client_debug_id INT DEFAULT NULL, date DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_6EEAA67D644AF351 (id_client_debug_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(200) NOT NULL, desc_court VARCHAR(100) NOT NULL, desc_long VARCHAR(255) NOT NULL, prix NUMERIC(5, 2) NOT NULL, nom_image VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_commande (id INT AUTO_INCREMENT NOT NULL, id_commande_id INT NOT NULL, id_produit_id INT NOT NULL, qte_commande INT NOT NULL, prix_unitaire NUMERIC(10, 3) NOT NULL, prix_totale NUMERIC(10, 3) NOT NULL, INDEX IDX_47F5946E9AF8E3A3 (id_commande_id), INDEX IDX_47F5946EAABEFE2C (id_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816644AF351 FOREIGN KEY (id_client_debug_id) REFERENCES clientdebug (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D644AF351 FOREIGN KEY (id_client_debug_id) REFERENCES clientdebug (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit_commande ADD CONSTRAINT FK_47F5946E9AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE produit_commande ADD CONSTRAINT FK_47F5946EAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816644AF351');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D644AF351');
        $this->addSql('ALTER TABLE produit_commande DROP FOREIGN KEY FK_47F5946E9AF8E3A3');
        $this->addSql('ALTER TABLE produit_commande DROP FOREIGN KEY FK_47F5946EAABEFE2C');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE clientdebug');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_commande');
    }
}
