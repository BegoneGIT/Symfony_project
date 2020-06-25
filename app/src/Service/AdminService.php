<?php
/**
 * Account service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\AdminRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountService.
 */
class AdminService
{
    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * AdminRepository repository.
     *
     * @var \App\Repository\AdminRepository
     */
    private $adminRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * Account constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(PaginatorInterface $paginator, AdminRepository $adminRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->paginator = $paginator;
        $this->adminRepository = $adminRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->adminRepository->queryAll(),
            $page,
            AdminRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Change password.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->adminRepository->save($user);
    }

    /**
     * Delete user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(User $user): void
    {
        $this->adminRepository->delete($user);
    }
}
