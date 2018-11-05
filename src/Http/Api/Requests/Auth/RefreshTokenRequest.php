<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Requests\Auth;

use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Rules\ApplicationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class RefreshTokenRequest
 * @package CrCms\Passport\Http\Api\Requests\Auth
 */
class RefreshTokenRequest extends FormRequest
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
            'app_key' => ['required', app()->make(ApplicationRule::class)],
            'token' => ['required',]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => trans('passport::app.auth.app_key'),
            'token' => trans('passport::app.auth.token'),
        ];
    }
}