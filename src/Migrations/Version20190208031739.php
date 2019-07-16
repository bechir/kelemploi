<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190208031739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE immovable DROP CONSTRAINT fk_42f2daaa76ed395');
        $this->addSql('ALTER TABLE basic_info DROP CONSTRAINT fk_9f9f7d3ca76ed395');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE app_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_user (id INT NOT NULL, avatar_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, submitted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E992FC23A8 ON app_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9A0D96FBF ON app_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9C05FB297 ON app_user (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E986383B10 ON app_user (avatar_id)');
        $this->addSql('COMMENT ON COLUMN app_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE immovable DROP CONSTRAINT FK_42F2DAAA76ED395');
        $this->addSql('ALTER TABLE immovable ADD CONSTRAINT FK_42F2DAAA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basic_info DROP CONSTRAINT FK_9F9F7D3CA76ED395');
        $this->addSql('ALTER TABLE basic_info ADD CONSTRAINT FK_9F9F7D3CA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE immovable DROP CONSTRAINT FK_42F2DAAA76ED395');
        $this->addSql('ALTER TABLE basic_info DROP CONSTRAINT FK_9F9F7D3CA76ED395');
        $this->addSql('DROP SEQUENCE app_user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, avatar_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, submitted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64992fc23a8 ON "user" (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649a0d96fbf ON "user" (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649c05fb297 ON "user" (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64986383b10 ON "user" (avatar_id)');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64986383b10 FOREIGN KEY (avatar_id) REFERENCES avatar (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('ALTER TABLE immovable DROP CONSTRAINT fk_42f2daaa76ed395');
        $this->addSql('ALTER TABLE immovable ADD CONSTRAINT fk_42f2daaa76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basic_info DROP CONSTRAINT fk_9f9f7d3ca76ed395');
        $this->addSql('ALTER TABLE basic_info ADD CONSTRAINT fk_9f9f7d3ca76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
