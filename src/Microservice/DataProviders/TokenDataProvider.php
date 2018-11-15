<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Microservice\DataProviders;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Rules\ApplicationRule;

/**
 * Class TokenDataProvider
 * @package CrCms\Passport\Http\Api\Requests\Auth
 */
class TokenDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', app(ApplicationRule::class)],
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