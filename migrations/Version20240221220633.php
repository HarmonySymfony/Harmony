<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221220633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cabinet ADD rendez_vous_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cabinet ADD CONSTRAINT FK_4CED05B091EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id)');
        $this->addSql('CREATE INDEX IDX_4CED05B091EF7EAA ON cabinet (rendez_vous_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cabinet DROP FOREIGN KEY FK_4CED05B091EF7EAA');
        $this->addSql('DROP INDEX IDX_4CED05B091EF7EAA ON cabinet');
        $this->addSql('ALTER TABLE cabinet DROP rendez_vous_id');
    }
}
