<?php declare(strict_types=1);

namespace App\Domains\UserToken\Action;

use App\Domains\SharedApp\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\UserToken\Model\UserToken as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\UserToken\Model\UserToken
     */
    protected ?Model $row;
}
