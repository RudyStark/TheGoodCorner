<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406131835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acquisition (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, annonce_id INT NOT NULL, address_id INT NOT NULL, INDEX IDX_2FEB9033A76ED395 (user_id), UNIQUE INDEX UNIQ_2FEB90338805AB2F (annonce_id), INDEX IDX_2FEB9033F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acquisition ADD CONSTRAINT FK_2FEB9033A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE acquisition ADD CONSTRAINT FK_2FEB90338805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE acquisition ADD CONSTRAINT FK_2FEB9033F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE annonce ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5A76ED395 ON annonce (user_id)');
        $this->addSql('ALTER TABLE commentary ADD user_id INT NOT NULL, ADD annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CAA76ED395 ON commentary (user_id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CA8805AB2F ON commentary (annonce_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acquisition DROP FOREIGN KEY FK_2FEB9033A76ED395');
        $this->addSql('ALTER TABLE acquisition DROP FOREIGN KEY FK_2FEB90338805AB2F');
        $this->addSql('ALTER TABLE acquisition DROP FOREIGN KEY FK_2FEB9033F5B7AF75');
        $this->addSql('DROP TABLE acquisition');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A76ED395');
        $this->addSql('DROP INDEX IDX_F65593E5A76ED395 ON annonce');
        $this->addSql('ALTER TABLE annonce DROP user_id');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAA76ED395');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA8805AB2F');
        $this->addSql('DROP INDEX IDX_1CAC12CAA76ED395 ON commentary');
        $this->addSql('DROP INDEX IDX_1CAC12CA8805AB2F ON commentary');
        $this->addSql('ALTER TABLE commentary DROP user_id, DROP annonce_id');
    }
}
