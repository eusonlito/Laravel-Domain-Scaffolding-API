<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;

class ConfirmFinish extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->check();
        $this->save();

        return $this->row;
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
            'type' => 'user-confirm',
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
        $this->row->confirmed_at ??= date('Y-m-d H:i:s');
        $this->row->save();
    }
}
