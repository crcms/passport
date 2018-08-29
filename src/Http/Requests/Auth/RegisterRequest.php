<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-29 07:25
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Modules\passport\src\Http\Requests\Auth;

use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class RegisterRequest
 * @package CrCms\Modules\passport\src\Http\Requests\Auth
 */
class RegisterRequest extends FormRequest
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
            'app_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')],
            'name' => 'required|string|max:15|unique:passport_users',
            'email' => 'required|string|email|max:50|unique:passport_users',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => trans('passport::app.auth.app_key'),
            'name' => trans('passport::app.auth.name'),
            'email' => trans('passport::app.auth.email'),
            'password' => trans('passport::app.auth.password'),
        ];
    }
}