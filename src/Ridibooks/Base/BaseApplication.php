<?php
declare(strict_types=1);

namespace Ridibooks\Base;

use Laravel\Lumen\Application as LumenApplication;
use Laravel\Lumen\Bootstrap\LoadEnvironmentVariables;
use Laravel\Lumen\Exceptions\Handler;

abstract class BaseApplication
{
    /** @var string */
    protected $dir;
    /** @var LumenApplication */
    protected $app;

    protected function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->boot();

        $this->initView();
        $this->registerServiceProviders();
        $this->registerMiddlewares();
        $this->routeControllers();
        $this->registerExceptionHandler();
    }

    public static function create(string $dir)
    {
        $called_class = get_called_class();
        $static = new $called_class($dir);

        return $static;
    }

    protected function boot(): void
    {
        $error_type = error_reporting();

        (new LoadEnvironmentVariables($this->dir))->bootstrap();

        $this->initEnv();

        $this->app = new LumenApplication($this->dir);
        $this->app->withFacades();

        error_reporting($error_type);
    }

    abstract protected function initEnv(): void;

    protected function initView(): void
    {
    }

    protected function registerServiceProviders(): void
    {
        $service_providers = $this->getServiceProviders();
        foreach ($service_providers as $service_provider) {
            $this->app->register($service_provider);
        }
    }

    protected function getServiceProviders(): array
    {
        return [];
    }

    protected function registerMiddlewares(): void
    {
        $middlewares = $this->getMiddlewares();
        if (!empty($middlewares)) {
            $this->app->middleware($middlewares);
        }

        $route_middlewares = $this->getRouteMiddlewares();
        if (!empty($route_middlewares)) {
            $this->app->routeMiddleware($route_middlewares);
        }
    }

    protected function getMiddlewares(): array
    {
        return [];
    }

    protected function getRouteMiddlewares(): array
    {
        return [];
    }

    abstract protected function routeControllers(): void;

    protected function registerExceptionHandler(): void
    {
        $exception_handler = $this->getExceptionHandler();

        if ($exception_handler instanceof Handler) {
            $this->app->singleton(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                $exception_handler
            );
        }
    }

    protected function getExceptionHandler(): ?Handler
    {
        return null;
    }

    public function run(): void
    {
        $this->app->run();
    }
}
