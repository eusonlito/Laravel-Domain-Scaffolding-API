<?php declare(strict_types=1);

namespace App\Domains\UserCode\Controller;

use App\Domains\Shared\Controller\ControllerAbstract as ControllerAbstractShared;
use App\Domains\UserCode\Model\UserCode as Model;
use App\Exceptions\NotFoundException;

abstract class ControllerAbstract extends ControllerAbstractShared
{
    /**
     * @var ?\App\Domains\UserCode\Model\UserCode
     */
    protected ?Model $row;

    /**
     * @param int $id
     *
     * @return void
     */
    protected function row(int $id): void
    {
        $this->row = Model::query()->byId($id)->firstOr(static function () {
            throw new NotFoundException(__('user-code.validator.not-found'));
        });
    }
}
