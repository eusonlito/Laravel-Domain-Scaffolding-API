<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;

class Set extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->set();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function set(): void
    {
        $this->setRow();
        $this->setLanguage();
    }

    /**
     * @return void
     */
    protected function setRow(): void
    {
        app()->bind('user', fn () => $this->row);
    }

    /**
     * @return void
     */
    protected function setLanguage(): void
    {
        $this->factory('Language', $this->row->language)->action()->set();
    }
}
