<?php

namespace App\Presentation\Controllers;

use App\Application\Factories\PaymentTaxFactory;
use App\Application\UseCases\CreateTransactionUseCase;
use App\Application\UseCases\GetBalanceUseCase;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;
use App\Presentation\Routers\RouterDispatcher\JsonResponse;
use App\Presentation\Routers\RouterDispatcher\Request;

readonly class TransactionController implements Controller
{
    public function __construct(
        private CreateTransactionUseCase $transactionUseCase,
        private GetBalanceUseCase $getBalanceUseCase
    ) {
        //
    }

    /**
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        $transactionType = $request->get('forma_pagamento');
        $accountNumber = $request->get('numero_conta');
        $amount = $request->get('valor');

        $transactionType = TransactionTypeEnum::tryFrom($transactionType);

        if (is_null($transactionType)) {
            throw new \RuntimeException("Invalid transaction type", 422);
        }

        if(!is_numeric($accountNumber) || is_float($accountNumber)) {
            throw new \RuntimeException("Invalid account number", 422);
        }

        if (!is_numeric($amount)) {
            throw new \RuntimeException("Invalid amount", 422);
        }

        $taxStrategy = PaymentTaxFactory::factory($transactionType);

        $this->transactionUseCase->execute(
            accountNumber: $accountNumber,
            paymentTax: $taxStrategy,
            amount: Amount::fromAmountFloat($amount)
        );

        $balance = $this->getBalanceUseCase->execute($accountNumber);

        return new JsonResponse([
            'numero_conta' => $accountNumber,
            'saldo' => $balance->amount->parseFloat()
        ]);
    }
}
