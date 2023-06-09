<?php declare(strict_types=1);

namespace App\Domains\UserToken\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Check extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'token.required' => __('user-token-check.validate.token-required'),
        ];
    }
}
