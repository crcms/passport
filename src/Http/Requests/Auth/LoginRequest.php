<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Requests\Auth;

use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package CrCms\Passport\Http\Requests\Auth
 */
class LoginRequest extends FormRequest
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
            'name' => ['required'],
            'password' => ['required'],
            'app_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('passport::app.auth.name'),
            'password' => trans('passport::app.auth.password'),
            'app_key' => trans('passport::app.auth.app_key'),
        ];
    }
}