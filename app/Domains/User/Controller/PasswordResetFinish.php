<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

class PasswordResetFinish extends ControllerAbstract
{
    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->action()->passwordResetFinish();
    }
}
