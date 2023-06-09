<?php declare(strict_types=1);

namespace App\Domains\UserCode\Test\Factory;

use Illuminate\Database\Eloquent\Factories\Factory as FactoryEloquent;
use App\Domains\User\Model\User as UserModel;
use App\Domains\UserCode\Model\UserCode as Model;

class UserCode extends FactoryEloquent
{
    /**
     * @var class-string<\App\Domains\UserCode\Model\UserCode>
     */
    protected $model = Model::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => uniqid(),
            'ip' => $this->faker->ipv4,
            'user_id' => UserModel::factory(),
        ];
    }
}
