<?php

namespace App\Repository;

use App\Entity\PaymentTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentTypes[]    findAll()
 * @method PaymentTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentTypesRepository extends ServiceEntityRepository
{
    /**
     * PaymentTypesRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentTypes::class);
    }

    /**
     * Save paymentType.
     *
     * @param \App\Entity\PaymentTypes $paymentType PaymentTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PaymentTypes $paymentType): void
    {
        $this->_em->persist($paymentType);
        $this->_em->flush($paymentType);
    }

    /**
     * Delete paymentType.
     *
     * @param \App\Entity\PaymentTypes $paymentType PaymentTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(PaymentTypes $paymentType): void
    {
        $this->_em->remove($paymentType);
        $this->_em->flush($paymentType);
    }
}
