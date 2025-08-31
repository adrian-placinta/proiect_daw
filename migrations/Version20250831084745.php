<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831084745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, destination VARCHAR(255) NOT NULL, departure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION NOT NULL, available_seats INT NOT NULL, is_available TINYINT(1) NOT NULL, is_booked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_trip (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, trip_id INT NOT NULL, qr_code VARCHAR(255) NOT NULL, booking_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CD7B9F2A76ED395 (user_id), INDEX IDX_CD7B9F2A5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_trip ADD CONSTRAINT FK_CD7B9F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_trip ADD CONSTRAINT FK_CD7B9F2A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_trip DROP FOREIGN KEY FK_CD7B9F2A76ED395');
        $this->addSql('ALTER TABLE user_trip DROP FOREIGN KEY FK_CD7B9F2A5BC2E0E');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_trip');
    }
}
