<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729123729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_A45BDDC1708A0E0');
        $this->addSql('DROP INDEX IDX_A45BDDC146E90E27');
        $this->addSql('DROP INDEX IDX_A45BDDC1AD6CAAB2');
        $this->addSql('DROP INDEX IDX_A45BDDC18F79604F');
        $this->addSql('DROP INDEX IDX_A45BDDC1CD1DF15B');
        $this->addSql('DROP INDEX UNIQ_A45BDDC13DA992C3');
        $this->addSql('DROP INDEX IDX_A45BDDC1FE0617CD');
        $this->addSql('DROP INDEX IDX_A45BDDC1B3F944DB');
        $this->addSql('DROP INDEX IDX_A45BDDC1979B1AD6');
        $this->addSql('DROP INDEX UNIQ_A45BDDC1989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, company_id INTEGER DEFAULT NULL, interlocutor_id INTEGER DEFAULT NULL, post_category_id INTEGER DEFAULT NULL, dates_id INTEGER NOT NULL, contract_type_id INTEGER NOT NULL, min_study_level_id INTEGER DEFAULT NULL, required_languages_id INTEGER DEFAULT NULL, experience_id INTEGER DEFAULT NULL, gender_id INTEGER DEFAULT NULL, job_title VARCHAR(255) NOT NULL COLLATE BINARY, job_description CLOB NOT NULL COLLATE BINARY, nb_candidates_to_recruit INTEGER DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL COLLATE BINARY, work_time VARCHAR(255) DEFAULT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, status VARCHAR(255) DEFAULT NULL COLLATE BINARY, benefits CLOB DEFAULT NULL COLLATE BINARY, responsibilities CLOB DEFAULT NULL COLLATE BINARY, tools CLOB DEFAULT NULL COLLATE BINARY, view_count INTEGER DEFAULT NULL, CONSTRAINT FK_A45BDDC1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1B3F944DB FOREIGN KEY (interlocutor_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1FE0617CD FOREIGN KEY (post_category_id) REFERENCES job_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC13DA992C3 FOREIGN KEY (dates_id) REFERENCES date_interval (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1CD1DF15B FOREIGN KEY (contract_type_id) REFERENCES contract_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC18F79604F FOREIGN KEY (min_study_level_id) REFERENCES study_level (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1AD6CAAB2 FOREIGN KEY (required_languages_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1708A0E0 FOREIGN KEY (gender_id) REFERENCES job_gender (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO application (id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools) SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools FROM __temp__application');
        $this->addSql('DROP TABLE __temp__application');
        $this->addSql('CREATE INDEX IDX_A45BDDC1708A0E0 ON application (gender_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC146E90E27 ON application (experience_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1AD6CAAB2 ON application (required_languages_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC18F79604F ON application (min_study_level_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1CD1DF15B ON application (contract_type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A45BDDC13DA992C3 ON application (dates_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1FE0617CD ON application (post_category_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1B3F944DB ON application (interlocutor_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1979B1AD6 ON application (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A45BDDC1989D9B62 ON application (slug)');
        $this->addSql('DROP INDEX IDX_88BDF3E998260155');
        $this->addSql('DROP INDEX IDX_88BDF3E9979B1AD6');
        $this->addSql('DROP INDEX UNIQ_88BDF3E986383B10');
        $this->addSql('DROP INDEX IDX_88BDF3E923D6A298');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9C05FB297');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_88BDF3E992FC23A8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_user AS SELECT id, civility_id, avatar_id, company_id, region_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, about FROM app_user');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('CREATE TABLE app_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER DEFAULT NULL, avatar_id INTEGER DEFAULT NULL, company_id INTEGER DEFAULT NULL, region_id INTEGER DEFAULT NULL, username VARCHAR(180) NOT NULL COLLATE BINARY, username_canonical VARCHAR(180) NOT NULL COLLATE BINARY, email VARCHAR(180) NOT NULL COLLATE BINARY, email_canonical VARCHAR(180) NOT NULL COLLATE BINARY, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE BINARY, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:array)
        , first_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, last_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, phone_number VARCHAR(255) DEFAULT NULL COLLATE BINARY, submitted_at DATETIME NOT NULL, country VARCHAR(255) DEFAULT NULL COLLATE BINARY, locale VARCHAR(10) DEFAULT NULL COLLATE BINARY, about VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_88BDF3E923D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E998260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO app_user (id, civility_id, avatar_id, company_id, region_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, about) SELECT id, civility_id, avatar_id, company_id, region_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, about FROM __temp__app_user');
        $this->addSql('DROP TABLE __temp__app_user');
        $this->addSql('CREATE INDEX IDX_88BDF3E998260155 ON app_user (region_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9979B1AD6 ON app_user (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E986383B10 ON app_user (avatar_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E923D6A298 ON app_user (civility_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9C05FB297 ON app_user (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9A0D96FBF ON app_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E992FC23A8 ON app_user (username_canonical)');
        $this->addSql('DROP INDEX IDX_BCFF1F2F5585C142');
        $this->addSql('DROP INDEX IDX_BCFF1F2FA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_skill AS SELECT user_id, skill_id FROM user_skill');
        $this->addSql('DROP TABLE user_skill');
        $this->addSql('CREATE TABLE user_skill (user_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(user_id, skill_id), CONSTRAINT FK_BCFF1F2FA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BCFF1F2F5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_skill (user_id, skill_id) SELECT user_id, skill_id FROM __temp__user_skill');
        $this->addSql('DROP TABLE __temp__user_skill');
        $this->addSql('CREATE INDEX IDX_BCFF1F2F5585C142 ON user_skill (skill_id)');
        $this->addSql('CREATE INDEX IDX_BCFF1F2FA76ED395 ON user_skill (user_id)');
        $this->addSql('DROP INDEX IDX_4FBF094F12469DE2');
        $this->addSql('DROP INDEX UNIQ_4FBF094F989D9B62');
        $this->addSql('DROP INDEX UNIQ_4FBF094F7E9E4C8C');
        $this->addSql('DROP INDEX IDX_4FBF094F98260155');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, region_id, photo_id, category_id, name, description, address, zip, website, email, slug FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, region_id INTEGER DEFAULT NULL, photo_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, address VARCHAR(255) DEFAULT NULL COLLATE BINARY, zip VARCHAR(255) DEFAULT NULL COLLATE BINARY, website VARCHAR(100) DEFAULT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_4FBF094F98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4FBF094F12469DE2 FOREIGN KEY (category_id) REFERENCES job_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4FBF094F7E9E4C8C FOREIGN KEY (photo_id) REFERENCES company_photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO company (id, region_id, photo_id, category_id, name, description, address, zip, website, email, slug) SELECT id, region_id, photo_id, category_id, name, description, address, zip, website, email, slug FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE INDEX IDX_4FBF094F12469DE2 ON company (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F989D9B62 ON company (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F7E9E4C8C ON company (photo_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F98260155 ON company (region_id)');
        $this->addSql('DROP INDEX UNIQ_C8B28E44CFE419E2');
        $this->addSql('DROP INDEX IDX_C8B28E445D237A9A');
        $this->addSql('DROP INDEX IDX_C8B28E44FC385E2E');
        $this->addSql('DROP INDEX IDX_C8B28E4423D6A298');
        $this->addSql('CREATE TEMPORARY TABLE __temp__candidate AS SELECT id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address FROM candidate');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('CREATE TABLE candidate (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER NOT NULL, study_level_id INTEGER NOT NULL, languages_id INTEGER NOT NULL, cv_id INTEGER DEFAULT NULL, first_name VARCHAR(255) NOT NULL COLLATE BINARY, last_name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, address VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_C8B28E4423D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C8B28E44FC385E2E FOREIGN KEY (study_level_id) REFERENCES study_level (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C8B28E445D237A9A FOREIGN KEY (languages_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C8B28E44CFE419E2 FOREIGN KEY (cv_id) REFERENCES cvfile (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO candidate (id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address) SELECT id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address FROM __temp__candidate');
        $this->addSql('DROP TABLE __temp__candidate');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B28E44CFE419E2 ON candidate (cv_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E445D237A9A ON candidate (languages_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44FC385E2E ON candidate (study_level_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E4423D6A298 ON candidate (civility_id)');
        $this->addSql('DROP INDEX IDX_5E3DE47712469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__skill AS SELECT id, category_id, name FROM skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(100) NOT NULL COLLATE BINARY, CONSTRAINT FK_5E3DE47712469DE2 FOREIGN KEY (category_id) REFERENCES job_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO skill (id, category_id, name) SELECT id, category_id, name FROM __temp__skill');
        $this->addSql('DROP TABLE __temp__skill');
        $this->addSql('CREATE INDEX IDX_5E3DE47712469DE2 ON skill (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_88BDF3E992FC23A8');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9C05FB297');
        $this->addSql('DROP INDEX IDX_88BDF3E923D6A298');
        $this->addSql('DROP INDEX UNIQ_88BDF3E986383B10');
        $this->addSql('DROP INDEX IDX_88BDF3E9979B1AD6');
        $this->addSql('DROP INDEX IDX_88BDF3E998260155');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_user AS SELECT id, civility_id, avatar_id, company_id, region_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, about FROM app_user');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('CREATE TABLE app_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER DEFAULT NULL, avatar_id INTEGER DEFAULT NULL, company_id INTEGER DEFAULT NULL, region_id INTEGER DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, submitted_at DATETIME NOT NULL, country VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) DEFAULT NULL, about VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO app_user (id, civility_id, avatar_id, company_id, region_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, about) SELECT id, civility_id, avatar_id, company_id, region_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, about FROM __temp__app_user');
        $this->addSql('DROP TABLE __temp__app_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E992FC23A8 ON app_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9A0D96FBF ON app_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9C05FB297 ON app_user (confirmation_token)');
        $this->addSql('CREATE INDEX IDX_88BDF3E923D6A298 ON app_user (civility_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E986383B10 ON app_user (avatar_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9979B1AD6 ON app_user (company_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E998260155 ON app_user (region_id)');
        $this->addSql('DROP INDEX UNIQ_A45BDDC1989D9B62');
        $this->addSql('DROP INDEX IDX_A45BDDC1979B1AD6');
        $this->addSql('DROP INDEX IDX_A45BDDC1B3F944DB');
        $this->addSql('DROP INDEX IDX_A45BDDC1FE0617CD');
        $this->addSql('DROP INDEX UNIQ_A45BDDC13DA992C3');
        $this->addSql('DROP INDEX IDX_A45BDDC1CD1DF15B');
        $this->addSql('DROP INDEX IDX_A45BDDC18F79604F');
        $this->addSql('DROP INDEX IDX_A45BDDC1AD6CAAB2');
        $this->addSql('DROP INDEX IDX_A45BDDC146E90E27');
        $this->addSql('DROP INDEX IDX_A45BDDC1708A0E0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, company_id INTEGER DEFAULT NULL, interlocutor_id INTEGER DEFAULT NULL, post_category_id INTEGER DEFAULT NULL, dates_id INTEGER NOT NULL, contract_type_id INTEGER NOT NULL, min_study_level_id INTEGER DEFAULT NULL, required_languages_id INTEGER DEFAULT NULL, experience_id INTEGER DEFAULT NULL, gender_id INTEGER DEFAULT NULL, job_title VARCHAR(255) NOT NULL, job_description CLOB NOT NULL, nb_candidates_to_recruit INTEGER DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, work_time VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, benefits CLOB DEFAULT NULL, responsibilities CLOB DEFAULT NULL, tools CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO application (id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools) SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools FROM __temp__application');
        $this->addSql('DROP TABLE __temp__application');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A45BDDC1989D9B62 ON application (slug)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1979B1AD6 ON application (company_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1B3F944DB ON application (interlocutor_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1FE0617CD ON application (post_category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A45BDDC13DA992C3 ON application (dates_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1CD1DF15B ON application (contract_type_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC18F79604F ON application (min_study_level_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1AD6CAAB2 ON application (required_languages_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC146E90E27 ON application (experience_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1708A0E0 ON application (gender_id)');
        $this->addSql('DROP INDEX IDX_C8B28E4423D6A298');
        $this->addSql('DROP INDEX IDX_C8B28E44FC385E2E');
        $this->addSql('DROP INDEX IDX_C8B28E445D237A9A');
        $this->addSql('DROP INDEX UNIQ_C8B28E44CFE419E2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__candidate AS SELECT id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address FROM candidate');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('CREATE TABLE candidate (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER NOT NULL, study_level_id INTEGER NOT NULL, languages_id INTEGER NOT NULL, cv_id INTEGER DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO candidate (id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address) SELECT id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address FROM __temp__candidate');
        $this->addSql('DROP TABLE __temp__candidate');
        $this->addSql('CREATE INDEX IDX_C8B28E4423D6A298 ON candidate (civility_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44FC385E2E ON candidate (study_level_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E445D237A9A ON candidate (languages_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B28E44CFE419E2 ON candidate (cv_id)');
        $this->addSql('DROP INDEX UNIQ_4FBF094F989D9B62');
        $this->addSql('DROP INDEX IDX_4FBF094F98260155');
        $this->addSql('DROP INDEX IDX_4FBF094F12469DE2');
        $this->addSql('DROP INDEX UNIQ_4FBF094F7E9E4C8C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, region_id, category_id, photo_id, name, email, description, address, zip, website, slug FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, region_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, photo_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, description CLOB NOT NULL, address VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, website VARCHAR(100) DEFAULT NULL, slug VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO company (id, region_id, category_id, photo_id, name, email, description, address, zip, website, slug) SELECT id, region_id, category_id, photo_id, name, email, description, address, zip, website, slug FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F989D9B62 ON company (slug)');
        $this->addSql('CREATE INDEX IDX_4FBF094F98260155 ON company (region_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F12469DE2 ON company (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F7E9E4C8C ON company (photo_id)');
        $this->addSql('DROP INDEX IDX_5E3DE47712469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__skill AS SELECT id, category_id, name FROM skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO skill (id, category_id, name) SELECT id, category_id, name FROM __temp__skill');
        $this->addSql('DROP TABLE __temp__skill');
        $this->addSql('CREATE INDEX IDX_5E3DE47712469DE2 ON skill (category_id)');
        $this->addSql('DROP INDEX IDX_BCFF1F2FA76ED395');
        $this->addSql('DROP INDEX IDX_BCFF1F2F5585C142');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_skill AS SELECT user_id, skill_id FROM user_skill');
        $this->addSql('DROP TABLE user_skill');
        $this->addSql('CREATE TABLE user_skill (user_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(user_id, skill_id))');
        $this->addSql('INSERT INTO user_skill (user_id, skill_id) SELECT user_id, skill_id FROM __temp__user_skill');
        $this->addSql('DROP TABLE __temp__user_skill');
        $this->addSql('CREATE INDEX IDX_BCFF1F2FA76ED395 ON user_skill (user_id)');
        $this->addSql('CREATE INDEX IDX_BCFF1F2F5585C142 ON user_skill (skill_id)');
    }
}
