<?php declare(strict_types=1);

namespace App\Domains\IpLock\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Check extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'ip' => ['bail', 'nullable'],
        ];
    }
}
