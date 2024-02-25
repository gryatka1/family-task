<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240204134733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD created_at_by_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD assigned_to_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE task_group ADD created_at_by_user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "task_group" DROP created_at_by_user_id');
        $this->addSql('ALTER TABLE "task" DROP created_at_by_user_id');
        $this->addSql('ALTER TABLE "task" DROP assigned_to_user_id');
    }
}
