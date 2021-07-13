<?php

declare(strict_types=1);

namespace PHPapp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713020053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'a test migration';
    }

    // public function preUp(Schema $schema): void
    // {

    // }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->warnIf(something === someCondition, 'something happened'); 
        // this above warns you if a condition is met
        // available methods:
        // $this->abortIf()
        // $this->skipIf()
        // $this->addSql()
        /**
         * You can use the addSql method within the up and down methods.
         * Internally the addSql calls are passed to the executeQuery
         * method in the DBAL. This means that you can use the power of
         * prepared statements easily and that you don't need to copy
         * paste the same query with different parameters. You can just
         * pass those different parameters to the addSql method
         * as parameters.
         * 
         * Example:
         * public function up(Schema $schema) : void
        *{
        *    $users = [
        *        ['name' => 'mike', 'id' => 1],
        *        ['name' => 'jwage', 'id' => 2],
        *        ['name' => 'ocramius', 'id' => 3],
        *    ];
        *    
        *    foreach ($users as $user) {
        *        $this->addSql('UPDATE user SET happy = true WHERE name = :name AND id = :id', $user);
        *    }
        *}
         */
        // $this->write()
        
    }

    // public function postUp(Schema $schema): void
    // {
        // $this->throwIrreversibleMigrationException()

    // }

    // preDown() and postDown() also exist

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
