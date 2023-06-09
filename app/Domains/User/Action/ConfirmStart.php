<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;
use App\Domains\UserCode\Model\UserCode as UserCodeModel;

class ConfirmStart extends ActionAbstract
{
    /**
     * @var \App\Domains\UserCode\Model\UserCode
     */
    protected UserCodeModel $userCode;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->save();
        $this->mail();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveUserCode();
    }

    /**
     * @return void
     */
    protected function saveUserCode(): void
    {
        $this->userCode = $this->factory('UserCode')
            ->action($this->saveUserCodeData())
            ->create();
    }

    /**
     * @return array
     */
    protected function saveUserCodeData(): array
    {
        return [
            'type' => 'user-confirm',
            'user_id' => $this->row->id,
        ];
    }

    /**
     * @return void
     */
    protected function mail(): void
    {
        $this->factory()->mail()->confirmStart($this->row, $this->userCode);
    }
}
