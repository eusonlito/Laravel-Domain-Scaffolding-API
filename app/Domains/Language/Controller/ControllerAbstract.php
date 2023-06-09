<?php declare(strict_types=1);

namespace App\Domains\Language\Controller;

use App\Domains\Language\Model\Language as Model;
use App\Domains\Shared\Controller\ControllerAbstract as ControllerAbstractShared;
use App\Exceptions\NotFoundException;

abstract class ControllerAbstract extends ControllerAbstractShared
{
    /**
     * @var ?\App\Domains\Language\Model\Language
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
            throw new NotFoundException(__('language.validator.not-found'));
        });
    }
}
