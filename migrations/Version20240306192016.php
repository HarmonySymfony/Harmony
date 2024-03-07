<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306192016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_event (id INT AUTO_INCREMENT NOT NULL, event_comment_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_92349256A087A43C (event_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, rating_value DOUBLE PRECISION NOT NULL, INDEX IDX_D889262271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_event ADD CONSTRAINT FK_92349256A087A43C FOREIGN KEY (event_comment_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262271F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_event DROP FOREIGN KEY FK_92349256A087A43C');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262271F7E88B');
        $this->addSql('DROP TABLE comment_event');
        $this->addSql('DROP TABLE rating');
    }
}
