<?php declare(strict_types=1);

namespace App\Domains\UserToken\Action;

use App\Domains\UserToken\Model\UserToken as Model;
use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\UserToken\Model\UserToken
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function check(): Model
    {
        return $this->actionHandle(Check::class, $this->validate()->check());
    }

    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function create(): Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create());
    }

    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function expire(): Model
    {
        return $this->actionHandle(Expire::class, $this->validate()->expire());
    }

    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function refresh(): Model
    {
        return $this->actionHandle(Refresh::class, $this->validate()->refresh());
    }
}
