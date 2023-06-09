<?php declare(strict_types=1);

namespace App\Domains\User\Middleware;

use Closure;
use Illuminate\Http\Request as RequestVendor;

class AuthRequest extends MiddlewareAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(RequestVendor $request, Closure $next)
    {
        $this->factory()->action()->authRequest();

        return $next($request);
    }
}
