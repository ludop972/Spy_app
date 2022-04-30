<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430193332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent_specialities (agent_id INT NOT NULL, specialities_id INT NOT NULL, INDEX IDX_89E364DA3414710B (agent_id), INDEX IDX_89E364DA804698D6 (specialities_id), PRIMARY KEY(agent_id, specialities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent_specialities ADD CONSTRAINT FK_89E364DA3414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent_specialities ADD CONSTRAINT FK_89E364DA804698D6 FOREIGN KEY (specialities_id) REFERENCES specialities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent ADD nationality_id INT NOT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D1C9DA55 FOREIGN KEY (nationality_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D1C9DA55 ON agent (nationality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agent_specialities');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D1C9DA55');
        $this->addSql('DROP INDEX IDX_268B9C9D1C9DA55 ON agent');
        $this->addSql('ALTER TABLE agent DROP nationality_id');
    }
}
