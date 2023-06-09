<?php declare(strict_types=1);

namespace App\Domains\UserToken\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\UserToken\Model\Builder\UserToken as Builder;
use App\Domains\UserToken\Model\Collection\UserToken as Collection;
use App\Domains\SharedApp\Model\ModelAbstract;
use App\Domains\User\Model\User as UserModel;

class UserToken extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'user_token';

    /**
     * @const string
     */
    public const TABLE = 'user_token';

    /**
     * @const string
     */
    public const FOREIGN = 'user_token_id';

    /**
     * @param array $models
     *
     * @return \App\Domains\UserToken\Model\Collection\UserToken
     */
    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \App\Domains\UserToken\Model\Builder\UserToken
     */
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, UserModel::FOREIGN);
    }
}
