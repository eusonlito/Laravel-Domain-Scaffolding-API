<?php declare(strict_types=1);

namespace App\Services\Helper;

class Service
{
    /**
     * @return class@anonymous
     */
    public function message()
    {
        return new class() {
            public function __call(string $name, array $arguments)
            {
            }
        };
    }
}
