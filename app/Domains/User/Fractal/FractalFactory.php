<?php declare(strict_types=1);

namespace App\Domains\User\Fractal;

use App\Domains\User\Model\User as Model;
use App\Domains\Shared\Fractal\FractalAbstract;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\User\Model\User $row
     *
     * @return array
     */
    protected function auth(Model $row): array
    {
        return [
            'uuid' => $row->uuid,
            'name' => $row->name,
            'email' => $row->email,
            'avatar' => $row->avatar,
            'confirmed_at' => $row->confirmed_at,
            'token' => $row->token,
            'language' => [
                'uuid' => $row->language->uuid,
                'name' => $row->language->name,
                'locale' => $row->language->locale,
            ],
        ];
    }

    /**
     * @param \App\Domains\User\Model\User $row
     *
     * @return array
     */
    protected function profile(Model $row): array
    {
        return [
            'uuid' => $row->uuid,
            'name' => $row->name,
            'email' => $row->email,
            'avatar' => $row->avatar,
            'confirmed_at' => $row->confirmed_at,
            'language' => [
                'uuid' => $row->language->uuid,
                'name' => $row->language->name,
                'locale' => $row->language->locale,
            ],
        ];
    }
}
