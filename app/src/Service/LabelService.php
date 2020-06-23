<?php
/**
 * Label service.
 */

namespace App\Service;

use App\Entity\Label;
use App\Entity\Wallet;
use App\Repository\LabelRepository;

/**
 * Class LabelService.
 */
class LabelService
{
    /**
     * Label constructor.
     */
    public function __construct(LabelRepository $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }

    /**
     * Save label.
     * @param Label $label label label
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Label $label): void
    {
        $this->labelRepository->save($label);
    }

    /**
     * Delete label.
     *
     * @param \App\Entity\Label $label Label entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Label $label): void
    {
        $this->labelRepository->delete($label);
    }

    /**
     * Find label by Id.
     *
     * @param int $id Wallet Id
     *
     * @return \App\Entity\Label|null Label entity
     */
    public function findOneById(int $id): ?Label
    {
        return $this->labelRepository->findOneById($id);
    }
}
