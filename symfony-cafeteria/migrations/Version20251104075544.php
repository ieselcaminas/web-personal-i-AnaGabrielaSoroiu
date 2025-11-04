<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104075544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bebida ADD cafeteria_id INT NOT NULL, ADD alergenos VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE bebida ADD CONSTRAINT FK_4821C78546884829 FOREIGN KEY (cafeteria_id) REFERENCES cafeteria (id)');
        $this->addSql('CREATE INDEX IDX_4821C78546884829 ON bebida (cafeteria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bebida DROP FOREIGN KEY FK_4821C78546884829');
        $this->addSql('DROP INDEX IDX_4821C78546884829 ON bebida');
        $this->addSql('ALTER TABLE bebida DROP cafeteria_id, DROP alergenos');
    }
}
