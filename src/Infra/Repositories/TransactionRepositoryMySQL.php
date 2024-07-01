<?php

namespace App\Infra\Repositories;

use App\Application\Repositories\ITransactionRepository;
use App\Domain\Entities\Transaction;
use App\Infra\Database\ISqlAdapter;

readonly class TransactionRepositoryMySQL implements ITransactionRepository
{
    public function __construct(private ISqlAdapter $db)
    {
        //
    }

    /**
     * @throws \Exception
     */
    public function create(Transaction $transaction): Transaction
    {
        $this->db->beginTransaction();
        try {
            $sql = 'INSERT INTO transaction (id, account_number, amount, type) VALUES (:id, :account_number, :amount, :type)';
            $params = [
                ':id' => $transaction->id,
                ':account_number' => $transaction->accountNumber,
                ':amount' => $transaction->amount->value,
                ':type' => $transaction->transactionType->value
            ];
            $this->db->execute($sql, $params);

            $this->db->commit();
            return $transaction;
        } catch (\Exception $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function find(string $transactionId): ?Transaction
    {
        $sql = 'SELECT * FROM transaction WHERE account_number = :id';
        $params = [':id' => $transactionId];
        $result = $this->db->query($sql, $params);

        if (empty($result)) {
            return null;
        }

        $data = $result[0];
        return new Transaction($data['id'], $data['account_number'], $data['amount'], $data['type']);
    }
}
