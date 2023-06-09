<?php declare(strict_types=1);

namespace App\Domains\Configuration\Controller;

use Illuminate\Http\Response;

class Cache extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        return response(config('cache.version'));
    }
}
