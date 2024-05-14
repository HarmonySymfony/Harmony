<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513113837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_event (id INT AUTO_INCREMENT NOT NULL, event_comment_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_92349256A087A43C (event_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament_pharmacie (medicament_id INT NOT NULL, pharmacie_id INT NOT NULL, INDEX IDX_804E4447AB0D61F7 (medicament_id), INDEX IDX_804E4447BC6D351B (pharmacie_id), PRIMARY KEY(medicament_id, pharmacie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, rating_value DOUBLE PRECISION NOT NULL, INDEX IDX_D889262271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_event ADD CONSTRAINT FK_92349256A087A43C FOREIGN KEY (event_comment_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE medicament_pharmacie ADD CONSTRAINT FK_804E4447AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medicament_pharmacie ADD CONSTRAINT FK_804E4447BC6D351B FOREIGN KEY (pharmacie_id) REFERENCES pharmacie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262271F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE cabinet ADD CONSTRAINT FK_4CED05B091EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE evenement ADD date_event DATE DEFAULT NULL, DROP Date, CHANGE image image VARCHAR(255) NOT NULL, CHANGE placeDispo place_dispo INT NOT NULL');
        $this->addSql('ALTER TABLE pharmacie ADD id_medicament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacie ADD CONSTRAINT FK_5FC194341525B092 FOREIGN KEY (id_medicament_id) REFERENCES medicament (id)');
        $this->addSql('CREATE INDEX IDX_5FC194341525B092 ON pharmacie (id_medicament_id)');
        $this->addSql('ALTER TABLE posts DROP liked_by, DROP disliked_by, CHANGE contenu contenu LONGTEXT NOT NULL, CHANGE posted_as posted_as VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('DROP INDEX event_id ON reservation');
        $this->addSql('ALTER TABLE reservation ADD approuve TINYINT(1) NOT NULL, CHANGE event_id idevent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EDAB66BE FOREIGN KEY (idevent) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_42C84955EDAB66BE ON reservation (idevent)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE email email VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(20) NOT NULL, CHANGE prenom prenom VARCHAR(20) DEFAULT NULL, CHANGE role role VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_event DROP FOREIGN KEY FK_92349256A087A43C');
        $this->addSql('ALTER TABLE medicament_pharmacie DROP FOREIGN KEY FK_804E4447AB0D61F7');
        $this->addSql('ALTER TABLE medicament_pharmacie DROP FOREIGN KEY FK_804E4447BC6D351B');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262271F7E88B');
        $this->addSql('DROP TABLE comment_event');
        $this->addSql('DROP TABLE medicament_pharmacie');
        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE cabinet DROP FOREIGN KEY FK_4CED05B091EF7EAA');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AFB88E14F');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD5E258C5');
        $this->addSql('ALTER TABLE evenement ADD Date DATE DEFAULT \'1000-01-01\' NOT NULL, DROP date_event, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE place_dispo placeDispo INT NOT NULL');
        $this->addSql('ALTER TABLE pharmacie DROP FOREIGN KEY FK_5FC194341525B092');
        $this->addSql('DROP INDEX IDX_5FC194341525B092 ON pharmacie');
        $this->addSql('ALTER TABLE pharmacie DROP id_medicament_id');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAFB88E14F');
        $this->addSql('ALTER TABLE posts ADD liked_by JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD disliked_by JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE contenu contenu LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE posted_as posted_as VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EDAB66BE');
        $this->addSql('DROP INDEX IDX_42C84955EDAB66BE ON reservation');
        $this->addSql('ALTER TABLE reservation DROP approuve, CHANGE idevent event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX event_id ON reservation (event_id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE utilisateur CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(20) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE role role VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
