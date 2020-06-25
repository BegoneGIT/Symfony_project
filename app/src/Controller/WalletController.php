<?php
/**
 * Wallet controller.
 */

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletDatesType;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use App\Service\WalletService;
use Doctrine\DBAL\Types\DateType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WalletController.
 *
 * @Route("/wallet")
 */
class WalletController extends AbstractController
{
    /**
     * Wallet service.
     *
     * @var \App\Service\WalletService
     */
    private $walletService;

    /**
     * WalletController constructor.
     *
     * @param \App\Service\WalletService $walletService Wallet service
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP request
     * @param \App\Repository\WalletRepository          $walletRepository Wallet repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator        Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="wallet_index",
     * )
     */
    public function index(Request $request, WalletRepository $walletRepository, PaginatorInterface $paginator): Response
    {

        $form = $this->createForm(WalletDatesType::class)->handleRequest($request);


        dump($form);

        $user = $this->getUser();
        $filters = [];
        $urlFilters = $request->query->getAlnum('filters', '');
        if ($urlFilters) {
            $filters = $this->walletService->get_filters($request, $urlFilters);
        }

        $pagination = $this->walletService->createPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser(),
            $filters
        );

        $balance = $this->walletService->balance($user);

        return $this->render(
            'wallet/index.html.twig',
            [
                'pagination' => $pagination,
                'balance' => $balance,
                'form' => $form->createView(),
            ]
        );
    }


//    /**
//     * Search action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP request
//     * @param \App\Repository\WalletRepository          $walletRepository Wallet repository
//     * @param \Knp\Component\Pager\PaginatorInterface   $paginator        Paginator
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @Route(
//     *     "/{date}",
//     *     methods={"GET"},
//     *     name="wallet_search",
//     * )
//     */
//    public function search(Request $request, WalletRepository $walletRepository, PaginatorInterface $paginator): Response
//    {
//
//        $routeParameters = $request->attributes->get('_route_params');
//        $form = $this->createForm(WalletDatesType::class);
//
//        dump($form);
//
//        $user = $this->getUser();
//        $filters = [];
//        $urlFilters = $request->query->getAlnum('filters', '');
//        if ($urlFilters) {
//            $filters = $this->walletService->get_filters($request, $urlFilters);
//        }
//
//        $pagination = $this->walletService->createPaginatedList(
//            $request->query->getInt('page', 1),
//            $this->getUser(),
//            $filters
//        );
//
//        $balance = $this->walletService->balance($user);
//
//        return $this->render(
//            'wallet/index.html.twig',
//            [
//                'pagination' => $pagination,
//                'balance' => $balance,
//                'form' => $form,
//            ]
//        );
//    }

    /**
     * Show action.
     *
     * @param \App\Entity\Wallet $wallet Wallet entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="wallet_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted(
     *     "VIEW",
     *     subject="wallet",
     * )
     */
    public function show(Wallet $wallet): Response
    {
        return $this->render(
            'wallet/show.html.twig',
            ['wallet' => $wallet]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP request
     * @param \App\Repository\WalletRepository          $walletRepository Wallet repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="wallet_create",
     * )
     */
    public function create(Request $request, WalletRepository $walletRepository): Response
    {
        $wallet = new Wallet();
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        $user = $this->getUser();
        $balance = $this->walletService->balance($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionType = $form->getNormData()->getTransactionType()->getName();
            $amount = $form->getNormData()->getAmount();

            if ('expense' === $transactionType && $balance < $amount) {
                $this->addFlash('error', 'message_too_much_substracted');
            } else {
                $this->walletService->save($wallet, $user);

                $this->addFlash('success', 'message_created_successfully');

                return $this->redirectToRoute('wallet_index');
            }
        }

        return $this->render(
            'wallet/create.html.twig',
            [
                'form' => $form->createView(),
                'balance' => $balance,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP request
     * @param \App\Entity\Wallet                        $wallet           Wallet entity
     * @param \App\Repository\WalletRepository          $walletRepository Wallet repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="wallet_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="wallet",
     *    )
     */
    public function edit(Request $request, Wallet $wallet, WalletRepository $walletRepository): Response
    {
        $form = $this->createForm(WalletType::class, $wallet, ['method' => 'PUT']);
        $form->handleRequest($request);

        $user = $this->getUser();
        $balance = $this->walletService->balance($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionType = $form->getNormData()->getTransactionType()->getName();
            $amount = $form->getNormData()->getAmount();

            if ('expense' === $transactionType && $balance < $amount) {
                $this->addFlash('error', 'message_too_much_substracted');
            } else {
                $walletRepository->save($wallet);

                $this->addFlash('success', 'message_updated_successfully');

                return $this->redirectToRoute('wallet_index');
            }
        }

        return $this->render(
            'wallet/edit.html.twig',
            [
                'form' => $form->createView(),
                'wallet' => $wallet,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Wallet                        $wallet     Wallet entity
     * @param \App\Repository\WalletRepository          $repository Wallet repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="wallet_delete",
     * )
     *
     * @IsGranted(
     *     "DELETE",
     *     subject="wallet",
     *     )
     */
    public function delete(Request $request, Wallet $wallet, WalletRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $wallet, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($wallet);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('wallet_index');
        }

        return $this->render(
            'wallet/delete.html.twig',
            [
                'form' => $form->createView(),
                'wallet' => $wallet,
            ]
        );
    }
}
