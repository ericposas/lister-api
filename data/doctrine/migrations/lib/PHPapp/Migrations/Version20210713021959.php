<?php

declare(strict_types=1);

namespace PHPapp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713021959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = <<< EOT
        CREATE TABLE example_table (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        )
        EOT;
        $this->addSql($table);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE example_table');
    }
}
