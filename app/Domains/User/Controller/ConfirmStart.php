<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

class ConfirmStart extends ControllerAbstract
{
    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->action($this->auth)->confirmStart();
    }
}
