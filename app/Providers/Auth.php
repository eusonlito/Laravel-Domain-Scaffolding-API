<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\ServiceProvider;
use App\Domains\Shared\Traits\Factory;
use App\Domains\User\Model\User as UserModel;

class Auth extends ServiceProvider
{
    use Factory;

    /**
     * @return void
     */
    public function boot(): void
    {
        AuthFacade::viaRequest('jwt', $this->user(...));
    }

    /**
     * @return ?\App\Domains\User\Model\User
     */
    protected function user(Request $request): ?UserModel
    {
        $this->request = $request;

        return $this->factory('User')->action()->authResolve();
    }
}
