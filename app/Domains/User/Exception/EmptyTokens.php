<?php declare(strict_types=1);

namespace App\Domains\User\Exception;

class EmptyTokens extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 422;

    /**
     * @var ?string
     */
    protected ?string $status = 'user-empty-tokens';
}
