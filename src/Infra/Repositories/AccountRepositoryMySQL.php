<?php

namespace App\Infra\Repositories;

use App\Application\Repositories\IAccountRepository;
use App\Domain\Entities\Account;
use App\Domain\ValueObjects\AccountBalance;
use App\Domain\ValueObjects\Amount;
use App\Infra\Database\ISqlAdapter;

readonly class AccountRepositoryMySQL implements IAccountRepository
{
    public function __construct(private ISqlAdapter $db)
    {
        //
    }

    public function find(string $accountNumber): ?Account
    {
        $sql = 'SELECT * FROM account WHERE account_number = :account_number';
        $params = [':account_number' => $accountNumber];
        $result = $this->db->query($sql, $params);

        if (empty($result)) {
            return null;
        }

        $data = $result[0];
        return new Account($data['id'], $data['account_number']);
    }

    public function findOrFail(string $accountNumber): Account
    {
        $account = $this->find($accountNumber);

        if ($account === null) {
            throw new \Exception('Account not found');
        }

        return $account;
    }

    public function store(Account $account, Amount $amount): Account
    {
        $this->db->beginTransaction();
        try {
            $sql = 'INSERT INTO account (id, account_number) VALUES (:id, :account_number)';
            $params = [
                ':id' => $account->id,
                ':account_number' => $account->accountNumber,
            ];
            $this->db->execute($sql, $params);

            $accountBalance = new AccountBalance($account->id, $amount);
            $this->createOrUpdateBalance($accountBalance);

            $this->db->commit();
            return $account;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getBalance(string $accountId): AccountBalance
    {
        $sql = 'SELECT amount FROM account_balance WHERE account_id = :account_id';
        $params = [':account_id' => $accountId];
        $result = $this->db->query($sql, $params);

        if (empty($result)) {
            throw new \Exception('Account balance not found');
        }

        return new AccountBalance($accountId, $result[0]['amount']);
    }

    public function createOrUpdateBalance(AccountBalance $accountBalance): AccountBalance
    {
        $sql = 'INSERT INTO account_balance (account_id, amount) VALUES (:account_id, :amount)
                ON DUPLICATE KEY UPDATE amount = :amount';
        $params = [
            ':account_id' => $accountBalance->accountId,
            ':amount' => $accountBalance->amount->value,
        ];
        $this->db->execute($sql, $params);

        return $accountBalance;
    }
}
