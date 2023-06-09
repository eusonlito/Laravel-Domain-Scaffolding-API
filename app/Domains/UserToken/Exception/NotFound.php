<?php declare(strict_types=1);

namespace App\Domains\UserToken\Exception;

class NotFound extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @var ?string
     */
    protected ?string $status = 'user-token-not-found';
}
