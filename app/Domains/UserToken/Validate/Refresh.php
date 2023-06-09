<?php declare(strict_types=1);

namespace App\Domains\UserToken\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Refresh extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['bail', 'required'],
            'device' => ['bail', 'string'],
            'text' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'token.required' => __('user-token-refresh.validate.token-required'),
            'text.required' => __('user-token-refresh.validate.text-required'),
        ];
    }
}
