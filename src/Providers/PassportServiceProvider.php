<?php

namespace CrCms\Passport\Providers;

use CrCms\Foundation\Providers\ModuleServiceProvider;
use CrCms\Passport\Commands\ApplicationListCommand;
use CrCms\Passport\Commands\CreateApplicationCommand;
use CrCms\Passport\Events\BehaviorCreatedEvent;
use CrCms\Passport\Events\ForgetPasswordEvent;
use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Listeners\BehaviorCreatedListener;
use CrCms\Passport\Listeners\ForgetPasswordMailListener;
use CrCms\Passport\Listeners\LoginTokenListener;
use CrCms\Passport\Listeners\NotificationListener;
use CrCms\Passport\Listeners\RegisterMailListener;
use CrCms\Passport\Listeners\Repositories\UserBehaviorListener;
use CrCms\Passport\Listeners\Repositories\UserListener;
use CrCms\Passport\Repositories\TokenRepository;
use CrCms\Passport\Repositories\UserBehaviorRepository;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Repositories\Contracts\TokenContract;
use Illuminate\Support\Facades\Event;

/**
 * Class PassportServiceProvider
 * @package CrCms\Passport\Providers
 */
class PassportServiceProvider extends ModuleServiceProvider
{
    /**
     * @var string
     */
    protected $basePath = __DIR__ . '/../../';

    /**
     * @var string
     */
    protected $name = 'passport';

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        $this->publishes([
            $this->basePath . 'config/config.php' => config_path("{$this->name}.php"),
            $this->basePath . 'resources/lang' => resource_path("lang/vendor/{$this->name}"),
        ]);

        $this->listens();

        $this->repositoryListener();
    }

    /**
     * @return void
     */
    protected function repositoryListener(): void
    {
        UserRepository::observer(UserListener::class);
        UserBehaviorRepository::observer(UserBehaviorListener::class);
    }

    /**
     * @return void
     */
    protected function map(): void
    {
        if (env('APP_ENV') !== 'testing') {
            require $this->basePath . 'routes/service.php';
        }
    }

    /**
     * @return void
     */
    protected function listens()
    {
        //Event::listen(RegisteredEvent::class, RegisterMailListener::class);
        Event::listen(RegisteredEvent::class, BehaviorCreatedListener::class);

        Event::listen(ForgetPasswordEvent::class, ForgetPasswordMailListener::class);

        Event::listen(BehaviorCreatedEvent::class, BehaviorCreatedListener::class);

        Event::listen(LoginEvent::class, BehaviorCreatedListener::class);
//        Event::listen(LoginEvent::class, LoginTokenListener::class);
        //Event::listen(LoginEvent::class, NotificationListener::class);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(TokenContract::class, TokenRepository::class);

        $this->commands([
            CreateApplicationCommand::class,
            ApplicationListCommand::class,
        ]);
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            CreateApplicationCommand::class,
            ApplicationListCommand::class,
        ];
    }
}