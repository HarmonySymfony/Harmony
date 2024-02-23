<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223021920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pharmacie ADD id_medicament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacie ADD CONSTRAINT FK_5FC194341525B092 FOREIGN KEY (id_medicament_id) REFERENCES medicament (id)');
        $this->addSql('CREATE INDEX IDX_5FC194341525B092 ON pharmacie (id_medicament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pharmacie DROP FOREIGN KEY FK_5FC194341525B092');
        $this->addSql('DROP INDEX IDX_5FC194341525B092 ON pharmacie');
        $this->addSql('ALTER TABLE pharmacie DROP id_medicament_id');
    }
}
