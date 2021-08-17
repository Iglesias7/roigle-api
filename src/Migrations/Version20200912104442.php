<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200912104442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mark VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, number VARCHAR(3) NOT NULL, type VARCHAR(255) NOT NULL, face_up TINYINT(1) NOT NULL, INDEX IDX_161498D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, user_id INT NOT NULL, body VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, solde DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_CFF65260A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE immobilier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', price DOUBLE PRECISION NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, joueur1_id INT NOT NULL, joueur2_id INT NOT NULL, milieux_id INT DEFAULT NULL, bank_id INT DEFAULT NULL, mise INT NOT NULL, UNIQUE INDEX UNIQ_7A5BC50592C1E237 (joueur1_id), UNIQUE INDEX UNIQ_7A5BC50580744DD9 (joueur2_id), UNIQUE INDEX UNIQ_7A5BC505F0B9098C (milieux_id), UNIQUE INDEX UNIQ_7A5BC50511C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, body LONGTEXT NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pret (id INT AUTO_INCREMENT NOT NULL, demandeur_id INT DEFAULT NULL, donneur_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, delai DATETIME NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_52ECE97995A6EE59 (demandeur_id), INDEX IDX_52ECE9799789825B (donneur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, demandeur_id INT DEFAULT NULL, donneur_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, message VARCHAR(255) NOT NULL, time DATETIME NOT NULL, action LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_723705D1F2C56620 (compte_id), INDEX IDX_723705D195A6EE59 (demandeur_id), INDEX IDX_723705D19789825B (donneur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, is_open TINYINT(1) NOT NULL, reputation INT NOT NULL, quality_of_life VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_admin TINYINT(1) NOT NULL, nb_cards INT DEFAULT NULL, a_moi TINYINT(1) NOT NULL, max_mise INT DEFAULT NULL, nb_trophet INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_car (user_id INT NOT NULL, car_id INT NOT NULL, INDEX IDX_9C2B8716A76ED395 (user_id), INDEX IDX_9C2B8716C3C6F69F (car_id), PRIMARY KEY(user_id, car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_immobilier (user_id INT NOT NULL, immobilier_id INT NOT NULL, INDEX IDX_B34AE23EA76ED395 (user_id), INDEX IDX_B34AE23E5C7B99A9 (immobilier_id), PRIMARY KEY(user_id, immobilier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE send_user (user_id INT NOT NULL, send_id INT NOT NULL, INDEX IDX_E9546EDAA76ED395 (user_id), INDEX IDX_E9546EDA13933E7B (send_id), PRIMARY KEY(user_id, send_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE received_user (user_id INT NOT NULL, received_id INT NOT NULL, INDEX IDX_5D557BF1A76ED395 (user_id), INDEX IDX_5D557BF1B821E5F5 (received_id), PRIMARY KEY(user_id, received_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prets (user_id INT NOT NULL, pret_id INT NOT NULL, INDEX IDX_3285EA7AA76ED395 (user_id), INDEX IDX_3285EA7A1B61704B (pret_id), PRIMARY KEY(user_id, pret_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunts (user_id INT NOT NULL, emprunts_id INT NOT NULL, INDEX IDX_38FC80DA76ED395 (user_id), INDEX IDX_38FC80D10BD9597 (emprunts_id), PRIMARY KEY(user_id, emprunts_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pretsSend (user_id INT NOT NULL, pretsSend_id INT NOT NULL, INDEX IDX_2995BDF3A76ED395 (user_id), INDEX IDX_2995BDF3CDBFAE (pretsSend_id), PRIMARY KEY(user_id, pretsSend_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empruntsSend (user_id INT NOT NULL, empruntsSend_id INT NOT NULL, INDEX IDX_4F855FE8A76ED395 (user_id), INDEX IDX_4F855FE861417253 (empruntsSend_id), PRIMARY KEY(user_id, empruntsSend_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (post_id INT NOT NULL, user_id INT NOT NULL, updown INT NOT NULL, INDEX IDX_5A1085644B89032C (post_id), INDEX IDX_5A108564A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50592C1E237 FOREIGN KEY (joueur1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50580744DD9 FOREIGN KEY (joueur2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505F0B9098C FOREIGN KEY (milieux_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50511C8FB41 FOREIGN KEY (bank_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D727ACA70 FOREIGN KEY (parent_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97995A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE9799789825B FOREIGN KEY (donneur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D195A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19789825B FOREIGN KEY (donneur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B8716A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B8716C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_immobilier ADD CONSTRAINT FK_B34AE23EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_immobilier ADD CONSTRAINT FK_B34AE23E5C7B99A9 FOREIGN KEY (immobilier_id) REFERENCES immobilier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE send_user ADD CONSTRAINT FK_E9546EDAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE send_user ADD CONSTRAINT FK_E9546EDA13933E7B FOREIGN KEY (send_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE received_user ADD CONSTRAINT FK_5D557BF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE received_user ADD CONSTRAINT FK_5D557BF1B821E5F5 FOREIGN KEY (received_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prets ADD CONSTRAINT FK_3285EA7AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prets ADD CONSTRAINT FK_3285EA7A1B61704B FOREIGN KEY (pret_id) REFERENCES pret (id)');
        $this->addSql('ALTER TABLE emprunts ADD CONSTRAINT FK_38FC80DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE emprunts ADD CONSTRAINT FK_38FC80D10BD9597 FOREIGN KEY (emprunts_id) REFERENCES pret (id)');
        $this->addSql('ALTER TABLE pretsSend ADD CONSTRAINT FK_2995BDF3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pretsSend ADD CONSTRAINT FK_2995BDF3CDBFAE FOREIGN KEY (pretsSend_id) REFERENCES pret (id)');
        $this->addSql('ALTER TABLE empruntsSend ADD CONSTRAINT FK_4F855FE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE empruntsSend ADD CONSTRAINT FK_4F855FE861417253 FOREIGN KEY (empruntsSend_id) REFERENCES pret (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085644B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B8716C3C6F69F');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC505F0B9098C');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50511C8FB41');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F2C56620');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F2C56620');
        $this->addSql('ALTER TABLE user_immobilier DROP FOREIGN KEY FK_B34AE23E5C7B99A9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D727ACA70');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085644B89032C');
        $this->addSql('ALTER TABLE prets DROP FOREIGN KEY FK_3285EA7A1B61704B');
        $this->addSql('ALTER TABLE emprunts DROP FOREIGN KEY FK_38FC80D10BD9597');
        $this->addSql('ALTER TABLE pretsSend DROP FOREIGN KEY FK_2995BDF3CDBFAE');
        $this->addSql('ALTER TABLE empruntsSend DROP FOREIGN KEY FK_4F855FE861417253');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3A76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260A76ED395');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50592C1E237');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50580744DD9');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97995A6EE59');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE9799789825B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D195A6EE59');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D19789825B');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B8716A76ED395');
        $this->addSql('ALTER TABLE user_immobilier DROP FOREIGN KEY FK_B34AE23EA76ED395');
        $this->addSql('ALTER TABLE send_user DROP FOREIGN KEY FK_E9546EDAA76ED395');
        $this->addSql('ALTER TABLE send_user DROP FOREIGN KEY FK_E9546EDA13933E7B');
        $this->addSql('ALTER TABLE received_user DROP FOREIGN KEY FK_5D557BF1A76ED395');
        $this->addSql('ALTER TABLE received_user DROP FOREIGN KEY FK_5D557BF1B821E5F5');
        $this->addSql('ALTER TABLE prets DROP FOREIGN KEY FK_3285EA7AA76ED395');
        $this->addSql('ALTER TABLE emprunts DROP FOREIGN KEY FK_38FC80DA76ED395');
        $this->addSql('ALTER TABLE pretsSend DROP FOREIGN KEY FK_2995BDF3A76ED395');
        $this->addSql('ALTER TABLE empruntsSend DROP FOREIGN KEY FK_4F855FE8A76ED395');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A76ED395');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE immobilier');
        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE pret');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE user_car');
        $this->addSql('DROP TABLE user_immobilier');
        $this->addSql('DROP TABLE send_user');
        $this->addSql('DROP TABLE received_user');
        $this->addSql('DROP TABLE prets');
        $this->addSql('DROP TABLE emprunts');
        $this->addSql('DROP TABLE pretsSend');
        $this->addSql('DROP TABLE empruntsSend');
        $this->addSql('DROP TABLE vote');
    }
}
