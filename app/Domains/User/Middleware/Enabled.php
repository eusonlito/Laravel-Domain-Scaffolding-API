<?php declare(strict_types=1);

namespace App\Domains\User\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Domains\User\Exception\NotEnabled;

class Enabled extends MiddlewareAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->load($request);

        if (empty($this->auth->enabled)) {
            throw new NotEnabled();
        }

        return $next($request);
    }
}
