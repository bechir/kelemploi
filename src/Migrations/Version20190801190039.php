<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190801190039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE education (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER NOT NULL, designation VARCHAR(255) NOT NULL, institue VARCHAR(255) NOT NULL, period VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_DB0A5ED2D262AF09 ON education (resume_id)');
        $this->addSql('CREATE TABLE portfolio (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image_id INTEGER DEFAULT NULL, resume_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9ED10623DA5256D ON portfolio (image_id)');
        $this->addSql('CREATE INDEX IDX_A9ED1062D262AF09 ON portfolio (resume_id)');
        $this->addSql('CREATE TABLE portfolio_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, src VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE resume (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, job_category_id INTEGER DEFAULT NULL, study_level_id INTEGER DEFAULT NULL, experience_level_id INTEGER DEFAULT NULL, cv_id INTEGER DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, about CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_60C1D0A0712A86AB ON resume (job_category_id)');
        $this->addSql('CREATE INDEX IDX_60C1D0A0FC385E2E ON resume (study_level_id)');
        $this->addSql('CREATE INDEX IDX_60C1D0A041F59224 ON resume (experience_level_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_60C1D0A0CFE419E2 ON resume (cv_id)');
        $this->addSql('CREATE TABLE resume_skill (resume_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(resume_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_C2CA241FD262AF09 ON resume_skill (resume_id)');
        $this->addSql('CREATE INDEX IDX_C2CA241F5585C142 ON resume_skill (skill_id)');
        $this->addSql('CREATE TABLE social_account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE social_profil (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, account_id INTEGER NOT NULL, resume_id INTEGER NOT NULL, url VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_F89DD04B9B6B5FBA ON social_profil (account_id)');
        $this->addSql('CREATE INDEX IDX_F89DD04BD262AF09 ON social_profil (resume_id)');
        $this->addSql('CREATE TABLE work_experience (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER NOT NULL, designation VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, period VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_1EF36CD0D262AF09 ON work_experience (resume_id)');
        $this->addSql('DROP TABLE app_user_skill');
        $this->addSql('DROP TABLE user_skill');
        $this->addSql('DROP INDEX IDX_88BDF3E9C6798DB');
        $this->addSql('DROP INDEX IDX_88BDF3E9712A86AB');
        $this->addSql('DROP INDEX UNIQ_88BDF3E992FC23A8');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9C05FB297');
        $this->addSql('DROP INDEX IDX_88BDF3E923D6A298');
        $this->addSql('DROP INDEX UNIQ_88BDF3E986383B10');
        $this->addSql('DROP INDEX IDX_88BDF3E9979B1AD6');
        $this->addSql('DROP INDEX IDX_88BDF3E998260155');
        $this->addSql('DROP INDEX IDX_88BDF3E946E90E27');
        $this->addSql('DROP INDEX IDX_88BDF3E9FC385E2E');
        $this->addSql('DROP INDEX IDX_88BDF3E9708A0E0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_user AS SELECT id, civility_id, avatar_id, company_id, region_id, gender_id, account_type_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, view_count, age FROM app_user');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('CREATE TABLE app_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER DEFAULT NULL, avatar_id INTEGER DEFAULT NULL, company_id INTEGER DEFAULT NULL, region_id INTEGER DEFAULT NULL, gender_id INTEGER DEFAULT NULL, account_type_id INTEGER DEFAULT NULL, username VARCHAR(180) NOT NULL COLLATE BINARY, username_canonical VARCHAR(180) NOT NULL COLLATE BINARY, email VARCHAR(180) NOT NULL COLLATE BINARY, email_canonical VARCHAR(180) NOT NULL COLLATE BINARY, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE BINARY, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:array)
        , first_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, last_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, phone_number VARCHAR(255) DEFAULT NULL COLLATE BINARY, submitted_at DATETIME NOT NULL, country VARCHAR(255) DEFAULT NULL COLLATE BINARY, locale VARCHAR(10) DEFAULT NULL COLLATE BINARY, view_count INTEGER DEFAULT NULL, age INTEGER DEFAULT NULL, CONSTRAINT FK_88BDF3E923D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E998260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E9708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_88BDF3E9C6798DB FOREIGN KEY (account_type_id) REFERENCES account_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO app_user (id, civility_id, avatar_id, company_id, region_id, gender_id, account_type_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, view_count, age) SELECT id, civility_id, avatar_id, company_id, region_id, gender_id, account_type_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, view_count, age FROM __temp__app_user');
        $this->addSql('DROP TABLE __temp__app_user');
        $this->addSql('CREATE INDEX IDX_88BDF3E9C6798DB ON app_user (account_type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E992FC23A8 ON app_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9A0D96FBF ON app_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9C05FB297 ON app_user (confirmation_token)');
        $this->addSql('CREATE INDEX IDX_88BDF3E923D6A298 ON app_user (civility_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E986383B10 ON app_user (avatar_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9979B1AD6 ON app_user (company_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E998260155 ON app_user (region_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9708A0E0 ON app_user (gender_id)');
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
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools, view_count FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, company_id INTEGER DEFAULT NULL, interlocutor_id INTEGER DEFAULT NULL, post_category_id INTEGER DEFAULT NULL, dates_id INTEGER NOT NULL, contract_type_id INTEGER NOT NULL, min_study_level_id INTEGER DEFAULT NULL, required_languages_id INTEGER DEFAULT NULL, experience_id INTEGER DEFAULT NULL, gender_id INTEGER DEFAULT NULL, job_title VARCHAR(255) NOT NULL COLLATE BINARY, job_description CLOB NOT NULL COLLATE BINARY, nb_candidates_to_recruit INTEGER DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL COLLATE BINARY, work_time VARCHAR(255) DEFAULT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, status VARCHAR(255) DEFAULT NULL COLLATE BINARY, benefits CLOB DEFAULT NULL COLLATE BINARY, responsibilities CLOB DEFAULT NULL COLLATE BINARY, tools CLOB DEFAULT NULL COLLATE BINARY, view_count INTEGER DEFAULT NULL, CONSTRAINT FK_A45BDDC1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1B3F944DB FOREIGN KEY (interlocutor_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1FE0617CD FOREIGN KEY (post_category_id) REFERENCES job_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC13DA992C3 FOREIGN KEY (dates_id) REFERENCES date_interval (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1CD1DF15B FOREIGN KEY (contract_type_id) REFERENCES contract_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC18F79604F FOREIGN KEY (min_study_level_id) REFERENCES study_level (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1AD6CAAB2 FOREIGN KEY (required_languages_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A45BDDC1708A0E0 FOREIGN KEY (gender_id) REFERENCES job_gender (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO application (id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools, view_count) SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools, view_count FROM __temp__application');
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
        $this->addSql('DROP INDEX IDX_4FBF094F98260155');
        $this->addSql('DROP INDEX UNIQ_4FBF094F7E9E4C8C');
        $this->addSql('DROP INDEX UNIQ_4FBF094F989D9B62');
        $this->addSql('DROP INDEX IDX_4FBF094F12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, region_id, photo_id, category_id, name, description, address, zip, website, email, slug FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, region_id INTEGER DEFAULT NULL, photo_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, address VARCHAR(255) DEFAULT NULL COLLATE BINARY, zip VARCHAR(255) DEFAULT NULL COLLATE BINARY, website VARCHAR(100) DEFAULT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_4FBF094F98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4FBF094F12469DE2 FOREIGN KEY (category_id) REFERENCES job_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4FBF094F7E9E4C8C FOREIGN KEY (photo_id) REFERENCES company_photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO company (id, region_id, photo_id, category_id, name, description, address, zip, website, email, slug) SELECT id, region_id, photo_id, category_id, name, description, address, zip, website, email, slug FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE INDEX IDX_4FBF094F98260155 ON company (region_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F7E9E4C8C ON company (photo_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F989D9B62 ON company (slug)');
        $this->addSql('CREATE INDEX IDX_4FBF094F12469DE2 ON company (category_id)');
        $this->addSql('DROP INDEX IDX_C8B28E4423D6A298');
        $this->addSql('DROP INDEX IDX_C8B28E44FC385E2E');
        $this->addSql('DROP INDEX IDX_C8B28E445D237A9A');
        $this->addSql('DROP INDEX UNIQ_C8B28E44CFE419E2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__candidate AS SELECT id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address FROM candidate');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('CREATE TABLE candidate (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER NOT NULL, study_level_id INTEGER NOT NULL, languages_id INTEGER NOT NULL, cv_id INTEGER DEFAULT NULL, first_name VARCHAR(255) NOT NULL COLLATE BINARY, last_name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, address VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_C8B28E4423D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C8B28E44FC385E2E FOREIGN KEY (study_level_id) REFERENCES study_level (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C8B28E445D237A9A FOREIGN KEY (languages_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C8B28E44CFE419E2 FOREIGN KEY (cv_id) REFERENCES cvfile (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO candidate (id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address) SELECT id, civility_id, study_level_id, languages_id, cv_id, first_name, last_name, email, address FROM __temp__candidate');
        $this->addSql('DROP TABLE __temp__candidate');
        $this->addSql('CREATE INDEX IDX_C8B28E4423D6A298 ON candidate (civility_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44FC385E2E ON candidate (study_level_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E445D237A9A ON candidate (languages_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B28E44CFE419E2 ON candidate (cv_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE app_user_skill (user_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(user_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_EAE4B4535585C142 ON app_user_skill (skill_id)');
        $this->addSql('CREATE INDEX IDX_EAE4B453A76ED395 ON app_user_skill (user_id)');
        $this->addSql('CREATE TABLE user_skill (user_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(user_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_BCFF1F2F5585C142 ON user_skill (skill_id)');
        $this->addSql('CREATE INDEX IDX_BCFF1F2FA76ED395 ON user_skill (user_id)');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE portfolio');
        $this->addSql('DROP TABLE portfolio_image');
        $this->addSql('DROP TABLE resume');
        $this->addSql('DROP TABLE resume_skill');
        $this->addSql('DROP TABLE social_account');
        $this->addSql('DROP TABLE social_profil');
        $this->addSql('DROP TABLE work_experience');
        $this->addSql('DROP INDEX UNIQ_88BDF3E992FC23A8');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_88BDF3E9C05FB297');
        $this->addSql('DROP INDEX IDX_88BDF3E923D6A298');
        $this->addSql('DROP INDEX UNIQ_88BDF3E986383B10');
        $this->addSql('DROP INDEX IDX_88BDF3E9979B1AD6');
        $this->addSql('DROP INDEX IDX_88BDF3E998260155');
        $this->addSql('DROP INDEX IDX_88BDF3E9708A0E0');
        $this->addSql('DROP INDEX IDX_88BDF3E9C6798DB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_user AS SELECT id, civility_id, avatar_id, company_id, region_id, gender_id, account_type_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, view_count, age FROM app_user');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('CREATE TABLE app_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, civility_id INTEGER DEFAULT NULL, avatar_id INTEGER DEFAULT NULL, company_id INTEGER DEFAULT NULL, region_id INTEGER DEFAULT NULL, gender_id INTEGER DEFAULT NULL, account_type_id INTEGER DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, submitted_at DATETIME NOT NULL, country VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) DEFAULT NULL, view_count INTEGER DEFAULT NULL, age INTEGER DEFAULT NULL, job_category_id INTEGER DEFAULT NULL, experience_id INTEGER DEFAULT NULL, study_level_id INTEGER DEFAULT NULL, about VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO app_user (id, civility_id, avatar_id, company_id, region_id, gender_id, account_type_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, view_count, age) SELECT id, civility_id, avatar_id, company_id, region_id, gender_id, account_type_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, phone_number, submitted_at, country, locale, view_count, age FROM __temp__app_user');
        $this->addSql('DROP TABLE __temp__app_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E992FC23A8 ON app_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9A0D96FBF ON app_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9C05FB297 ON app_user (confirmation_token)');
        $this->addSql('CREATE INDEX IDX_88BDF3E923D6A298 ON app_user (civility_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E986383B10 ON app_user (avatar_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9979B1AD6 ON app_user (company_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E998260155 ON app_user (region_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9708A0E0 ON app_user (gender_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9C6798DB ON app_user (account_type_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9712A86AB ON app_user (job_category_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E946E90E27 ON app_user (experience_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9FC385E2E ON app_user (study_level_id)');
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
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools, view_count FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, company_id INTEGER DEFAULT NULL, interlocutor_id INTEGER DEFAULT NULL, post_category_id INTEGER DEFAULT NULL, dates_id INTEGER NOT NULL, contract_type_id INTEGER NOT NULL, min_study_level_id INTEGER DEFAULT NULL, required_languages_id INTEGER DEFAULT NULL, experience_id INTEGER DEFAULT NULL, gender_id INTEGER DEFAULT NULL, job_title VARCHAR(255) NOT NULL, job_description CLOB NOT NULL, nb_candidates_to_recruit INTEGER DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, work_time VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, benefits CLOB DEFAULT NULL, responsibilities CLOB DEFAULT NULL, tools CLOB DEFAULT NULL, view_count INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO application (id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools, view_count) SELECT id, company_id, interlocutor_id, post_category_id, dates_id, contract_type_id, min_study_level_id, required_languages_id, experience_id, gender_id, job_title, job_description, nb_candidates_to_recruit, salary, work_time, slug, status, benefits, responsibilities, tools, view_count FROM __temp__application');
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
    }
}
