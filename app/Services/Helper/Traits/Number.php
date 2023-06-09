<?php declare(strict_types=1);

namespace App\Services\Helper\Traits;

trait Number
{
    /**
     * @param ?float $value
     * @param int $decimals = 2
     * @param ?string $default = '-'
     *
     * @return ?string
     */
    public function number(?float $value, int $decimals = 2, ?string $default = '-'): ?string
    {
        if ($value === null) {
            return $default;
        }

        return number_format($value, $decimals, ',', '.');
    }

    /**
     * @param ?float $value
     * @param int $decimals = 2
     * @param string $symbol = '€'
     *
     * @return ?string
     */
    public function money(?float $value, int $decimals = 2, string $symbol = '€'): ?string
    {
        return $this->number($value, $decimals).$symbol;
    }
}
