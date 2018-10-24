<?php

namespace CrCms\Passport\Http\Api\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'object' => ['required'],
            'code' => ['required']
        ];
    }
}