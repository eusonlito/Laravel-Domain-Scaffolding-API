<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use App\Domains\Shared\Controller\ControllerAbstract as ControllerAbstractShared;
use App\Domains\User\Model\User as Model;
use App\Exceptions\NotFoundException;

abstract class ControllerAbstract extends ControllerAbstractShared
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @return void
     */
    protected function rowAuth(): void
    {
        $this->row = $this->auth;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    protected function row(int $id): void
    {
        $this->row = Model::query()->byId($id)->firstOr(static function () {
            throw new NotFoundException(__('user.validator.not-found'));
        });
    }
}
