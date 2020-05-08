<?php
declare(strict_types=1);

namespace Ridibooks\Lumen\Base;

use Laravel\Lumen\Application as LumenApplication;
use Laravel\Lumen\Bootstrap\LoadEnvironmentVariables;
use Laravel\Lumen\Exceptions\Handler;
use Ridibooks\Lumen\Constant\ConfigConst;

abstract class BaseApplication
{
    /** @var string */
    protected $dir;
    /** @var LumenApplication */
    protected $app;
    /** @var array */
    protected $config = [];

    protected function __construct(array $config)
    {
        $this->config = array_merge(ConfigConst::DEFAULT_CONFIG, $config);
        $this->dir = $this->config[ConfigConst::BASE_PATH];

        $this->beforeBoot();
        $this->boot();
        $this->afterBoot();

        $this->initView();
        $this->registerServiceProviders();
        $this->registerMiddlewares();
        $this->routeControllers();
        $this->registerExceptionHandler();
    }

    public static function create(array $config)
    {
        $static = new static($config);

        return $static;
    }

    protected function beforeBoot(): void
    {
    }

    protected function boot(): void
    {
        $error_type = error_reporting();

        (new LoadEnvironmentVariables($this->dir))->bootstrap();
        $_ENV['APP_DEBUG'] = $this->config[ConfigConst::DEBUG]; // override debug mode

        $this->initEnv();

        $this->app = new LumenApplication($this->dir);
        $this->app->withFacades();

        error_reporting($error_type);
    }

    protected function afterBoot(): void
    {
    }

    abstract protected function initEnv(): void;

    protected function initView(): void
    {
    }

    protected function registerServiceProviders(): void
    {
        $service_providers = $this->config[ConfigConst::SERVICE_PROVIDERS] ?? [];
        foreach ($service_providers as $service_provider) {
            $this->app->register($service_provider);
        }
    }

    protected function registerMiddlewares(): void
    {
        $middlewares = $this->config[ConfigConst::MIDDLEWARES] ?? [];
        if (!empty($middlewares)) {
            $this->app->middleware($middlewares);
        }

        $route_middlewares = $this->config[ConfigConst::ROUTE_MIDDLEWARES] ?? [];
        if (!empty($route_middlewares)) {
            $this->app->routeMiddleware($route_middlewares);
        }
    }

    abstract protected function routeControllers(): void;

    protected function registerExceptionHandler(): void
    {
        $exception_handler = $this->config[ConfigConst::EXCEPTION_HANDLER] ?? '';

        if ($exception_handler instanceof Handler) {
            $this->app->singleton(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                $exception_handler
            );
        }
    }

    public function run(): void
    {
        $this->app->run();
    }
}
