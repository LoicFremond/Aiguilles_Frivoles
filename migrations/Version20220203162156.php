<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203162156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E966BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_DB021E966BF700BD ON messages (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E966BF700BD');
        $this->addSql('DROP INDEX IDX_DB021E966BF700BD ON messages');
        $this->addSql('ALTER TABLE messages DROP status_id');
    }
}
