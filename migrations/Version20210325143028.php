<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325143028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(150) DEFAULT NULL, last_name VARCHAR(150) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_calendar (user_id INT NOT NULL, calendar_id INT NOT NULL, INDEX IDX_8E244546A76ED395 (user_id), INDEX IDX_8E244546A40A2C8 (calendar_id), PRIMARY KEY(user_id, calendar_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_contact (user_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_146FF832A76ED395 (user_id), INDEX IDX_146FF832E7A1254A (contact_id), PRIMARY KEY(user_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_calendar ADD CONSTRAINT FK_8E244546A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_calendar ADD CONSTRAINT FK_8E244546A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF832A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF832E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact ADD category_contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638C1BF7534 FOREIGN KEY (category_contact_id) REFERENCES category_contact (id)');
        $this->addSql('CREATE INDEX IDX_4C62E638C1BF7534 ON contact (category_contact_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_calendar DROP FOREIGN KEY FK_8E244546A76ED395');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF832A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_calendar');
        $this->addSql('DROP TABLE user_contact');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638C1BF7534');
        $this->addSql('DROP INDEX IDX_4C62E638C1BF7534 ON contact');
        $this->addSql('ALTER TABLE contact DROP category_contact_id');
    }
}
