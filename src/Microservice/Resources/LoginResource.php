<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-30 18:56
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Microservice\Resources;

use CrCms\Foundation\Resources\Resource;
use CrCms\Passport\Microservice\Resources\Traits\AuthResourceTrait;

/**
 * Class LoginResource
 * @package CrCms\Passport\Microservice\Resources
 */
class LoginResource extends Resource
{
    use AuthResourceTrait;
}