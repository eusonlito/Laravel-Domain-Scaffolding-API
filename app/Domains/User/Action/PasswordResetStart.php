<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;
use App\Domains\UserCode\Model\UserCode as UserCodeModel;

class PasswordResetStart extends ActionAbstract
{
    /**
     * @var ?\App\Domains\UserCode\Model\UserCode
     */
    protected ?UserCodeModel $userCode;

    /**
     * @return ?\App\Domains\User\Model\User
     */
    public function handle(): ?Model
    {
        $this->data();
        $this->row();

        if (empty($this->row)) {
            return $this->fail();
        }

        $this->userCode();
        $this->mail();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataEmail();
    }

    /**
     * @return void
     */
    protected function dataEmail(): void
    {
        $this->data['email'] = strtolower($this->data['email']);
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()
            ->byEmail($this->data['email'])
            ->enabled()
            ->first();
    }

    /**
     * @return void
     */
    protected function userCode(): void
    {
        $this->userCode = $this->factory('UserCode')
            ->action($this->codeData())
            ->create();
    }

    /**
     * @return array
     */
    protected function codeData(): array
    {
        return [
            'type' => 'user-password-reset',
            'user_id' => $this->row->id,
        ];
    }

    /**
     * @return void
     */
    protected function mail(): void
    {
        $this->factory()->mail()->passwordResetStart($this->row, $this->userCode);
    }

    /**
     * @return void
     */
    protected function fail(): void
    {
        $this->factory()->mail()->passwordResetFail($this->data);
    }
}
