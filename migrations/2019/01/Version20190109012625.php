<?php declare(strict_types=1);

namespace DoctrineMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190109012625 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE public.phones(
         id serial PRIMARY KEY,
         number TEXT UNIQUE NOT NULL,
         created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
         used_at TIMESTAMP
        );");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE public.phones;");
    }
}
