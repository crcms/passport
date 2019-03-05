<?php

namespace CrCms\Passport\Tests;


use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Handlers\User\ListHandler;
use CrCms\Passport\Handlers\User\ShowHandler;
use CrCms\Passport\Handlers\User\StoreHandler;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use ApplicationTrait;

    public function testStoreHandler()
    {
        $app = ApplicationModel::create([
            'app_key' => Str::random(10)
        ]);

        $name = Str::random(10);
        $data = [
            'app_key' => $app->app_key,
            'name' => $name,
            'email' => Str::random(10).'@gmail.com',
            'mobile' => '121'.mt_rand(1000, 9999).mt_rand(1000, 9999),
            'password' => 'adfdasfsda',
        ];

        $handler = new StoreHandler();
        $user = $handler->handle(new DataProvider($data));

        $this->assertInstanceOf(UserModel::class, $user);
        $this->assertEquals($name, $user->name);

        $result = DB::table('passport_user_applications')->where('user_id', $user->id)->get();

        $this->assertEquals(1, $result->count());
        $this->assertEquals($app->app_key, $result->get(0)->app_key);

        return $user;
    }

    /**
     * testShowHandler
     *
     * @depends testStoreHandler
     *
     * @param UserModel $user
     * @return void
     */
    public function testShowHandler(UserModel $user)
    {
        $appUser = DB::table('passport_user_applications')->where('user_id', $user->id)->get();

        $appKey = $appUser->get(0)->app_key;

        $handler = new ShowHandler();
        $result = $handler->handle(new DataProvider([
            'app_key' => $appKey,
            'id' => $user->id,
        ]));

        $this->assertInstanceOf(UserModel::class, $result);
        $this->assertEquals($user->id, $result->id);

        return $appKey;
    }

    /**
     * testListHandler
     *
     * @depends testShowHandler
     *
     * @return void
     */
    public function testListHandler(string $appKey)
    {
        $listHandler = new ListHandler();
        $result = $listHandler->handle(new DataProvider([
            'app_key' => $appKey,
            'page' => 1,
        ]));

        $this->assertInstanceOf(Paginator::class,$result);
    }

}
