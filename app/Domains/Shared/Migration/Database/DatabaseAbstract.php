<?php declare(strict_types=1);

namespace App\Domains\Shared\Migration\Database;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Blueprint;

abstract class DatabaseAbstract
{
    /**
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return void
     */
    abstract public function uuid(Blueprint $table): void;

    /**
     * @return void
     */
    abstract public function functionUpdatedAtNow(): void;

    /**
     * @param string $table
     * @param bool $execute = false
     *
     * @return string
     */
    abstract public function dropTriggerUpdatedAt(string $table, bool $execute = false): string;

    /**
     * @param string $table
     * @param bool $execute = false
     *
     * @return string
     */
    abstract public function createTriggerUpdatedAt(string $table, bool $execute = false): string;

    /**
     * @param \Illuminate\Database\ConnectionInterface $db
     *
     * @return self
     */
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }
}
