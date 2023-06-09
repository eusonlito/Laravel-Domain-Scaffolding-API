<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Illuminate\Support\Facades\Hash;
use App\Domains\User\Model\User as Model;

class PasswordResetFinish extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->data();
        $this->row();
        $this->check();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataEmail();
        $this->dataPassword();
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
    protected function dataPassword(): void
    {
        $this->data['password'] = Hash::make($this->data['password']);
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()
            ->byEmail($this->data['email'])
            ->enabled()
            ->firstOr($this->fail(...));
    }

    /**
     * @return void
     */
    protected function fail(): void
    {
        $this->factory('UserFail')->action($this->failData())->create();

        $this->exceptionValidator(__('user-password-reset-finish.validator.email-invalid'));
    }

    /**
     * @return array
     */
    protected function failData(): array
    {
        return [
            'type' => 'user-password-reset',
            'text' => $this->data['email'],
            'ip' => $this->request->ip(),
        ];
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkCode();
    }

    /**
     * @return void
     */
    protected function checkCode(): void
    {
        $this->factory('UserCode')->action($this->checkCodeData())->check();
    }

    /**
     * @return array
     */
    protected function checkCodeData(): array
    {
        return [
            'type' => 'user-password-reset',
            'code' => $this->data['code'],
            'user_id' => $this->row->id,
            'finish' => true,
        ];
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row->password = $this->data['password'];
        $this->row->save();
    }
}
