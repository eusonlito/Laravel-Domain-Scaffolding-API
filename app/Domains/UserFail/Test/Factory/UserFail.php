<?php declare(strict_types=1);

namespace App\Domains\UserFail\Test\Factory;

use App\Domains\SharedApp\Test\Factory\FactoryAbstract;
use App\Domains\UserFail\Model\UserFail as Model;

class UserFail extends FactoryAbstract
{
    /**
     * @var class-string<\App\Domains\UserFail\Model\UserFail>
     */
    protected $model = Model::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->email(),
            'ip' => $this->faker->ip,

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

            'user_id' => (rand(1, 0) ? $this->userFirstOrFactory() : null),
        ];
    }
}
