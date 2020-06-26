<?php
/**
 * Wallet service.
 */

namespace App\Service;

use App\Entity\PaymentTypes;
use App\Entity\TransactionTypes;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\LabelRepository;
use App\Repository\WalletRepository;
use Doctrine\DBAL\Types\DateType;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Label repository.
     *
     * @var \App\Repository\LabelRepository
     */
    private $labelRepository;

    /**
     * Label service.
     *
     * @var \App\Service\LabelService
     */
    private $labelService;

    /**
     * PaymentTypes service.
     *
     * @var \App\Service\PaymentTypesService
     */
    private $paymentTypesService;

    /**
     * TransactionTypes service.
     *
     * @var \App\Service\TransactionTypesService
     */
    private $transactionTypesService;

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
    public function __construct(
        WalletRepository $walletRepository,
        PaginatorInterface $paginator,
        LabelRepository $labelRepository,
        LabelService $labelService,
        PaymentTypesService $paymentTypesService,
        TransactionTypesService $transactionTypesService
        ) {
        $this->walletRepository = $walletRepository;
        $this->paginator = $paginator;
        $this->labelRepository = $labelRepository;
        $this->labelService = $labelService;
        $this->paymentTypesService = $paymentTypesService;
        $this->transactionTypesService = $transactionTypesService;
    }

    /**
     * Find wallet by Id.
     *
     * @param int $id Wallet Id
     *
     * @return \App\Entity\Label|null Label entity
     */
    public function findOneById(int $id): ?Wallet
    {
        return $this->walletRepository->findOneById($id);
    }

    /**
     * Create paginated list.
     *
     * @param int                                                 $page    Page number
     * @param \Symfony\Component\Security\Core\User\UserInterface $user    User entity
     * @param array                                               $filters Filters array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, UserInterface $user, array $filters = [], $dates = null): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->walletRepository->queryByAuthor($user, $filters, $dates),
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
     * Calculate balance.
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
        $balance = $income - $expense;

        return $balance;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     */
    public function get_filters(Request $request, array $filters)
    {
        $filterKey = key($request->query->getAlnum('filters'));
        $filters[$filterKey] = $request->query->getAlnum('filters', '')[$filterKey];

        return $filters;
    }

    /**
     * Prepare filters for the wallets list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['wallet']) && is_numeric($filters['wallet'])) {
            $wallet = $this->findOneById(
                $filters['wallet']
            );
            if (null !== $wallet) {
                $resultFilters['wallet'] = $wallet;
            }
        }

        if (isset($filters['label']) && is_numeric($filters['label'])) {
            $label = $this->labelService->findOneById($filters['label']);
            if (null !== $label) {
                $resultFilters['label'] = $label;
            }
        }

        if (isset($filters['paymentType']) && is_numeric($filters['paymentType'])) {
            $paymentType = $this->paymentTypesService->findOneById($filters['paymentType']);
            if (null !== $paymentType) {
                $resultFilters['paymentType'] = $paymentType;
            }
        }

        if (isset($filters['transactionType']) && is_numeric($filters['transactionType'])) {
            $transactionType = $this->transactionTypesService->findOneById($filters['transactionType']);
            if (null !== $transactionType) {
                $resultFilters['transactionType'] = $transactionType;
            }
        }


        return $resultFilters;
    }
}
