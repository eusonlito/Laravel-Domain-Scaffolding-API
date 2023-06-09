<?php declare(strict_types=1);

namespace App\Domains\UserToken\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'text' => ['bail', 'required'],
            'device' => ['bail', 'string'],
            'user_id' => ['bail', 'required', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'text.required' => __('user-token-create.validate.text-required'),
            'user_id.required' => __('user-token-create.validate.user_id-required'),
            'user_id.integer' => __('user-token-create.validate.user_id-integer'),
        ];
    }
}
