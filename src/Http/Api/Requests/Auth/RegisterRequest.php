<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-29 07:25
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Requests\Auth;

use CrCms\Passport\Rules\ApplicationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'app_key' => ['required', app()->make(ApplicationRule::class)],
            'password' => 'required|string|min:6',
        ];

        return array_merge($defaults, config('passport.register_rules'));
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

        $attributes = collect(config('passport.register_rules'))->keys()->mapWithKeys(function ($key) {
            return [$key => trans("passport::app.auth.{$key}")];
        })->toArray();

        return array_merge($defaults, $attributes);
    }
}