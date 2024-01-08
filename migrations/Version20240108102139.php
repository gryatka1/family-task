<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240108102139 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "task" (id SERIAL NOT NULL, task_group_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, doneAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25BE94330B ON "task" (task_group_id)');
        $this->addSql('CREATE INDEX task_id_idx ON "task" (id)');
        $this->addSql('COMMENT ON COLUMN "task".doneAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "task".deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "task".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "task_group" (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task_group_id_idx ON "task_group" (id)');
        $this->addSql('COMMENT ON COLUMN "task_group".deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "task_group".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "task" ADD CONSTRAINT FK_527EDB25BE94330B FOREIGN KEY (task_group_id) REFERENCES "task_group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "task" DROP CONSTRAINT FK_527EDB25BE94330B');
        $this->addSql('DROP TABLE "task"');
        $this->addSql('DROP TABLE "task_group"');
    }
}
