<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207205010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ArticleArtiste (id_article INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom_a VARCHAR(255) NOT NULL, description_a TINYTEXT NOT NULL, views INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image_h VARCHAR(255) DEFAULT NULL, INDEX IDX_D2A0408A76ED395 (user_id), PRIMARY KEY(id_article)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, nomev VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, datedeb VARCHAR(255) NOT NULL, datefin VARCHAR(255) NOT NULL, imagename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eventsponsor (id INT NOT NULL, id_sponsor INT NOT NULL, INDEX IDX_4A265905BF396750 (id), INDEX IDX_4A2659055F1160A4 (id_sponsor), PRIMARY KEY(id, id_sponsor)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messagerie (id INT AUTO_INCREMENT NOT NULL, nomrex VARCHAR(255) NOT NULL, nomex VARCHAR(255) NOT NULL, msg TINYTEXT NOT NULL, vu TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (idrec INT AUTO_INCREMENT NOT NULL, message TINYTEXT NOT NULL, object VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(idrec)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id_sponsor INT AUTO_INCREMENT NOT NULL, phone_societe INT NOT NULL, montant INT NOT NULL, nom_societe VARCHAR(255) NOT NULL, email_societe VARCHAR(255) NOT NULL, PRIMARY KEY(id_sponsor)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ArticleArtiste ADD CONSTRAINT FK_D2A0408A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE eventsponsor ADD CONSTRAINT FK_4A265905BF396750 FOREIGN KEY (id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE eventsponsor ADD CONSTRAINT FK_4A2659055F1160A4 FOREIGN KEY (id_sponsor) REFERENCES sponsor (id_sponsor)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ArticleArtiste DROP FOREIGN KEY FK_D2A0408A76ED395');
        $this->addSql('ALTER TABLE eventsponsor DROP FOREIGN KEY FK_4A265905BF396750');
        $this->addSql('ALTER TABLE eventsponsor DROP FOREIGN KEY FK_4A2659055F1160A4');
        $this->addSql('DROP TABLE ArticleArtiste');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE eventsponsor');
        $this->addSql('DROP TABLE messagerie');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
