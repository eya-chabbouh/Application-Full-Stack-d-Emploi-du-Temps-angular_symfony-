<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124193809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE class_entity CHANGE students students JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4EA000B10');
        $this->addSql('DROP INDEX IDX_D044D5D4EA000B10 ON session');
        $this->addSql('ALTER TABLE session ADD class_entity_id INT NOT NULL, DROP class_id, CHANGE subject_id subject_id INT NOT NULL, CHANGE teacher_id teacher_id INT NOT NULL, CHANGE room_id room_id INT NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4EAFAE262 FOREIGN KEY (class_entity_id) REFERENCES class_entity (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D4EAFAE262 ON session (class_entity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE class_entity CHANGE students students LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4EAFAE262');
        $this->addSql('DROP INDEX IDX_D044D5D4EAFAE262 ON session');
        $this->addSql('ALTER TABLE session ADD class_id INT DEFAULT NULL, DROP class_entity_id, CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE teacher_id teacher_id INT DEFAULT NULL, CHANGE room_id room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4EA000B10 FOREIGN KEY (class_id) REFERENCES class_entity (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D4EA000B10 ON session (class_id)');
    }
}
