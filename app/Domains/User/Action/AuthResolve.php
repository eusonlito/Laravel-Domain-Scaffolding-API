<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Throwable;
use Illuminate\Support\Facades\Auth;
use App\Domains\User\Model\User as Model;
use App\Domains\UserToken\Model\UserToken as UserTokenModel;

class AuthResolve extends ActionAbstract
{
    /**
     * @var ?\App\Domains\UserToken\Model\UserToken
     */
    protected ?UserTokenModel $userToken = null;

    /**
     * @return ?\App\Domains\User\Model\User
     */
    public function handle(): ?Model
    {
        $this->userToken();
        $this->row();
        $this->auth();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function userToken(): void
    {
        if (empty($this->request->bearerToken())) {
            return;
        }

        try {
            $this->userToken = $this->factory('UserToken')->action($this->userTokenData())->check();
        } catch (Throwable $e) {
        }
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

    /**
     * @return void
     */
    protected function row(): void
    {
        if ($this->userToken) {
            $this->row = $this->userToken->user;
        }
    }

    /**
     * @return void
     */
    protected function auth(): void
    {
        if ($this->row) {
            Auth::setUser($this->auth = $this->row);
        }
    }
}
