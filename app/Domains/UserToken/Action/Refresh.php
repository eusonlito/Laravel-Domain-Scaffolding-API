<?php declare(strict_types=1);

namespace App\Domains\UserToken\Action;

use App\Domains\UserToken\Exception\NotFound as NotFoundException;
use App\Domains\UserToken\Model\UserToken as Model;

class Refresh extends ActionAbstract
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
    protected function save(): void
    {
        if ($this->row->expired_at > date('Y-m-d H:i:s')) {
            $this->saveRowActive();
        } else {
            $this->saveRowExpired();
        }
    }

    /**
     * @return void
     */
    protected function saveRowActive(): void
    {
        $this->saveRowActiveToken();
    }

    /**
     * @return void
     */
    protected function saveRowActiveToken(): void
    {
        $this->row->token = $this->data['token'];
    }

    /**
     * @return void
     */
    protected function saveRowExpired(): void
    {
        $this->row = $this->factory()
            ->action($this->saveRowExpiredData())
            ->create();
    }

    /**
     * @return array
     */
    protected function saveRowExpiredData(): array
    {
        return [
            'text' => $this->data['text'],
            'device' => $this->data['device'],
            'user_id' => $this->row->user->id,
        ];
    }
}
