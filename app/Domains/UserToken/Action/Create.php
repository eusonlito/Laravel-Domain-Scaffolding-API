<?php declare(strict_types=1);

namespace App\Domains\UserToken\Action;

use App\Domains\User\Model\User as UserModel;
use App\Domains\UserToken\Model\UserToken as Model;
use App\Services\Jwt\Token as JwtToken;

class Create extends ActionAbstract
{
    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function handle(): Model
    {
        $this->data();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataUserId();
        $this->dataExpiredAt();
        $this->dataToken();
        $this->dataHash();
    }

    /**
     * @return void
     */
    protected function dataUserId(): void
    {
        $this->data['user_id'] = UserModel::query()
            ->select('id')
            ->byId($this->data['user_id'])
            ->valueOrFail('id');
    }

    /**
     * @return void
     */
    protected function dataExpiredAt(): void
    {
        $this->data['expired_at'] = date('Y-m-d H:i:s', time() + config('jwt.ttl'));
    }

    /**
     * @return void
     */
    protected function dataToken(): void
    {
        $this->data['token'] = JwtToken::get(config('app.url'), $this->data['text'], $this->data['expired_at']);
    }

    /**
     * @return void
     */
    protected function dataHash(): void
    {
        $this->data['hash'] = hash('sha256', $this->data['token']);
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveRow();
        $this->saveToken();
    }

    /**
     * @return void
     */
    protected function saveRow(): void
    {
        $this->row = Model::query()->create([
            'hash' => $this->data['hash'],
            'device' => $this->data['device'],
            'expired_at' => $this->data['expired_at'],
            'user_id' => $this->data['user_id'],
        ])->fresh();
    }

    /**
     * @return void
     */
    protected function saveToken(): void
    {
        $this->row->token = $this->data['token'];
    }
}
