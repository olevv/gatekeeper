<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191027172322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            'postgresql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE TABLE users (
                                uuid UUID NOT NULL, 
                                email VARCHAR(100) NOT NULL, 
                                password_hash VARCHAR(255) NOT NULL,
                                role VARCHAR(50) NOT NULL, 
                                status VARCHAR(16) NOT NULL,
                                access_token VARCHAR(255) DEFAULT NULL,
                                access_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                                PRIMARY KEY(uuid)
                            )'
        );

        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON users (email)');

        $this->addSql('COMMENT ON COLUMN users.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.email IS \'(DC2Type:email)\'');
        $this->addSql('COMMENT ON COLUMN users.password_hash IS \'(DC2Type:hashed_password)\'');
        $this->addSql('COMMENT ON COLUMN users.role IS \'(DC2Type:role)\'');
        $this->addSql('COMMENT ON COLUMN users.status IS \'(DC2Type:status)\'');
        $this->addSql('COMMENT ON COLUMN users.access_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            'postgresql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('DROP TABLE users');
    }
}
