<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $domainId = DB::table('passport_domains')->insertGetId([
            'name' => 'default',
            'remark' => 'remark',
        ]);

        $appKey = time();

        $appId = DB::table('passport_applications')->insertGetId([
            'app_key' => $appKey,
            'app_secret' => sha1('123456'),
            'status' => \CrCms\Passport\Attributes\ApplicationAttribute::STATUS_NORMAL,
            'created_at' => \Illuminate\Support\Carbon::now()->getTimestamp(),
            'updated_at' => \Illuminate\Support\Carbon::now()->getTimestamp(),
        ]);

        $userId = DB::table('passport_users')->insertGetId([
            'name' => 'testing',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'status' => 1,
            'created_at' => \Illuminate\Support\Carbon::now()->getTimestamp(),
            'updated_at' => \Illuminate\Support\Carbon::now()->getTimestamp(),
        ]);

        DB::table('passport_applications_domains')->insert([
            'app_key' => $appKey,
            'domain_id' => $domainId,
        ]);

        DB::table('passport_user_applications')->insert([
            'user_id' => $userId,
            'app_key' => $appKey,
        ]);
    }
}