<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Illuminate\Support\Facades\Hash;
use Eusonlito\DisposableEmail\Check as DisposableEmailCheck;
use App\Domains\Language\Model\Language as LanguageModel;
use App\Domains\User\Model\User as Model;
use App\Domains\UserCode\Model\UserCode as UserCodeModel;
use App\Services\Image\AvatarRandom as ImageAvatarRandom;

class Create extends ActionAbstract
{
    /**
     * @var \App\Domains\UserCode\Model\UserCode
     */
    protected UserCodeModel $userCode;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->save();
        $this->mail();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataName();
        $this->dataEmail();
        $this->dataPassword();
        $this->dataTimezone();
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
    protected function dataPassword(): void
    {
        $this->data['password'] = Hash::make($this->data['password']);
    }

    /**
     * @return void
     */
    protected function dataTimezone(): void
    {
        $this->data['timezone'] = $this->request->header('x-timezone')
            ?: config('app.timezone');
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
        $this->checkLastByIp();
    }

    /**
     * @return void
     */
    protected function checkEmail(): void
    {
        $this->checkEmailExists();
        $this->checkEmailAlias();
        $this->checkEmailDisposable();
    }

    /**
     * @return void
     */
    protected function checkEmailExists(): void
    {
        if (Model::query()->byEmail($this->data['email'])->exists()) {
            $this->exceptionValidator(__('user-create.validator.email-exists'));
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

        if (Model::query()->byEmailLike($account)->count() > 1) {
            $this->exceptionValidator(__('user-create.validator.email-alias'));
        }
    }

    /**
     * @return void
     */
    protected function checkEmailDisposable(): void
    {
        if (DisposableEmailCheck::email($this->data['email']) === false) {
            $this->exceptionValidator(__('user-create.validator.email-invalid'));
        }
    }

    /**
     * @return void
     */
    protected function checkLastByIp(): void
    {
        if ($this->checkLastByIpCount() > 1) {
            $this->exceptionValidator(__('user-create.validator.ip-count'));
        }
    }

    /**
     * @return int
     */
    protected function checkLastByIpCount(): int
    {
        return UserCodeModel::query()
            ->byIp($this->request->ip())
            ->byCreatedAtAfter(date('Y-m-d H:i:s', strtotime('-5 minutes')))
            ->count();
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
        $this->row = Model::query()->create([
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => $this->data['password'],
            'timezone' => $this->data['timezone'],
            'enabled' => true,
            'language_id' => $this->data['language_id'],
        ])->fresh();
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
        return ImageAvatarRandom::new('male', $this->saveAvatarDataFile(), $this->saveAvatarDataPath())->handle();
    }

    /**
     * @return string
     */
    protected function saveAvatarDataFile(): string
    {
        return $this->row?->avatar
            ?: '/storage/user/'.$this->row->uuid.'/avatar/'.helper()->uniqidReal(10).'.jpg';
    }

    /**
     * @return string
     */
    protected function saveAvatarDataPath(): string
    {
        return public_path();
    }

    /**
     * @return void
     */
    protected function saveUserCode(): void
    {
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

    /**
     * @return void
     */
    protected function saveUserToken(): void
    {
        $this->row->token = $this->factory('UserToken')
            ->action($this->saveUserTokenData())
            ->create()
            ->token;
    }

    /**
     * @return array
     */
    protected function saveUserTokenData(): array
    {
        return [
            'text' => strval($this->row->id),
            'device' => $this->request->header('User-Agent'),
            'user_id' => $this->row->id,
        ];
    }

    /**
     * @return void
     */
    protected function mail(): void
    {
        $this->factory()->mail()->create($this->row, $this->userCode);
    }
}
