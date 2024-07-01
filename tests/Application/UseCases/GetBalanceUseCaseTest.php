<?php

namespace Tests\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Application\UseCases\GetBalanceUseCase;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\Amount;
use App\Infra\Repositories\AccountRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class GetBalanceUseCaseTest extends TestCase
{
    private IAccountRepository $repository;
    private GetBalanceUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new AccountRepositoryInMemory();
        $this->useCase = new GetBalanceUseCase($this->repository);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldGetBalance(): void
    {
        $account = $this->repository->store(
            account: new Account(uniqid('', true), "244"),
            amount: new Amount(200)
        );

        $balance = $this->useCase->execute("244");

        $this->assertEquals($account->id, $balance->accountId);
        $this->assertEquals(200, $balance->amount->value);
    }

    public function test_ShouldThrowsExceptionWhenDoesntExistsAccount(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Account 244 does not exist");
        $this->expectExceptionCode(404);

        $this->useCase->execute("244");
    }
}
