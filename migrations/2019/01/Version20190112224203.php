<?php declare(strict_types=1);

namespace DoctrineMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190112224203 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE public.location (
         id serial PRIMARY KEY,
         external_id INTEGER NOT NULL,
         name TEXT NOT NULL,
         type TEXT NOT NULL,
         level INTEGER NOT NULL,
         coordinates JSON,
         parent_id INTEGER DEFAULT NULL CONSTRAINT location_parent_id_fk
		 REFERENCES location,
		 region_id INTEGER DEFAULT NULL CONSTRAINT location_region_id_fk
		 REFERENCES location,
         created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(0)
        );");

        $this->addSql("CREATE INDEX location_external_id_index ON public.location (external_id);");
        $this->addSql("CREATE INDEX location_name_index ON public.location (name);");
        $this->addSql("CREATE INDEX location_type_index ON public.location (type);");
        $this->addSql("CREATE INDEX location_level_index ON public.location (level);");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE public.location;");
    }
}
