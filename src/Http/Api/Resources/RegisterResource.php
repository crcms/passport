<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-30 18:56
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */


namespace CrCms\Passport\Http\Api\Resources;

use CrCms\Foundation\App\Http\Resources\Resource;
use CrCms\Passport\Http\Api\Resources\Traits\AuthResourceTrait;

/**
 * Class RegisterResource
 * @package CrCms\Passport\Http\Api\Resources
 */
class RegisterResource extends Resource
{
    use AuthResourceTrait;
}