<?php declare(strict_types=1);

namespace App\Domains\Shared\Migration\Database;

use Illuminate\Database\Schema\Blueprint;

class PostgreSQL extends DatabaseAbstract
{
    /**
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return void
     */
    public function uuid(Blueprint $table): void
    {
        $table->uuid('uuid')->unique()->default($this->db->raw('gen_random_uuid()'));
    }

    /**
     * @return void
     */
    public function functionUpdatedAtNow(): void
    {
        $this->db->statement('
            CREATE OR REPLACE FUNCTION updated_at_now()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW."updated_at" = now();
                RETURN NEW;
            END;
            $$ language \'plpgsql\';
        ');
    }

    /**
     * @param string $table
     * @param bool $execute = false
     *
     * @return string
     */
    public function dropTriggerUpdatedAt(string $table, bool $execute = false): string
    {
        $sql = '
            DROP TRIGGER IF EXISTS "update_'.$table.'_updated_at"
            ON "'.$table.'";
        ';

        if ($execute) {
            $this->db->statement($sql);
        }

        return $sql;
    }

    /**
     * @param string $table
     * @param bool $execute = false
     *
     * @return string
     */
    public function createTriggerUpdatedAt(string $table, bool $execute = false): string
    {
        $sql = '
            CREATE TRIGGER "update_'.$table.'_updated_at"
            BEFORE UPDATE ON "'.$table.'"
            FOR EACH ROW EXECUTE PROCEDURE updated_at_now();
        ';

        if ($execute) {
            $this->db->statement($sql);
        }

        return $sql;
    }
}
