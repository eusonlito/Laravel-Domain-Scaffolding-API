<?php declare(strict_types=1);

namespace App\Domains\Shared\Migration\Database;

use Illuminate\Database\Schema\Blueprint;

class MySQL extends DatabaseAbstract
{
    /**
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return void
     */
    public function uuid(Blueprint $table): void
    {
        $table->uuid('uuid')->unique()->default($this->db->raw('(UUID())'));
    }

    /**
     * @return void
     */
    public function functionUpdatedAtNow(): void
    {
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param string $table
     * @param bool $execute = false
     *
     * @return string
     */
    public function dropTriggerUpdatedAt(string $table, bool $execute = false): string
    {
        return '';
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param string $table
     * @param bool $execute = false
     *
     * @return string
     */
    public function createTriggerUpdatedAt(string $table, bool $execute = false): string
    {
        return '';
    }
}
