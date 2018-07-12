<?php

namespace CrCms\Passport\Attributes;

use CrCms\AttributeContract\AbstractAttributeContract;

/**
 * Class UserAttribute
 * @package CrCms\Passport\Attributes
 */
class UserAttribute extends AbstractAttributeContract
{
    /**
     * 用户状态 - 未定义
     */
    const STATUS_UNDEFINED = 0;

    /**
     * 用户状态 - 正常
     */
    const STATUS_NORMAL = 1;

    /**
     * 用户状态 - 未认证
     */
    const STATUS_INACTIVATE = 2;

    /**
     * 用户状态 - 禁止
     */
    const STATUS_DISABLE = 3;

    /**
     * 用户状态
     */
    const KEY_STATUS = 'status';

    /**
     * 用户验证类型 - 邮件
     */
    const VERIFY_MAIL = 1;

    /**
     * 用户验证类型 - 手机号
     */
    const VERIFY_TEL = 2;

    /**
     * 用户验证类型
     */
    const KEY_VERIFY = 'verify';

    /**
     * 用户验证状态 - 验证成功
     */
    const VERIFY_STATUS_SUCCESS = 1;

    /**
     * 用户验证状态 - 验证错误
     */
    const VERIFY_STATUS_ERROR = 2;

    /**
     * 用户验证状态 - 未验证
     */
    const VERIFY_STATUS_NO = 3;

    /**
     * 用户验证状态
     */
    const KEY_VERIFY_STATUS = 'verify_status';

    /**
     * 认证类型 - 登录
     */
    const AUTH_TYPE_LOGIN = 1;

    /**
     * 认证类型 - 注册
     */
    const AUTH_TYPE_REGISTER = 2;

    /**
     * 认证类型 - 重置密码
     */
    const AUTH_TYPE_RESET_PASSWORD = 3;

    /**
     * 认证类型 - 注册邮件认证
     */
    const AUTH_TYPE_REGISTER_EMAIL_AUTHENTICATION = 4;

    /**
     * 认证类型 - 忘记密码
     */
    const AUTH_TYPE_FORGET_PASSWORD = 5;

    /**
     * 用户验证状态 - 验证成功
     */
    const AUTH_STATUS_SUCCESS = 1;

    /**
     * 用户验证状态 - 验证错误
     */
    const AUTH_STATUS_ERROR = 2;

    /**
     * 用户验证状态 - 未验证
     */
    const AUTH_STATUS_DEFAULT = 3;

    /**
     * 认证类型
     */
    const KEY_AUTH_TYPE = 'auth_type';

    /**
     * @return array
     */
    protected function attributes(): array
    {
        return [

            static::KEY_STATUS => [
                static::STATUS_UNDEFINED => trans('passport::app.status.undefined'),
                static::STATUS_NORMAL => trans('passport::app.status.normal'),
                static::STATUS_INACTIVATE => trans('passport::app.status.inactivate'),
                static::STATUS_DISABLE => trans('passport::app.status.disable')
            ],

            static::KEY_VERIFY => [
                static::VERIFY_MAIL => trans('passport::app.verify.mail'),
                static::VERIFY_TEL => trans('passport::app.verify.tel')
            ],

            static::KEY_VERIFY_STATUS => [
                static::VERIFY_STATUS_SUCCESS => trans('passport::app.verify_status.success'),
                static::VERIFY_STATUS_ERROR => trans('passport::app.verify_status.error'),
                static::VERIFY_STATUS_NO => trans('passport::app.verify_status.no'),
            ],

            static::KEY_AUTH_TYPE => [
                static::AUTH_TYPE_LOGIN => trans('passport::app.auth_type.login'),
                static::AUTH_TYPE_REGISTER => trans('passport::app.auth_type.register'),
            ]
        ];
    }
}