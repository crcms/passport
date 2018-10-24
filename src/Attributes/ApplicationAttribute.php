<?php

namespace CrCms\Passport\Attributes;

/**
 * Class ApplicationAttribute
 * @package CrCms\Modules\passport\src\Attributes
 */
class ApplicationAttribute extends AbstractAttribute
{
    /**
     * 应用状态 - 正常
     */
    const STATUS_NORMAL = 1;

    /**
     * 应用状态 - 禁止
     */
    const STATUS_DISABLE = 2;

    /**
     * 应用状态
     */
    const KEY_STATUS = 'status';

    /**
     * @return array
     */
    protected function attributes(): array
    {
        return [

            static::KEY_STATUS => [
                static::STATUS_NORMAL => trans('passport::app.status.normal'),
                static::STATUS_DISABLE => trans('passport::app.status.disable')
            ],

        ];
    }
}