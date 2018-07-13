<?php

namespace CrCms\Passport\Providers;

use CrCms\Foundation\App\Providers\ModuleServiceProvider;
use CrCms\Passport\Events\BehaviorCreatedEvent;
use CrCms\Passport\Events\ForgetPasswordEvent;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Listeners\BehaviorCreatedListener;
use CrCms\Passport\Listeners\ForgetPasswordMailListener;
use CrCms\Passport\Listeners\RegisterMailListener;
use CrCms\Passport\Listeners\Repositories\UserBehaviorListener;
use CrCms\Passport\Listeners\Repositories\UserListener;
use CrCms\Passport\Repositories\UserBehaviorRepository;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Support\Facades\Event;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;

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
    protected function repositoryListener(): void
    {
        UserRepository::observer(UserListener::class);
        UserBehaviorRepository::observer(UserBehaviorListener::class);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        $this->publishes([
            $this->basePath . 'config/auth.php' => config_path('auth.php'),
            $this->basePath . 'config/config.php' => config_path("{$this->name}.php"),
            $this->basePath . 'resources/lang' => resource_path("lang/vendor/{$this->name}"),
        ]);

        $this->loadViewsFrom($this->basePath . '/resources/views', $this->name);

        $this->listens();
    }

    /**
     * @return void
     */
    protected function listens()
    {
        Event::listen(RegisteredEvent::class, RegisterMailListener::class);
        Event::listen(RegisteredEvent::class, BehaviorCreatedListener::class);

        Event::listen(ForgetPasswordEvent::class, ForgetPasswordMailListener::class);

        Event::listen(BehaviorCreatedEvent::class, BehaviorCreatedListener::class);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(
            $this->basePath . 'config/auth.php', 'auth'
        );

        $this->app->register(AuthServiceProvider::class);
        $this->app->register(PasswordResetServiceProvider::class);
        $this->app->register(LaravelServiceProvider::class);
    }
}