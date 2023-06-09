<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Illuminate\Support\Facades\Hash;
use Eusonlito\DisposableEmail\Check as DisposableEmailCheck;
use App\Domains\Language\Model\Language as LanguageModel;
use App\Domains\User\Model\User as Model;
use App\Services\Image\Base64 as ImageBase64;

class Update extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataName();
        $this->dataEmail();
        $this->dataConfirmedAt();
        $this->dataPassword();
        $this->dataLanguageId();
    }

    /**
     * @return void
     */
    protected function dataName(): void
    {
        $this->data['name'] = trim($this->data['name']);
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
    protected function dataConfirmedAt(): void
    {
        if ($this->data['email'] === $this->row->email) {
            $this->data['confirmed_at'] = $this->row->confirmed_at;
        } else {
            $this->data['confirmed_at'] = null;
        }
    }

    /**
     * @return void
     */
    protected function dataPassword(): void
    {
        if ($this->data['password']) {
            $this->data['password'] = Hash::make($this->data['password']);
        } else {
            $this->data['password'] = $this->row->password;
        }
    }

    /**
     * @return void
     */
    protected function dataLanguageId(): void
    {
        $this->data['language_id'] = LanguageModel::query()
            ->byUuid($this->data['language']['uuid'])
            ->valueOrFail('id');
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkEmail();
        $this->checkEmailAlias();
        $this->checkEmailDisposable();
    }

    /**
     * @return void
     */
    protected function checkEmail(): void
    {
        if (Model::query()->byIdNot($this->row->id)->byEmail($this->data['email'])->exists()) {
            $this->exceptionValidator(__('user-update.validator.email-exists'));
        }
    }

    /**
     * @return void
     */
    protected function checkEmailAlias(): void
    {
        if (str_contains($this->data['email'], '+') === false) {
            return;
        }

        $account = preg_replace('/\+[^@]+@/', '+%@', $this->data['email']);

        if (Model::query()->byIdNot($this->row->id)->byEmailLike($account)->count() > 1) {
            $this->exceptionValidator(__('user-update.validator.email-alias'));
        }
    }

    /**
     * @return void
     */
    protected function checkEmailDisposable(): void
    {
        if (DisposableEmailCheck::email($this->data['email']) === false) {
            $this->exceptionValidator(__('user-update.validator.email-invalid'));
        }
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveRow();
        $this->saveAvatar();
        $this->saveUserCode();
    }

    /**
     * @return void
     */
    protected function saveRow(): void
    {
        $this->row->name = $this->data['name'];
        $this->row->email = $this->data['email'];
        $this->row->password = $this->data['password'];
        $this->row->confirmed_at = $this->data['confirmed_at'];
        $this->row->language_id = $this->data['language_id'];

        $this->row->save();
    }

    /**
     * @return void
     */
    protected function saveAvatar(): void
    {
        $this->row->avatar = $this->saveAvatarData();
        $this->row->save();
    }

    /**
     * @return string
     */
    protected function saveAvatarData(): string
    {
        if ($this->data['avatar']) {
            return $this->saveAvatarDataBase64();
        }

        return $this->saveAvatarDataRow();
    }

    /**
     * @return string
     */
    protected function saveAvatarDataBase64(): string
    {
        return ImageBase64::new($this->data['avatar'], $this->saveAvatarDataFile(), $this->saveAvatarDataPath())->handle();
    }

    /**
     * @return string
     */
    protected function saveAvatarDataFile(): string
    {
        return '/storage/user/'.$this->row->uuid.'/avatar/'.helper()->uniqidReal(10).'.jpg';
    }

    /**
     * @return string
     */
    protected function saveAvatarDataPath(): string
    {
        return public_path();
    }

    /**
     * @return string
     */
    protected function saveAvatarDataRow(): string
    {
        return $this->row->avatar;
    }

    /**
     * @return void
     */
    protected function saveUserCode(): void
    {
        if ($this->row->confirmed_at) {
            return;
        }

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
}
