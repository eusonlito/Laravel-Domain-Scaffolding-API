<?php declare(strict_types=1);

namespace App\Domains\User\Action;

class Logout extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->userToken();
    }

    /**
     * @return void
     */
    protected function userToken(): void
    {
        $this->factory('UserToken')->action($this->userTokenData())->expire();
    }

    /**
     * @return array
     */
    protected function userTokenData(): array
    {
        return [
            'token' => $this->request->bearerToken(),
        ];
    }
}
