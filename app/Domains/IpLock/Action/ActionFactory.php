<?php declare(strict_types=1);

namespace App\Domains\IpLock\Action;

use App\Domains\IpLock\Model\IpLock as Model;
use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @return void
     */
    public function check(): void
    {
        $this->actionHandle(Check::class, $this->validate()->check());
    }

    /**
     * @return ?\App\Domains\IpLock\Model\IpLock
     */
    public function create(): ?Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create());
    }

    /**
     * @return \App\Domains\IpLock\Model\IpLock
     */
    public function updateEndAt(): Model
    {
        return $this->actionHandle(UpdateEndAt::class);
    }
}
