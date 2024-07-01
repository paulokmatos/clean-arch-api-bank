<?php

namespace Tests\Application\UseCases;

use App\Application\PaymentTaxes\CreditCardTax;
use App\Application\Repositories\IAccountRepository;
use App\Application\UseCases\CreateTransactionUseCase;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\Amount;
use App\Infra\Repositories\AccountRepositoryInMemory;
use App\Infra\Repositories\TransactionRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class CreateTransactionUseCaseTest extends TestCase
{
    private IAccountRepository $accountRepository;
    private CreateTransactionUseCase $useCase;

    protected function setUp(): void
    {
        $this->accountRepository = new AccountRepositoryInMemory();
        $transactionRepository = new TransactionRepositoryInMemory();

        $this->useCase = new CreateTransactionUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $this->accountRepository
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
            paymentTax: new CreditCardTax(),
            amount: new Amount(200)
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
            paymentTax: new CreditCardTax(),
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
            paymentTax: new CreditCardTax(),
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
        $this->expectExceptionCode(404);

        $this->accountRepository->store(
            new Account(uniqid('', true), "2000"),
            new Amount(100)
        );

        $this->useCase->execute(
            accountNumber: "2000",
            paymentTax: new CreditCardTax(),
            amount:  new Amount(200)
        );
    }
}
