<?php

use App\Application\UseCases\CreateAccountUseCase;
use App\Application\UseCases\CreateTransactionUseCase;
use App\Application\UseCases\GetBalanceUseCase;
use App\Infra\Database\PdoAdapter;
use App\Infra\Repositories\AccountRepositoryMySQL;
use App\Infra\Repositories\TransactionRepositoryMySQL;
use App\Presentation\Controllers\AccountController;
use App\Presentation\Controllers\HealthCheckController;
use App\Presentation\Controllers\TransactionController;
use App\Presentation\Routers\BramusRouterAdapter;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$path = dirname(__FILE__, 2);

$dotenv = Dotenv::createImmutable($path);
$dotenv->load();
$router = new BramusRouterAdapter();

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

$pdoAdapter = new PdoAdapter(
    dsn: "mysql:host=$host;port=$port;dbname=$dbname",
    username: $username,
    password: $password
);

$accountRepository = new AccountRepositoryMySQL($pdoAdapter);
$transactionRepository = new TransactionRepositoryMySQL($pdoAdapter);

$createAccountUseCase = new CreateAccountUseCase($accountRepository);
$getBalanceUseCase = new GetBalanceUseCase($accountRepository);
$createTransactionUseCase = new CreateTransactionUseCase($transactionRepository, $accountRepository);

$accountController = new AccountController($createAccountUseCase, $getBalanceUseCase);
$transactionController = new TransactionController($createTransactionUseCase, $getBalanceUseCase);

$router->register('get', '/health-check', (new HealthCheckController())->status(...));
$router->register('post', '/conta', $accountController->create(...));
$router->register('get', '/conta', $accountController->find(...));
$router->register('post', '/transacao', $transactionController->create(...));

$router->run();