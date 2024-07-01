<?php

namespace Tests\Presentation\Controllers;

use App\Application\Repositories\IAccountRepository;
use App\Application\Repositories\ITransactionRepository;
use App\Application\UseCases\CreateTransactionUseCase;
use App\Application\UseCases\GetBalanceUseCase;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Account;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;
use App\Infra\Repositories\AccountRepositoryInMemory;
use App\Infra\Repositories\TransactionRepositoryInMemory;
use App\Presentation\Controllers\TransactionController;
use App\Presentation\Routers\RouterDispatcher\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TransactionControllerTest extends TestCase
{
    private ITransactionRepository $transactionRepository;
    private IAccountRepository $accountRepository;
    private TransactionController $controller;

    protected function setUp(): void
    {
        $this->accountRepository = new AccountRepositoryInMemory();
        $this->transactionRepository = new TransactionRepositoryInMemory();
        $createTransactionUseCase = new CreateTransactionUseCase(
            $this->transactionRepository,
            $this->accountRepository
        );
        $getBalanceUseCase = new GetBalanceUseCase($this->accountRepository);
        $this->controller = new TransactionController(
            $createTransactionUseCase,
            $getBalanceUseCase
        );
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldThrowExceptionWhenInvalidTransactionType(): void
    {
        $this->accountRepository->store(
            new Account(uniqid('', true), "404"),
            Amount::fromAmountFloat(10)
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid transaction type");
        $this->expectExceptionCode(422);

        $request = new Request([
            "numero_conta" => "404",
            "forma_pagamento" => "Z",
            "valor" => 5
        ]);

        $this->controller->create($request);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldThrowExceptionWhenPassInvalidAccountNumber(): void
    {
        $this->accountRepository->store(
            new Account(uniqid('', true), "404"),
            Amount::fromAmountFloat(10)
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid account number");
        $this->expectExceptionCode(422);

        $request = new Request([
            "numero_conta" => "abc",
            "forma_pagamento" => "P",
            "valor" => 5
        ]);

        $this->controller->create($request);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldThrowExceptionWhenPassInvalidAmount(): void
    {
        $this->accountRepository->store(
            new Account(uniqid('', true), "404"),
            Amount::fromAmountFloat(10)
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");
        $this->expectExceptionCode(422);

        $request = new Request([
            "numero_conta" => "404",
            "forma_pagamento" => "P",
            "valor" => "zzzzzz"
        ]);

        $this->controller->create($request);
    }
    

    /**
     * @throws \Exception
     */
    #[DataProvider('taxDataProvider')]
    public function test_ShouldCreateTransaction(string $paymentMethod, int $balance, int $value, string $balanceAfterTax): void
    {
        $this->accountRepository->store(
            new Account(uniqid('', true), "404"),
            Amount::fromAmountFloat($balance)
        );

        $request = new Request([
            "numero_conta" => "404",
            "forma_pagamento" => $paymentMethod,
            "valor" => $value
        ]);

         $transaction = $this->controller->create($request);

         $this->assertEquals("404", $transaction->content['numero_conta']);
         $this->assertEquals($balanceAfterTax, $transaction->content['saldo']);
    }

    /**
     * @return array<string, array{0: IPaymentTax, 1: int, 2: int}>
     */
    public static function taxDataProvider(): array
    {
        return [
            'should apply credit card tax' => [TransactionTypeEnum::CREDIT->value, 100, 10, "89.50"],
            'should apply debit card tax' => [TransactionTypeEnum::DEBIT->value, 100, 10, "89.70"],
            'should apply pix tax' => [TransactionTypeEnum::PIX->value, 100, 10, "90.00"],
        ];
    }
}