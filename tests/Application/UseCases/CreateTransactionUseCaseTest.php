<?php

namespace Tests\Application\UseCases;

use App\Application\PaymentTaxes\CreditCardTax;
use App\Application\PaymentTaxes\DebitCardTax;
use App\Application\PaymentTaxes\PixTax;
use App\Application\Repositories\IAccountRepository;
use App\Application\UseCases\CreateTransactionUseCase;
use App\Domain\Contracts\IPaymentTax;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\Amount;
use App\Infra\Repositories\AccountRepositoryInMemory;
use App\Infra\Repositories\TransactionRepositoryInMemory;
use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('taxDataProvider')]
    public function test_ShouldUpdateAccountBalance(IPaymentTax $paymentTax, int $value, int $valueWithTax): void
    {
        $account = $this->accountRepository->store(
            new Account(uniqid('', true), "2000"),
            new Amount(300)
        );

        $transaction = $this->useCase->execute(
            accountNumber: "2000",
            paymentTax: $paymentTax,
            amount:  new Amount($value)
        );

        $accountBalance = $this->accountRepository->getBalance($account->id);

        $this->assertEquals("2000", $transaction->accountNumber);
        $this->assertEquals($paymentTax->getType(), $transaction->transactionType);
        $this->assertEquals($valueWithTax, $transaction->amount->value);
        $this->assertEquals(300 - $valueWithTax, $accountBalance->amount->value);
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

    /**
     * @return array<string, array{0: IPaymentTax, 1: int, 2: int}>
     */
    public static function taxDataProvider(): array
    {
        return [
            'should apply credit card tax' => [new CreditCardTax(), 200, 210],
            'should apply debit card tax' => [new DebitCardTax(), 200, 206],
            'should apply pix tax' => [new PixTax(), 200, 200],
        ];
    }
}
