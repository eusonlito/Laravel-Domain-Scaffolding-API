<?php declare(strict_types=1);

namespace App\Domains\Language\Controller;

use Illuminate\Http\JsonResponse;
use App\Domains\Language\Model\Language as Model;
use App\Domains\Language\Model\Collection\Language as Collection;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return $this->json($this->factory()->fractal('list', $this->list()));
    }

    /**
     * @return \App\Domains\Language\Model\Collection\Language
     */
    protected function list(): Collection
    {
        return Model::query()->list()->get();
    }
}
