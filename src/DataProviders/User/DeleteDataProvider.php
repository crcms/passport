<?php

namespace CrCms\Passport\DataProviders\User;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Validation\Rule;

class DeleteDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', Rule::exists(ApplicationModel::getTable(), 'app_key')],
            'id' => 'required|exists:passport_users',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => '应用ID',
            'id' => '用户ID',
        ];
    }
}