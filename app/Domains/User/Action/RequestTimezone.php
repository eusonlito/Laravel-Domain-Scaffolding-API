<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;

class RequestTimezone extends ActionAbstract
{
    /**
     * @var ?string
     */
    protected ?string $timezone;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->timezone();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function timezone(): void
    {
        $this->timezone = $this->request->header('x-timezone');
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        if ($this->saveIsValid() === false) {
            return;
        }

        $this->row->timezone = $this->timezone;
        $this->row->save();
    }

    /**
     * @return bool
     */
    protected function saveIsValid(): bool
    {
        return $this->timezone
            && $this->timezone !== $this->row->timezone;
    }
}
