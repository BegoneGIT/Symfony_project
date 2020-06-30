<?php
/**
 * Wallet repository.
 */

namespace App\Repository;

use App\Entity\Label;
use App\Entity\PaymentTypes;
use App\Entity\TransactionTypes;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WalletRepository.
 *
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * WalletRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    /**
     * Query all records.
     *
     * @param array $filters Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial wallet.{id, createdAt, amount}',
                'partial paymentType.{id, name, code}',
                'partial transactionType.{id, name, code}',
                'partial label.{id, name}'
            )
            ->join('wallet.label', 'label')
            ->join('wallet.paymentType', 'paymentType')
            ->join('wallet.transactionType', 'transactionType')
            ->orderBy('wallet.createdAt', 'DESC');
        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param array                      $filters      Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['wallet']) && $filters['wallet'] instanceof Wallet) {
            $queryBuilder->andWhere('wallet = :wallet')
                ->setParameter('wallet', $filters['wallet']);
        }

        if (isset($filters['label']) && $filters['label'] instanceof Label) {
            $queryBuilder->andWhere('label = :label')
                ->setParameter('label', $filters['label']);
        }

        if (isset($filters['paymentType']) && $filters['paymentType'] instanceof PaymentTypes) {
            $queryBuilder->andWhere('paymentType IN (:paymentType)')
                ->setParameter('paymentType', $filters['paymentType']);
        }

        if (isset($filters['transactionType']) && $filters['transactionType'] instanceof TransactionTypes) {
            $queryBuilder->andWhere('transactionType IN (:transactionType)')
                ->setParameter('transactionType', $filters['transactionType']);
        }

        return $queryBuilder;
    }

    /**
     * Query wallet by author.
     *
     * @param \App\Entity\User $user    User entity
     * @param array            $filters Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByAuthor(User $user, array $filters = [], $dates = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);


        $queryBuilder->andWhere('wallet.author = :author')
            ->setParameter('author', $user);

        if ($dates) {
            $queryBuilder->andWhere('wallet.createdAt BETWEEN :date_from AND :date_to')
                ->setParameter('date_from', $dates['date_from'])
                ->setParameter('date_to', $dates['date_to']);
        }

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('wallet');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Wallet $wallet Wallet entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Wallet $wallet): void
    {
        $this->_em->persist($wallet);
        $this->_em->flush($wallet);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Wallet $wallet Wallet entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Wallet $wallet): void
    {
        $this->_em->remove($wallet);
        $this->_em->flush($wallet);
    }
}
