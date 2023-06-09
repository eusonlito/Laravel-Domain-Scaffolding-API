<?php declare(strict_types=1);

namespace App\Domains\UserToken\Model\Builder;

use App\Domains\SharedApp\Model\Builder\BuilderAbstract;

class UserToken extends BuilderAbstract
{
    /**
     * @param string $hash
     *
     * @return self
     */
    public function byHash(string $hash): self
    {
        return $this->where('hash', $hash);
    }
}
