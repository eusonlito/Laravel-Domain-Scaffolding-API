<?php declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as KernelVendor;
use App\Domains\Configuration\Middleware\Request as ConfigurationRequest;
use App\Domains\IpLock\Middleware\Check as IpLockCheck;
use App\Domains\Language\Middleware\Request as LanguageRequest;
use App\Domains\User\Middleware\AuthRequest as UserAuthRequest;
use App\Domains\User\Middleware\Confirmed as UserConfirmed;
use App\Domains\User\Middleware\Enabled as UserEnabled;
use App\Domains\User\Middleware\Timezone as UserTimezone;
use App\Http\Middleware\RequestLogger;
use App\Http\Middleware\TrustProxies;

class Kernel extends KernelVendor
{
    /**
     * @var array<int, string>
     */
    protected $middleware = [
        TrustProxies::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        RequestLogger::class,
        IpLockCheck::class,
        ConfigurationRequest::class,
        LanguageRequest::class,
    ];

    /**
     * @var array<string, array<int, string>>
     */
    protected $middlewareGroups = [
        'user-auth' => [
            UserAuthRequest::class,
            UserTimezone::class,
        ],

        'user-auth-enabled' => [
            UserAuthRequest::class,
            UserTimezone::class,
            UserEnabled::class,
        ],

        'user-auth-enabled-confirmed' => [
            UserAuthRequest::class,
            UserTimezone::class,
            UserEnabled::class,
            UserConfirmed::class,
        ],
    ];

    /**
     * @var array<string, string>
     */
    protected $middlewareAliases = [
        'user.confirmed' => UserConfirmed::class,
        'user.enabled' => UserEnabled::class,
    ];
}
