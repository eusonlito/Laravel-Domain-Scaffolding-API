<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class PasswordResetFinish extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email:filter'],
            'password' => ['bail', 'required', 'min:8'],
            'code' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => __('user-password-reset-finish.validate.email-required'),
            'email.filter' => __('user-password-reset-finish.validate.email-filter'),
            'password.required' => __('user-user-password-reset-finish.validate.password-required'),
            'password.min' => __('user-user-password-reset-finish.validate.password-min', ['min' => 8]),
            'code.required' => __('user-password-reset-finish.validate.code-required'),
        ];
    }
}
