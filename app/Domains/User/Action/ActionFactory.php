<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\Shared\Action\ActionFactoryAbstract;
use App\Domains\User\Model\User as Model;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authCredentials(): Model
    {
        return $this->actionHandle(AuthCredentials::class, $this->validate()->authCredentials());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authRefresh(): Model
    {
        return $this->actionHandle(AuthRefresh::class);
    }

    /**
     * @return void
     */
    public function authRequest(): void
    {
        $this->actionHandle(AuthRequest::class);
    }

    /**
     * @return ?\App\Domains\User\Model\User
     */
    public function authResolve(): ?Model
    {
        return $this->actionHandle(AuthResolve::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function confirmFinish(): Model
    {
        return $this->actionHandle(ConfirmFinish::class, $this->validate()->confirmFinish());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function confirmStart(): Model
    {
        return $this->actionHandle(ConfirmStart::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function create(): Model
    {
        return $this->actionHandleTransaction(Create::class, $this->validate()->create());
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->actionHandle(Logout::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function passwordResetFinish(): Model
    {
        return $this->actionHandle(PasswordResetFinish::class, $this->validate()->passwordResetFinish());
    }

    /**
     * @return ?\App\Domains\User\Model\User
     */
    public function passwordResetStart(): ?Model
    {
        return $this->actionHandle(PasswordResetStart::class, $this->validate()->passwordResetStart());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function requestTimezone(): Model
    {
        return $this->actionHandle(RequestTimezone::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function set(): Model
    {
        return $this->actionHandle(Set::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function update(): Model
    {
        return $this->actionHandleTransaction(Update::class, $this->validate()->update());
    }
}
