<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529333333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Prize table';
    }

    public function up(Schema $schema): void
    {
        // Create the Prize table
        $this->addSql('CREATE TABLE prize (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            partner_id INT NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT fk_prize_partner FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop the Prize table if the migration is rolled back
        $this->addSql('DROP TABLE prize');
    }
}
