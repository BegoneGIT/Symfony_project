<?php
/**
 * Wallet service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\WalletRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class WalletService.
 */
class WalletService
{
    /**
     * Wallet repository.
     *
     * @var \App\Repository\WalletRepository
     */
    private $walletRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * WalletService constructor.
     *
     * @param \App\Repository\WalletRepository        $walletRepository Wallet repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator        Paginator
     */
    public function __construct(WalletRepository $walletRepository, PaginatorInterface $paginator)
    {
        $this->walletRepository = $walletRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->walletRepository->queryByAuthor($user),
            $page,
            WalletRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save wallet.
     *
     * @param \App\Entity\Wallet $wallet Wallet entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Wallet $wallet, User $user): void
    {
        $wallet->setCreatedAt(new \DateTime());
        $wallet->setAuthor($user);
        $this->walletRepository->save($wallet);
    }

    /**
     * Delete wallet.
     *
     * @param \App\Entity\Wallet $wallet Wallet entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Wallet $wallet): void
    {
        $this->walletRepository->delete($wallet);
    }

    /**
     * Calculate baance.
     */
    public function balance(User $user)
    {
        $existingWallets = $this->walletRepository->queryByAuthor($user)->getQuery()->getResult();
        $expense = 0;
        $income = 0;

        foreach ($existingWallets as $existingWallet) {
            if ('income' === $existingWallet->getTransactionType()->getName()) {
                $income += $existingWallet->getAmount();
            } else {
                $expense += $existingWallet->getAmount();
            }
        }
        $balance = abs($income - $expense);

        return $balance;
    }
}
