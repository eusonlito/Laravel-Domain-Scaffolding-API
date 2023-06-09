<?php declare(strict_types=1);

namespace App\Domains\UserCode\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['bail', 'required', 'string'],
            'user_id' => ['bail', 'required', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'type.required' => __('user-code-start.validate.type-required'),
            'user_id.required' => __('user-code-start.validate.user_id-required'),
            'user_id.integer' => __('user-code-start.validate.user_id-integer'),
        ];
    }
}
