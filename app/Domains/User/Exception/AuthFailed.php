<?php declare(strict_types=1);

namespace App\Domains\User\Exception;

class AuthFailed extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var ?string
     */
    protected ?string $status = 'user-auth-failed';
}
