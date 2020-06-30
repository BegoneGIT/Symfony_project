<?php

namespace App\Repository;

use App\Entity\TransactionTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionTypes[]    findAll()
 * @method TransactionTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionTypesRepository extends ServiceEntityRepository
{
    /**
     * TransactionTypesRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionTypes::class);
    }

    /**
     * Save transactionType.
     *
     * @param \App\Entity\TransactionTypes $transactionType TransactionTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(TransactionTypes $transactionType): void
    {
        $this->_em->persist($transactionType);
        $this->_em->flush($transactionType);
    }

    /**
     * Delete transactionType.
     *
     * @param \App\Entity\TransactionTypes $transactionType TransactionTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(TransactionTypes $transactionType): void
    {
        $this->_em->remove($transactionType);
        $this->_em->flush($transactionType);
    }
}
