<?php

namespace Tests\Application\UseCases;

use App\Application\PaymentTaxes\CreditCardTax;
use App\Application\Repositories\IAccountRepository;
use App\Application\Repositories\ITransactionRepository;
use App\Application\UseCases\CreateTransactionUseCase;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Account;
use App\Domain\Enums\TransactionTypeEnum;
use App\Domain\ValueObjects\Amount;
use App\Infra\Repositories\AccountRepositoryInMemory;
use App\Infra\Repositories\TransactionRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class CreateTransactionUseCaseTest extends TestCase
{
    private IPaymentTax $paymentTax;
    private IAccountRepository $accountRepository;
    private ITransactionRepository $transactionRepository;
    private CreateTransactionUseCase $useCase;

    protected function setUp(): void
    {
        $this->accountRepository = new AccountRepositoryInMemory();
        $this->paymentTax = new CreditCardTax();
        $this->transactionRepository = new TransactionRepositoryInMemory();
        $this->useCase = new CreateTransactionUseCase(
            transactionRepository: $this->transactionRepository,
            accountRepository: $this->accountRepository,
            paymentTax: $this->paymentTax
        );
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldCreateANewTransaction(): void
    {
        $this->accountRepository->store(
            new Account(uniqid('', true), "2000"),
            new Amount(300)
        );

        $transaction = $this->useCase->execute(
            accountNumber: "2000",
            transactionType: TransactionTypeEnum::CREDIT,
            amount:  new Amount(200)
        );

        $this->assertEquals("2000", $transaction->accountNumber);
        $this->assertEquals("C", $transaction->transactionType->value);
        $this->assertEquals(210, $transaction->amount->value);
    }

    public function test_ShouldThrowsExceptionWhenAccountNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Account 2567 does not exist");
        $this->expectExceptionCode(404);

        $this->useCase->execute(
            accountNumber: "2567",
            transactionType: TransactionTypeEnum::CREDIT,
            amount:  new Amount(200)
        );
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldUpdateAccountBalance(): void
    {
        $account = $this->accountRepository->store(
            new Account(uniqid('', true), "2000"),
            new Amount(300)
        );


        $transaction = $this->useCase->execute(
            accountNumber: "2000",
            transactionType: TransactionTypeEnum::CREDIT,
            amount:  new Amount(200)
        );

        $accountBalance = $this->accountRepository->getBalance($account->id);

        $this->assertEquals("2000", $transaction->accountNumber);
        $this->assertEquals("C", $transaction->transactionType->value);
        $this->assertEquals(210, $transaction->amount->value);
        $this->assertEquals(90, $accountBalance->amount->value);
    }

    public function test_ShouldThrowsExceptionIfAccountBalanceBecomeNegative(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Account balance must not be negative");
        $this->expectExceptionCode(403);

        $this->accountRepository->store(
            new Account(uniqid('', true), "2000"),
            new Amount(100)
        );

        $this->useCase->execute(
            accountNumber: "2000",
            transactionType: TransactionTypeEnum::CREDIT,
            amount:  new Amount(200)
        );
    }
}
