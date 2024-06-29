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

    public function test_ShouldCreateANewAccount(): void
    {
        $this->useCase->execute(accountNumber: "209", amount: 20.8);

        $account = $this->repository->find("209");

        $this->assertEquals("209", $account->accountNumber);
    }
}