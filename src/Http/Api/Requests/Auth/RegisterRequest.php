<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-29 07:25
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Requests\Auth;

use CrCms\Passport\Attributes\ApplicationAttribute;
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
        $defaults = [
            'app_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')->where('status', ApplicationAttribute::STATUS_NORMAL)],
            'password' => 'required|string|min:6',
        ];

        return array_merge($defaults, config('passport.register_rule'));
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        $defaults = [
            'app_key' => trans('passport::app.auth.app_key'),
            'password' => trans('passport::app.auth.password'),
        ];

        $attributes = collect(config('passport.register_rule'))->keys()->mapWithKeys(function ($key) {
            return [$key => trans("passport::app.auth.{$key}")];
        })->toArray();

        return array_merge($defaults, $attributes);
    }
}