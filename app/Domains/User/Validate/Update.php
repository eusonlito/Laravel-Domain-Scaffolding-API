<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Update extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'max:20'],
            'email' => ['bail', 'required', 'email:filter'],
            'password' => ['bail', 'min:8'],
            'avatar' => ['bail', 'nullable', 'string'],
            'language' => ['bail', 'required', 'array'],
            'language.uuid' => ['bail', 'required', 'uuid'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => __('user-create.validate.name-required'),
            'name.max' => __('user-create.validate.name-max', ['max' => 20]),
            'email.required' => __('user-create.validate.email-required'),
            'email.filter' => __('user-create.validate.email-filter'),
            'password.required' => __('user-create.validate.password-required'),
            'password.min' => __('user-create.validate.password-min', ['min' => 8]),
            'language.required' => __('user-create.validate.language-required'),
            'language.array' => __('user-create.validate.language-array'),
            'language.uuid.required' => __('user-create.validate.language.uuid-required'),
            'language.uuid.uuid' => __('user-create.validate.language.uuid-uuid'),
        ];
    }
}
