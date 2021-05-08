<?php
declare(strict_types=1);

/**
 * 快速获取配置内容
 */
if(!function_exists('C')){
    /**
     * @param string|null $name
     * @param null $default
     * @return array|mixed|null
     */
    function C(string $name = null, $default = null) {
        return \Kernel\Config::get($name, $default);
    }
}


/**
 * 快速获取环境配置内容
 */
if(!function_exists('E')){
    /**
     * @param string|null $name
     * @param null $default
     * @return array|mixed|null
     */
    function E(string $name = null, $default = null) {
        return \Kernel\Env::get($name, $default);
    }
}

/**
 * 中间件助手 - 执行业务内容
 */
if(!function_exists('callback')){
    /**
     * @param Closure $next
     * @param mixed ...$param
     * @return mixed
     */
    function callback(Closure $next, ...$param) {
        return $next(...$param);
    }
}

/**
 * 中间件助手 - 中间件运行
 */
if(!function_exists('run')){
    /**
     * @param callable[] $callables
     * @param callable $init
     * @param mixed ...$param
     * @return Closure
     */
    function run(array $callables, callable $init, ...$param) : Closure {
        return \Kernel\Middlewares::run($callables, $init, ...$param);
    }
}

if(!function_exists('root_init')){
    function root_init() : void {
        if(!defined('ROOT_PATH')){
            define('ROOT_PATH', dirname(dirname(debug_backtrace()[0]['file'])));
        }
    }
}

if(!function_exists('config_path')){
    function config_path() : string {
        return ROOT_PATH . '/config';
    }
}

if(!function_exists('bin_path')){
    function bin_path() : string {
        return ROOT_PATH . '/bin';
    }
}

if(!function_exists('runtime_path')){
    function runtime_path() : string {
        return ROOT_PATH . '/runtime';
    }
}

if(!function_exists('process_path')){
    function process_path() : string {
        return ROOT_PATH . '/process';
    }
}

