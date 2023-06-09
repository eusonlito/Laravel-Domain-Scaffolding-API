<?php declare(strict_types=1);

namespace App\Domains\UserToken\Exception;

class Expired extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 498;

    /**
     * @var ?string
     */
    protected ?string $status = 'user-token-expired';
}
