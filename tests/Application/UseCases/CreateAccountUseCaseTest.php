<?php

namespace Tests\Application\UseCases;

use App\Application\Repositories\IAccountRepository;
use App\Application\UseCases\CreateAccountUseCase;
use App\Infra\Repositories\AccountRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class CreateAccountUseCaseTest extends TestCase
{
    private IAccountRepository $repository;
    private CreateAccountUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new AccountRepositoryInMemory();
        $this->useCase = new CreateAccountUseCase($this->repository);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldCreateANewAccount(): void
    {
        $this->useCase->execute(accountNumber: "209", amount: 20.80);

        $account = $this->repository->findOrFail("209");

        $accountBalance = $this->repository->getBalance($account->id);

        $this->assertEquals("209", $account->accountNumber);
        $this->assertEquals(2080, $accountBalance->amount->value);
    }

    public function test_ShouldThrowsExceptionIfAccountAlreadyExists(): void
    {
        $this->useCase->execute(accountNumber: "209", amount: 20.80);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Account number already exists");
        $this->expectExceptionCode(400);

        $this->useCase->execute(accountNumber: "209", amount: 2000);
    }
}
