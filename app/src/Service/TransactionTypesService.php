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
     * Save paymentType.
     * @param TransactionTypes $paymentType paymentType paymentType
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(TransactionTypes $paymentType): void
    {
        $this->transactionTypesRepository->save($paymentType);
    }

    /**
     * Delete paymentType.
     *
     * @param \App\Entity\TransactionTypes $paymentType TransactionTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(TransactionTypes $paymentType): void
    {
        $this->transactionTypesRepository->delete($paymentType);
    }

    /**
     * Find paymentType by Id.
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
