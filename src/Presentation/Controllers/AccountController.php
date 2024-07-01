<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\CreateAccountUseCase;
use App\Application\UseCases\GetBalanceUseCase;
use App\Presentation\Routers\RouterDispatcher\JsonResponse;
use App\Presentation\Routers\RouterDispatcher\Request;

readonly class AccountController
{
    public function __construct(
        private CreateAccountUseCase $createAccountUseCase,
        private GetBalanceUseCase $getBalanceUseCase,
    )
    {
        //
    }

    public function find(Request $request): JsonResponse
    {
        $accountNumber = $request->get('numero_conta');

        if (!is_numeric($accountNumber) || is_float($accountNumber)) {
            throw new \RuntimeException("'numero_conta' must be numeric of type int");
        }

        $balance = $this->getBalanceUseCase->execute($accountNumber);

        return new JsonResponse([
            'numero_conta' => $accountNumber,
            'saldo' => $balance->amount->parseFloat()
        ], 200);
    }

    /**
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        $accountNumber = $request->get('numero_conta');
        $amount = $request->get('saldo');

        if (!$accountNumber || !$amount) {
            throw new \RuntimeException("required parameter 'numero_conta' and 'saldo'");
        }

        if (!is_numeric($amount)) {
            throw new \RuntimeException("'saldo' must be numeric of type float");
        }

        if (!is_numeric($accountNumber) || is_float($accountNumber)) {
            throw new \RuntimeException("'numero_conta' must be numeric of type int");
        }

        $account = $this->createAccountUseCase->execute($accountNumber, $amount);
        $balance = $this->getBalanceUseCase->execute($accountNumber);

        return new JsonResponse([
            'numero_conta' => $account->accountNumber,
            'saldo' => $balance->amount->parseFloat()
        ], 201);
    }
}
