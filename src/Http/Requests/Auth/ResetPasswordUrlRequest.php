<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-23 20:52
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ResetPasswordUrlRequest
 * @package CrCms\Modules\user\src\Http\Requests\Auth
 */
class ResetPasswordUrlRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'token' => ['required']
        ];
    }
}