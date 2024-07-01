<?php

namespace Tests\Presentation\Controllers;

use App\Application\Repositories\IAccountRepository;
use App\Application\UseCases\CreateAccountUseCase;
use App\Application\UseCases\GetBalanceUseCase;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\Amount;
use App\Infra\Repositories\AccountRepositoryInMemory;
use App\Presentation\Controllers\AccountController;
use App\Presentation\Routers\RouterDispatcher\Request;
use PHPUnit\Framework\TestCase;

class AccountControllerTest extends TestCase
{
    private IAccountRepository $accountRepository;
    private CreateAccountUseCase $createAccountUseCase;
    private GetBalanceUseCase $getBalanceUseCase;
    private AccountController $controller;

    protected function setUp(): void
    {
        $this->accountRepository = new AccountRepositoryInMemory();
        $this->createAccountUseCase = new CreateAccountUseCase($this->accountRepository);
        $getBalanceUseCase = new GetBalanceUseCase($this->accountRepository);
        $this->controller = new AccountController($this->createAccountUseCase, $getBalanceUseCase);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldCreateAccount(): void
    {
        $request = new Request([
            "numero_conta" => "404",
            "saldo" => 180.37
        ]);

        $transaction = $this->controller->create($request);

        $this->assertEquals("404", $transaction->content['numero_conta']);
        $this->assertEquals("180.37", $transaction->content['saldo']);
    }

    /**
     * @throws \Exception
     */
    public function test_ShouldGetAccount(): void
    {
        $this->accountRepository->store(
            new Account(uniqid('', true), "404"),
            Amount::fromAmountFloat(170.60)
        );

        $request = new Request(["numero_conta" => "404"]);

        $transaction = $this->controller->find($request);

        $this->assertEquals("404", $transaction->content['numero_conta']);
        $this->assertEquals("170.60", $transaction->content['saldo']);
    }

    public function test_ShouldThrowExceptionWhenPassInvalidAccountNumberOnGet(): void
    {
        $request = new Request([
            'numero_conta' => "oi",
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("'numero_conta' must be numeric of type int");
        $this->expectExceptionCode(422);

        $this->controller->find($request);
    }

    public function test_ShouldThrowExceptionWhenPassInvalidAccountNumberOnCreate(): void
    {
        $request = new Request([
            "numero_conta" => "oi",
            "saldo" => 180.37
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("'numero_conta' must be numeric of type int");
        $this->expectExceptionCode(422);

        $this->controller->create($request);
    }

    public function test_ShouldThrowExceptionWhenDontPassExpectedParametersOnCreate(): void
    {
        $request = new Request([
            "saldo" => 180.37
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("required parameter 'numero_conta' and 'saldo'");
        $this->expectExceptionCode(422);

        $this->controller->create($request);
    }

    public function test_ShouldThrowExceptionWhenPassInvalidAmount(): void
    {
        $request = new Request([
            "numero_conta" => "404",
            "saldo" => "oia"
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("'saldo' must be numeric of type float");
        $this->expectExceptionCode(422);

        $this->controller->create($request);
    }
}