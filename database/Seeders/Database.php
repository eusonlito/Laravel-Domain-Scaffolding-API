<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Configuration\Seeder\Configuration as ConfigurationSeeder;
use App\Domains\Language\Seeder\Language as LanguageSeeder;

class Database extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $time = time();

        $this->call(ConfigurationSeeder::class);
        $this->call(LanguageSeeder::class);

        $this->command->info(sprintf('Seeding: Total Time %s seconds', time() - $time));
    }
}
