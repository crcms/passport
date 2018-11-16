<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-29 07:25
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Microservice\DataProviders;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Rules\ApplicationRule;

/**
 * Class RegisterRequest
 * @package CrCms\Modules\passport\src\Http\Requests\Auth
 */
class RegisterDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $defaults = [
            'app_key' => ['required', app(ApplicationRule::class)],
            'password' => 'required|string|min:6',
            'ip' => ['ip'],
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