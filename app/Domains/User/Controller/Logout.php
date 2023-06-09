<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

class Logout extends ControllerAbstract
{
    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->action($this->auth)->logout();
    }
}
