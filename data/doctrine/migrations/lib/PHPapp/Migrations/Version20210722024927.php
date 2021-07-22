<?php

declare(strict_types=1);

namespace PHPapp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722024927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
                <<<SQL
                
                SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                DROP TABLE IF EXISTS `contact`;
                DROP TABLE IF EXISTS `items`;
                DROP TABLE IF EXISTS `lists`;
                DROP TABLE IF EXISTS `profiles`;
                DROP TABLE IF EXISTS `users`;
            SQL);
    }
}
