<?php

namespace CrCms\Passport\DataProviders\User;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Validation\Rule;

/**
 * Class StoreDataProvider
 * @package CrCms\Passport\DataProviders\User
 */
class StoreDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', Rule::exists((new ApplicationModel)->getTable(), 'app_key')],
            'name' => 'required|string|max:15|unique:passport_users',
            'mobile' => 'required|string|max:11|min:11|unique:passport_users',
            'password' => 'required|min:6',
            'status' => ['required', 'integer', Rule::in(array_keys(UserAttribute::getStaticTransform(UserAttribute::KEY_STATUS)))]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => '应用ID',
            'name' => '用户名',
            'mobile' => '手机号',
            'password' => '密码',
            'status' => '状态'
        ];
    }
}