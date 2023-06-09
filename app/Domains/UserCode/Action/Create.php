<?php declare(strict_types=1);

namespace App\Domains\UserCode\Action;

use App\Domains\User\Model\User as UserModel;
use App\Domains\UserCode\Model\UserCode as Model;

class Create extends ActionAbstract
{
    /**
     * @return \App\Domains\UserCode\Model\UserCode
     */
    public function handle(): Model
    {
        $this->user();
        $this->data();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function user(): void
    {
        $this->user = UserModel::query()
            ->select('id')
            ->byId($this->data['user_id'])
            ->firstOrFail();
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataCode();
        $this->dataIp();
    }

    /**
     * @return void
     */
    protected function dataCode(): void
    {
        $this->data['code'] = helper()->uniqidReal(rand(6, 8), true, 'upper');
    }

    /**
     * @return void
     */
    protected function dataIp(): void
    {
        $this->data['ip'] = $this->request->ip();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->savePrevious();
        $this->saveRow();
    }

    /**
     * @return void
     */
    protected function savePrevious(): void
    {
        Model::query()
            ->byUserId($this->user->id)
            ->available()
            ->update(['canceled_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * @return void
     */
    protected function saveRow(): void
    {
        $this->row = Model::query()->create([
            'type' => $this->data['type'],
            'code' => $this->data['code'],
            'ip' => $this->data['ip'],
            'user_id' => $this->user->id,
        ])->fresh();
    }
}
