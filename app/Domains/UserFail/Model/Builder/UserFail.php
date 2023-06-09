<?php declare(strict_types=1);

namespace App\Domains\UserFail\Model\Builder;

use App\Domains\SharedApp\Model\Builder\BuilderAbstract;

class UserFail extends BuilderAbstract
{
    /**
     * @param string $ip
     *
     * @return self
     */
    public function byIp(string $ip): self
    {
        return $this->where('ip', $ip);
    }

    /**
     * @return self
     */
    public function withUser(): self
    {
        return $this->with('user');
    }
}
