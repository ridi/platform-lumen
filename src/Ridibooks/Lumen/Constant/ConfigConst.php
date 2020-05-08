<?php

namespace Ridibooks\Lumen\Constant;

class ConfigConst
{
    public const DEBUG = 'debug';
    public const BASE_PATH = 'base.path';
    public const BASE_CONTROLLER_NAMESPACE = 'base.controller_namespace';

    public const SERVICE_PROVIDERS = 'service_providers';
    public const MIDDLEWARES = 'middlewares';
    public const ROUTE_MIDDLEWARES = 'route_middlewares';
    public const EXCEPTION_HANDLER = 'exception_handler';

    public const TWIG_PATH = 'twig.path';
    public const TWIG_GLOBALS = 'twig.globals';

    public const DEFAULT_CONFIG = [
        self::DEBUG => false,
        self::BASE_PATH => '',
        self::BASE_CONTROLLER_NAMESPACE => '',
        self::SERVICE_PROVIDERS => [],
        self::MIDDLEWARES => [],
        self::ROUTE_MIDDLEWARES => [],
        self::EXCEPTION_HANDLER => '',
        self::TWIG_PATH => [],
        self::TWIG_GLOBALS => [],
    ];
}
