<?php

$config = require __DIR__.'/../config/config.php';
$login = array_keys($config['login_rules']);

// init app
$app = new \Illuminate\Container\Container();
\Illuminate\Container\Container::setInstance($app);
\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

//alias
$app->alias('config', \Illuminate\Contracts\Config\Repository::class);
$app->alias('config', \Illuminate\Config\Repository::class);
$app->alias('queue', \Illuminate\Queue\QueueManager::class);
$app->alias('queue', \Illuminate\Contracts\Queue\Factory::class);
$app->alias('queue', \Illuminate\Contracts\Queue\Monitor::class);
$app->alias('events', \Illuminate\Contracts\Events\Dispatcher::class);
$app->alias('events', \Illuminate\Events\Dispatcher::class);

//config
$app->singleton('config', function () use ($config) {
    return new \Illuminate\Config\Repository([
        'passport' => $config,
        'database' => [
            'default' => 'mysql',
            'migrations' => 'migrations',
            'connections' => [
                'mysql' => [
                    'driver' => 'mysql',
                    'host' => '192.168.1.251',
                    'port' => '3306',
                    'database' => 'default',
                    'username' => 'root',
                    'password' => 'root',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => true,
                    'engine' => null,
                ],

//                'sqlite' => [
//                    'driver' => 'sqlite',
//                    'database' => __DIR__.'/database.sqlite',
//                    'prefix' => '',
//                    'foreign_key_constraints' => true,
//                ],

            ],
        ],
        'queue' => [
            'default' => 'sync',
            'connections' => [

                'sync' => [
                    'driver' => 'sync',
                ],
            ]
        ]
    ]);
});

//$router = Mockery::mock('Illuminate\Support\Facades\Route');
//$router->shouldReceive('namespace');
//$router->shouldReceive('register');
//$router->shouldReceive('group');
//$router = Mockery::mock('router');
//$Route = Mockery::mock('Illuminate\Support\Facades\Route');
//$Route->shouldReceive('namespace','register','group');

$app->instance('path.lang', './');

function config_path($path = null)
{
    return is_null($path) ? __DIR__ : __DIR__.'/'.$path;
}

function app_path($path = null)
{
    return is_null($path) ? __DIR__ : __DIR__.'/'.$path;
}

function resource_path($path = null)
{
    return is_null($path) ? __DIR__ : __DIR__.'/'.$path;
}

function config($key,$default = null)
{
    \Illuminate\Container\Container::getInstance()->make('config')->get($key,$default);
}

function trans($key)
{
    \Illuminate\Container\Container::getInstance()->make('translator')->trans($key);
}

//service providers
$providers = [
    \Illuminate\Database\DatabaseServiceProvider::class,
    \Illuminate\Hashing\HashServiceProvider::class,
    \Illuminate\Queue\QueueServiceProvider::class,
    \Illuminate\Events\EventServiceProvider::class,
    \Illuminate\Filesystem\FilesystemServiceProvider::class,
    \Illuminate\Translation\TranslationServiceProvider::class,
    \Illuminate\Bus\BusServiceProvider::class,
    \Illuminate\Database\MigrationServiceProvider::class,
    \CrCms\Repository\RepositoryServiceProvider::class,
    \CrCms\Passport\Providers\PassportServiceProvider::class,
];

$providers = array_map(function ($provider) use ($app) {
    return new $provider($app);
}, $providers);

foreach ($providers as $provider) {
    $provider->register();
}

foreach ($providers as $provider) {
    if (method_exists($provider, 'boot')) {
        $provider->boot();
    }

}

//\Illuminate\Support\Facades\Artisan::call('migrate:install');

//$app->make(\Illuminate\Database\Migrations\DatabaseMigrationRepository::class)
//->setSource('sqlite')
//->createRepository();

//$app->make('migrator')
//    ->run(__DIR__.'/../database/migrations');
if (!$app->make('migrator')->getRepository()->repositoryExists()) {
    $app->make('migrator')->getRepository()->createRepository();
}
$app->make('migrator')->reset([__DIR__.'/../database/migrations']);
$app->make('migrator')->run(__DIR__.'/../database/migrations');

//sleep(5);

$app->make(DatabaseSeeder::class)->run();


//$app->make('migrator')->run();

//$factory->define(\CrCms\Passport\Models\UserModel::class, function (\Faker\Generator $faker) {
//    return [
//        'name' => $faker->name,
//        'status' => 1,
//        'app_id' => 1,
//    ];
//});

return $app;