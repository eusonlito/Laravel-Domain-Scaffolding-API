<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Http\JsonResponse;

class Profile extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return $this->json($this->factory()->fractal('profile', $this->auth));
    }
}