<?php declare(strict_types=1);

namespace App\Domains\UserToken\Action;

use App\Domains\UserToken\Exception\Expired as ExpiredException;
use App\Domains\UserToken\Exception\NotFound as NotFoundException;
use App\Domains\UserToken\Model\UserToken as Model;

class Check extends ActionAbstract
{
    /**
     * @return \App\Domains\UserToken\Model\UserToken
     */
    public function handle(): Model
    {
        $this->data();
        $this->row();
        $this->check();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataHash();
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
    protected function row(): void
    {
        $this->row = Model::query()
            ->byHash($this->data['hash'])
            ->firstOr(static fn () => throw new NotFoundException());
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkExpiredAt();
    }

    /**
     * @return void
     */
    protected function checkExpiredAt(): void
    {
        if ($this->row->expired_at < date('Y-m-d H:i:s')) {
            throw new ExpiredException();
        }
    }
}
