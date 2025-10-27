<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027134304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_stats ADD successful_requests INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user_stats ADD total_response_time INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_stats DROP successful_requests');
        $this->addSql('ALTER TABLE user_stats DROP total_response_time');
    }
}
