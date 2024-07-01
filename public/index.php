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

require_once __DIR__ . '/../vendor/autoload.php';

$router = new BramusRouterAdapter();

// TODO: move credentials to env variables
$pdoAdapter = new PdoAdapter(
    dsn: 'mysql:host=obj-bank-mysql;port=3306;dbname=database',
    username: 'root',
    password: 'root'
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