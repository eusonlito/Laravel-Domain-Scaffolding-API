<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;

class AuthRefresh extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveUserToken();
    }

    /**
     * @return void
     */
    protected function saveUserToken(): void
    {
        $this->row->token = $this->factory('UserToken')
            ->action($this->saveUserTokenData())
            ->refresh()
            ->token;
    }

    /**
     * @return array
     */
    protected function saveUserTokenData(): array
    {
        return [
            'token' => $this->request->bearerToken(),
            'text' => strval($this->row->id),
            'device' => $this->request->header('User-Agent'),
            'user_id' => $this->row->id,
        ];
    }
}
