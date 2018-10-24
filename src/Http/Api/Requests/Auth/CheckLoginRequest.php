<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CheckLoginRequest
 * @package CrCms\Passport\Http\Api\Requests\Auth
 */
class CheckLoginRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['required',]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'token' => trans('passport::app.auth.token'),
        ];
    }
}