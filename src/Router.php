<?php
declare(strict_types=1);

namespace Kernel;

/**
 * 路由器类
 *
 * @method static Router notice(string $route, callable $callback)
 * @method static Router normal(string $route, callable $callback)
 * @method static Router any(string $route, callable $callback)
 */
class Router
{
    /**
     * @var Route[]
     */
    protected static $_routes = [];

    /**
     * 静态调用
     * @param string $method
     * @param array $arguments
     */
    public static function __callStatic(string $method, array $arguments)
    {
        [$route, $callback] = $arguments;
        if (($method = strtolower($method)) === 'any') {
            self::addRoute(['notice', 'normal'], $route, $callback);
        } else {
            self::addRoute($method, $route, $callback);
        }
    }

    /**
     * 增加路由
     * @param string|array $method
     * @param string $route
     * @param callable $callback
     * @return Route
     */
    public static function addRoute($method, string $route, callable $callback): Route
    {
        self::$_routes[$route] = new Route($method, $route, $callback);
        return self::$_routes[$route];
    }

    /**
     * 获取路由
     * @param string $route
     * @return Route|null
     */
    public static function getRoute(string $route): ?Route
    {
        if (
            isset(self::$_routes[$route]) and
            self::$_routes[$route] instanceof Route
        ) {
            return self::$_routes[$route];
        }
        return null;
    }

    /**
     * 派遣执行
     * @param string $method
     * @param string $route
     * @param callable|null $error
     * @return false|mixed
     */
    public static function dispatch(string $method, string $route, ?callable $error = null)
    {
        $route = self::getRoute($route);
        if (!$route or !$route->hasMethods($method)) {
            if($error){
                try {
                    return call_user_func($error);
                }catch (\Throwable $throwable){
                    throw new \RuntimeException('Error Callback Exception',500, $throwable);
                }
            }
            throw new \RuntimeException('Not Found',404);
        }

        try {
            Middlewares::run($route->getMiddlewares(), $route->getCallback());
        }catch (\Throwable $throwable){
            throw new \RuntimeException('Dispatch Callback Exception',500, $throwable);
        }
    }
}