<?php
/**
 * \App\Entity\PaymentTypes service.
 */

namespace App\Service;

use App\Entity\PaymentTypes;
use App\Repository\PaymentTypesRepository;

/**
 * Class PaymentTypesService.
 */
class PaymentTypesService
{
    /**
     * @var PaymentTypesRepository
     */
    private $paymentTypesRepository;

    /**
     * PaymentTypes constructor.
     *
     * @param PaymentTypesRepository $paymentTypesRepository
     */
    public function __construct(PaymentTypesRepository $paymentTypesRepository)
    {
        $this->paymentTypesRepository = $paymentTypesRepository;
    }

    /**
     * Save paymentType.
     *
     * @param PaymentTypes $paymentType paymentType paymentType
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PaymentTypes $paymentType): void
    {
        $this->paymentTypesRepository->save($paymentType);
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
        $this->paymentTypesRepository->delete($paymentType);
    }

    /**
     * Find paymentType by Id.
     *
     * @param int $id paymentType Id
     *
     * @return \App\Entity\PaymentTypes|null PaymentTypes entity
     */
    public function findOneById(int $id): ?PaymentTypes
    {
        return $this->paymentTypesRepository->findOneById($id);
    }
}
