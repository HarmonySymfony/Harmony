<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221225909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD idevent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EDAB66BE FOREIGN KEY (idevent) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_42C84955EDAB66BE ON reservation (idevent)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EDAB66BE');
        $this->addSql('DROP INDEX IDX_42C84955EDAB66BE ON reservation');
        $this->addSql('ALTER TABLE reservation DROP idevent');
    }
}
