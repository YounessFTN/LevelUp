<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126204136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE challenge_user (challenge_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY (challenge_id, user_id))');
        $this->addSql('CREATE INDEX IDX_843CD1CF98A21AC6 ON challenge_user (challenge_id)');
        $this->addSql('CREATE INDEX IDX_843CD1CFA76ED395 ON challenge_user (user_id)');
        $this->addSql('CREATE TABLE challenge_quote (challenge_id INT NOT NULL, quote_id INT NOT NULL, PRIMARY KEY (challenge_id, quote_id))');
        $this->addSql('CREATE INDEX IDX_6FA342E698A21AC6 ON challenge_quote (challenge_id)');
        $this->addSql('CREATE INDEX IDX_6FA342E6DB805178 ON challenge_quote (quote_id)');
        $this->addSql('ALTER TABLE challenge_user ADD CONSTRAINT FK_843CD1CF98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_user ADD CONSTRAINT FK_843CD1CFA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_quote ADD CONSTRAINT FK_6FA342E698A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_quote ADD CONSTRAINT FK_6FA342E6DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge ADD proposer_id INT NOT NULL');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951B13FA634 FOREIGN KEY (proposer_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D70989512B36786B ON challenge (title)');
        $this->addSql('CREATE INDEX IDX_D7098951B13FA634 ON challenge (proposer_id)');
        $this->addSql('CREATE UNIQUE INDEX comments_user_feedback_date_idx ON comment (author_id, feedback_id, created_at)');
        $this->addSql('CREATE UNIQUE INDEX feedbacks_user_challenge_date_idx ON feedback (author_id, challenge_id, created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4CE53EB1342561 ON fund_usage (usage_date)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B71CBF4A96CF84D ON quote (quote_text)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge_user DROP CONSTRAINT FK_843CD1CF98A21AC6');
        $this->addSql('ALTER TABLE challenge_user DROP CONSTRAINT FK_843CD1CFA76ED395');
        $this->addSql('ALTER TABLE challenge_quote DROP CONSTRAINT FK_6FA342E698A21AC6');
        $this->addSql('ALTER TABLE challenge_quote DROP CONSTRAINT FK_6FA342E6DB805178');
        $this->addSql('DROP TABLE challenge_user');
        $this->addSql('DROP TABLE challenge_quote');
        $this->addSql('ALTER TABLE challenge DROP CONSTRAINT FK_D7098951B13FA634');
        $this->addSql('DROP INDEX UNIQ_D70989512B36786B');
        $this->addSql('DROP INDEX IDX_D7098951B13FA634');
        $this->addSql('ALTER TABLE challenge DROP proposer_id');
        $this->addSql('DROP INDEX comments_user_feedback_date_idx');
        $this->addSql('DROP INDEX feedbacks_user_challenge_date_idx');
        $this->addSql('DROP INDEX UNIQ_D4CE53EB1342561');
        $this->addSql('DROP INDEX UNIQ_6B71CBF4A96CF84D');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
    }
}
