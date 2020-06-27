<?php
/**
 * Account controller.
 */

namespace App\Controller;

use App\Form\AccountType;
use App\Entity\User;
//use App\Repository\AdminRepository;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use App\Service\AdminService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Admin service.
     *
     * @var \App\Service\AdminService
     */
    private $adminService;

    /**
     * AdminController constructor.
     *
     * @param \App\Service\AdminService $adminService Admin service
     */
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP request
     * @param \App\Repository\AdminRepository           $adminRepository Admin repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator       Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="show_users",
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->adminService->createPaginatedList($request->query->getInt('page', 1));

        return $this->render(
            'admin/users.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\User $admin Account entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="admin_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted(
     *     "VIEW",
     *     subject="admin",
     * )
     */
    public function show(User $user): Response
    {
        return $this->render(
            'admin/show.html.twig',
            ['admin' => $user]
        );
    }

//    /**
//     * Create action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP request
//     * @param \App\Repository\AdminRepository          $adminRepository Account repository
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     *
//     * @Route(
//     *     "/create",
//     *     methods={"GET", "POST"},
//     *     name="admin_create",
//     * )
//     */
//    public function create(Request $request, AdminRepository $adminRepository): Response
//    {
//        $admin = new User();
//        $form = $this->createForm(AccountType::class, $admin);
//        $form->handleRequest($request);
//
//        $user = $this->getAccount();
//        $balance = $this->adminService->balance($user);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $transactionType = $form->getNormData()->getTransactionType()->getName();
//            $amount = $form->getNormData()->getAmount();
//
//            if ('expense' === $transactionType && $balance < $amount) {
//                $this->addFlash('error', 'message_too_much_substracted');
//            } else {
//                $this->adminService->save($user, $user);
//
//                $this->addFlash('success', 'message_created_successfully');
//
//                return $this->redirectToRoute('admin_index');
//            }
//        }
//
//        return $this->render(
//            'admin/create.html.twig',
//            [
//                'form' => $form->createView(),
//                'balance' => $balance,
//            ]
//        );
//    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\UserRepository            $userRepository Account repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/user_edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="user",
     *     )
     */
    public function edit(Request $request, UserRepository $userRepository, User $user): Response
    {
        $form = $this->createForm(AccountType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminService->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_show');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Account                       $admin      Account entity
     * @param \App\Repository\AdminRepository           $repository Account repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/admin_delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_delete",
     * )
     *
     */
    public function delete(Request $request, User $user, AdminRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($user);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('show_users');
        }

        return $this->render(
            'admin/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
