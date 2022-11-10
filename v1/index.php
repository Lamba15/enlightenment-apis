<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require '../src/Helpers.php';

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_ENV["DISPLAY_ERROR_DETAILS"] == '0'){
	error_reporting(0);
}

/**
 * @param ContainerInterface $container
 */
function getClosure(ContainerInterface $container): void {
	$container['db'] = static function (): PDO {
		$dsn = sprintf(
			'mysql:host=%s;dbname=%s;port=%s;charset=utf8',
			$_SERVER['DB_HOST'],
			$_SERVER['DB_NAME'],
			$_SERVER['DB_PORT']
		);
		$pdo = new PDO($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		return $pdo;
	};

}

$app = new App();

$container = $app->getContainer();

/**
 * @param $c
 *
 * @return PDO pdo
 */
getClosure($container);

$container['logger'] = function () {
	$logger = new Logger('my_logger');
	$file_handler = new StreamHandler('logs/app.log');
	$logger->pushHandler($file_handler);
	return $logger;
};

//todo remove in production cors not reason
$app->options('/{routes:.+}', function ($request, $response, $args) {
	return $response;
});
$app->add(function ($req, $res, $next) {
	$response = $next($req, $res);
	return $response
		->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


//$app->get('/ping', function (Request $request, Response $response) {
//	return $response->withJson("Welcome ");
//});

// Routes
//$RouteName($app);


/** @noinspection PhpUnhandledExceptionInspection */
$app->run();

