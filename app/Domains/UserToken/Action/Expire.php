<?php declare(strict_types=1);

namespace App\Domains\UserToken\Action;

use App\Domains\UserToken\Model\UserToken as Model;

class Expire extends ActionAbstract
{
    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function handle(): Model
    {
        $this->data();
        $this->row();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataHash();
        $this->dataExpiredAt();
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
    protected function dataExpiredAt(): void
    {
        $this->data['expired_at'] = date('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()
            ->byHash($this->data['hash'])
            ->firstOrFail();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        if ($this->row->expired_at <= $this->data['expired_at']) {
            return;
        }

        $this->row->expired_at = $this->data['expired_at'];
        $this->row->save();
    }
}
