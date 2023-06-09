<?php declare(strict_types=1);

namespace App\Domains\Language\Fractal;

use App\Domains\Shared\Fractal\FractalAbstract;
use App\Domains\Language\Model\Language as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\Language\Model\Language $row
     *
     * @return array
     */
    protected function list(Model $row): array
    {
        return $row->only('uuid', 'name', 'code', 'locale');
    }
}
