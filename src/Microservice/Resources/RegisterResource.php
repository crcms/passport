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
 * Class RegisterResource
 * @package CrCms\Passport\Microservice\Resources
 */
class RegisterResource extends Resource
{
    use AuthResourceTrait;
}