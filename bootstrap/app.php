<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

class Application extends \Laravel\Lumen\Application
{
    public function any($uri, $action)
    {
        $this->router->addRoute(['GET', 'POST'], $uri, $action);
        return $this;
    }
}

$app = new Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();
$app->withEloquent();

foreach (Finder::create()->files()->name('*.php')->in($app->basePath('config')) as $file) {
    $filename = trim($file->getFileName(), '.php');
    $dir = $file->getRelativePath();
    if (!empty($filename)) {
        $path = $dir . '/' . $filename;
        if ($dir) {
            $path = $dir . '/' . $filename;
        } else {
            $path = $filename;
        }
        $app->configure($path);
    }
}

//开启sql log，使用时DB::getQueryLog();
DB::enableQueryLog();

//测试环境允许指定域名跨域
if (env('APP_ENV') == 'local' || env('APP_ENV') == 'test') {
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    if ($origin) {
        header('Access-Control-Allow-Origin:' . $origin);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Auth,Content-Type");
        header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
    }

} else {
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    if (in_array($origin, [
        'https://cherish-time.wugenglong.com',
    ])) {
        header('Access-Control-Allow-Origin:' . $origin);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Auth,Content-Type");
        header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
    }
}

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
    //    'auth'      => App\Http\Middleware\Authenticate::class,
    'weappAuth' => App\Http\Middleware\Auth\WeappAuthenticate::class,
    'throttle'  => App\Http\Middleware\ThrottleRequests::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(\Illuminate\Redis\RedisServiceProvider::class);


if ($app->environment() != 'prod') {
    $app->register(Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
