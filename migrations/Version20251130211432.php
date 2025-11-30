<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251130211432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge_quote DROP CONSTRAINT fk_6fa342e698a21ac6');
        $this->addSql('ALTER TABLE challenge_quote DROP CONSTRAINT fk_6fa342e6db805178');
        $this->addSql('DROP TABLE challenge_quote');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE challenge_quote (challenge_id INT NOT NULL, quote_id INT NOT NULL, PRIMARY KEY (challenge_id, quote_id))');
        $this->addSql('CREATE INDEX idx_6fa342e698a21ac6 ON challenge_quote (challenge_id)');
        $this->addSql('CREATE INDEX idx_6fa342e6db805178 ON challenge_quote (quote_id)');
        $this->addSql('ALTER TABLE challenge_quote ADD CONSTRAINT fk_6fa342e698a21ac6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE challenge_quote ADD CONSTRAINT fk_6fa342e6db805178 FOREIGN KEY (quote_id) REFERENCES quote (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
