<?php

declare(strict_types=1);

namespace PHPapp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210718051524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added list table, changed rel. owner in user -- contact';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
                -- Adminer 4.8.1 MySQL 8.0.25 dump

                SET NAMES utf8;
                SET time_zone = '+00:00';
                SET foreign_key_checks = 0;
                SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

                DROP TABLE IF EXISTS `contacts`;
                CREATE TABLE `contacts` (
                  `id` int NOT NULL AUTO_INCREMENT,
                  `user_id` int DEFAULT NULL,
                  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                  `email` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `UNIQ_33401573E7927C74` (`email`),
                  UNIQUE KEY `UNIQ_33401573A76ED395` (`user_id`),
                  CONSTRAINT `FK_33401573A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

                DROP TABLE IF EXISTS `lists`;
                CREATE TABLE `lists` (
                  `id` int NOT NULL AUTO_INCREMENT,
                  `owner_id` int DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `IDX_8269FA57E3C61F9` (`owner_id`),
                  CONSTRAINT `FK_8269FA57E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;


                DROP TABLE IF EXISTS `users`;
                CREATE TABLE `users` (
                  `id` int NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;


                -- 2021-07-18 05:16:35

                SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<SQL
                DROP TABLE IF EXISTS `lists`;
                DROP TABLE IF EXISTS `users`;
                DROP TABLE IF EXISTS `contacts`;
                SQL;
    }
}
