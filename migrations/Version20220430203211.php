<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430203211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission ADD type_of_mission_id INT NOT NULL, ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C27D5D830 FOREIGN KEY (type_of_mission_id) REFERENCES type_mission (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C6BF700BD FOREIGN KEY (status_id) REFERENCES status_mission (id)');
        $this->addSql('CREATE INDEX IDX_9067F23C27D5D830 ON mission (type_of_mission_id)');
        $this->addSql('CREATE INDEX IDX_9067F23C6BF700BD ON mission (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C27D5D830');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C6BF700BD');
        $this->addSql('DROP INDEX IDX_9067F23C27D5D830 ON mission');
        $this->addSql('DROP INDEX IDX_9067F23C6BF700BD ON mission');
        $this->addSql('ALTER TABLE mission DROP type_of_mission_id, DROP status_id');
    }
}
