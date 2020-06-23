<?php
/**
 * \App\Entity\TransactionTypes service.
 */

namespace App\Service;

use App\Entity\TransactionTypes;
use App\Repository\TransactionTypesRepository;

/**
 * Class TransactionTypesService.
 */
class TransactionTypesService
{
    /**
     * TransactionTypes constructor.
     */
    public function __construct(TransactionTypesRepository $transactionTypesRepository)
    {
        $this->transactionTypesRepository = $transactionTypesRepository;
    }

    /**
     * Save label.
     * @param TransactionTypes $label label label
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(TransactionTypes $label): void
    {
        $this->transactionTypesRepository->save($label);
    }

    /**
     * Delete label.
     *
     * @param \App\Entity\TransactionTypes $label TransactionTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(TransactionTypes $label): void
    {
        $this->transactionTypesRepository->delete($label);
    }

    /**
     * Find label by Id.
     *
     * @param int $id Wallet Id
     *
     * @return \App\Entity\TransactionTypes|null TransactionTypes entity
     */
    public function findOneById(int $id): ?TransactionTypes
    {
        return $this->transactionTypesRepository->findOneById($id);
    }
}
