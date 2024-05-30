<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529222222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Partner table';
    }

    public function up(Schema $schema): void
    {
        // Create the Partner table
        $this->addSql('CREATE TABLE partner (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            language VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop the Partner table if the migration is rolled back
        $this->addSql('DROP TABLE partner');
    }
}
