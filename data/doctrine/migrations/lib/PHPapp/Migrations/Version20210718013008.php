<?php

declare(strict_types=1);

namespace PHPapp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210718013008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $tables = <<< SQL
                SET NAMES utf8;
                SET time_zone = '+00:00';
                SET foreign_key_checks = 0;
                SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

                DROP TABLE IF EXISTS `example_table`;
                DROP TABLE IF EXISTS `contacts`;

                DROP TABLE IF EXISTS `contact`;
                CREATE TABLE `contact` (
                  `id` int NOT NULL AUTO_INCREMENT,
                  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                  `email` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `UNIQ_4C62E638E7927C74` (`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
                
                DROP TABLE IF EXISTS `users`;
                CREATE TABLE `users` (
                  `id` int NOT NULL AUTO_INCREMENT,
                  `contact_id` int DEFAULT NULL,
                  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `UNIQ_1483A5E9E7A1254A` (`contact_id`),
                  CONSTRAINT `FK_1483A5E9E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
                SQL;
        $this->addSql($tables);
    }

    public function down(Schema $schema): void
    {
        $sql = <<< SQL
                DROP TABLE IF EXISTS `contact`;
                DROP TABLE IF EXISTS `contacts`;
                DROP TABLE IF EXISTS `users`;
                DROP TABLE IF EXISTS `example_table`;
                SQL;
        $this->addSql($sql);
    }
}
