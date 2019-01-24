<?php

namespace CrCms\Passport\Tests\Unit;

use CrCms\Passport\Attributes\ApplicationAttribute;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\DomainModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Tasks\UserApplicationTask;
use CrCms\Passport\Tests\ApplicationTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

/**
 * Class UserApplicationTaskTest
 * @package CrCms\Passport\Tests\Unit
 */
class UserApplicationTaskTest extends TestCase
{
    use ApplicationTrait;

    public function testUserApplications()
    {
        $user = UserModel::first();

        $this->createDomainAndApplication($user);

        $task = new UserApplicationTask();

        $result = $task->handle($user);

        $this->assertInstanceOf(Collection::class,$result);

        $this->assertEquals(DB::table('applications_domains')->count(),$result->count());
    }

    protected function createDomainAndApplication(UserModel $user)
    {
        $domain = DomainModel::create([
            'name' => 'testing'
        ]);

        $user->belongsToManyApplication()->each(function(ApplicationModel $application) use ($domain){
            $application->belongsToManyDomain()->attach($domain->id);
        });

        for($i=0;$i<=5;$i++) {
            /* @var ApplicationModel $app */
            $app = ApplicationModel::create([
                'app_key' => Str::random(9).strval($i),
                'app_secret' => sha1(uniqid()),
                'status' => ApplicationAttribute::STATUS_NORMAL,
                'created_at' => \Illuminate\Support\Carbon::now()->getTimestamp(),
                'updated_at' => \Illuminate\Support\Carbon::now()->getTimestamp(),
            ]);

            $app->belongsToManyDomain()->attach($domain->id);
        }

    }
}