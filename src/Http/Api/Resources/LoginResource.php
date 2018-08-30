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
 * Interface LoginResource
 * @package CrCms\Passport\Http\Api\Resources
 */
class LoginResource extends Resource
{
    use AuthResourceTrait;
}