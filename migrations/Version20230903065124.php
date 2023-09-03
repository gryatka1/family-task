<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230903065124 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id INT NOT NULL, task_group_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, done_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25BE94330B ON task (task_group_id)');
        $this->addSql('COMMENT ON COLUMN task.done_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE task_group (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BE94330B FOREIGN KEY (task_group_id) REFERENCES task_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25BE94330B');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_group');
    }
}
