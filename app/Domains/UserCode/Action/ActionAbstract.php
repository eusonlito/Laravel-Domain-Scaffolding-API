<?php declare(strict_types=1);

namespace App\Domains\UserCode\Action;

use App\Domains\SharedApp\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\User\Model\User as UserModel;
use App\Domains\UserCode\Model\UserCode as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\UserCode\Model\UserCode
     */
    protected ?Model $row;

    /**
     * @var \App\Domains\User\Model\User
     */
    protected UserModel $user;
}
