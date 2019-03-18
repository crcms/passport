<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-03-18 23:06
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Tests\User;

use CrCms\Passport\Controllers\UserController;
use CrCms\Passport\DataProviders\User\ShowDataProvider;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Tests\ApplicationTrait;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use ApplicationTrait;

    public function testShow()
    {
        static::$app->bind('request',function(){
            return \Mockery::mock(Request::class);
        });

        $controller = new UserController();
        $result = $controller->show(new ShowDataProvider([
            'id' => 1,
            'app_key' => '1552918360'
        ]));
        dd($result);
    }

}