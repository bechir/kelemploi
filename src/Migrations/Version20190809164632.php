<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190809164632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE apply_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE apply (id INT NOT NULL, candidate_id INT DEFAULT NULL, application_id INT DEFAULT NULL, cv_file_id INT DEFAULT NULL, fullname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message TEXT NOT NULL, status VARCHAR(255) DEFAULT NULL, applied_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, answered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BD2F8C1F91BD8781 ON apply (candidate_id)');
        $this->addSql('CREATE INDEX IDX_BD2F8C1F3E030ACD ON apply (application_id)');
        $this->addSql('CREATE INDEX IDX_BD2F8C1FD8422A22 ON apply (cv_file_id)');
        $this->addSql('ALTER TABLE apply ADD CONSTRAINT FK_BD2F8C1F91BD8781 FOREIGN KEY (candidate_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE apply ADD CONSTRAINT FK_BD2F8C1F3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE apply ADD CONSTRAINT FK_BD2F8C1FD8422A22 FOREIGN KEY (cv_file_id) REFERENCES cvfile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE application_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE apply_id_seq CASCADE');
        $this->addSql('CREATE TABLE application_user (application_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(application_id, user_id))');
        $this->addSql('CREATE INDEX idx_7a7fbec1a76ed395 ON application_user (user_id)');
        $this->addSql('CREATE INDEX idx_7a7fbec13e030acd ON application_user (application_id)');
        $this->addSql('ALTER TABLE application_user ADD CONSTRAINT fk_7a7fbec13e030acd FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_user ADD CONSTRAINT fk_7a7fbec1a76ed395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE apply');
    }
}
