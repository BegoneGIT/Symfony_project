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
     * PaymentTypes constructor.
     */
    public function __construct(PaymentTypesRepository $paymentTypesRepository)
    {
        $this->paymentTypesRepository = $paymentTypesRepository;
    }

    /**
     * Save label.
     * @param PaymentTypes $label label label
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PaymentTypes $label): void
    {
        $this->paymentTypesRepository->save($label);
    }

    /**
     * Delete label.
     *
     * @param \App\Entity\PaymentTypes $label PaymentTypes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(PaymentTypes $label): void
    {
        $this->paymentTypesRepository->delete($label);
    }

    /**
     * Find label by Id.
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
