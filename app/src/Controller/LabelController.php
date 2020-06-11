<?php
/**
 * Label controller.
 */

namespace App\Controller;

use App\Entity\Label;
use App\Form\LabelType;
use App\Repository\LabelRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LabelController.
 *
 * @Route("/label")
 */
class LabelController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\LabelRepository            $labelRepository Label repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="label_index",
     * )
     */
    public function index(Request $request, LabelRepository $labelRepository, PaginatorInterface $paginator): Response
    {
        //$pagination = $paginator->paginate(
        //$labelRepository->queryAll(),
        //    $request->query->getInt('page', 1),
        //    LabelRepository::PAGINATOR_ITEMS_PER_PAGE
        //);
        $pagination = $paginator->paginate(
            $labelRepository->queryAll(),
            $request->query->getInt('page', 1),
            LabelRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'label/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Label $label Label entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="label_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted(
     *     "VIEW",
     *     subject="label",
     * )
     */
    public function show(Label $label): Response
    {
        return $this->render(
            'label/show.html.twig',
            ['label' => $label]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\LabelRepository            $labelRepository Label repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="label_create",
     * )
     *
     * @IsGranted(
     *     "CREATE",
     *     subject="label",
     * )
     */
    public function create(Request $request, LabelRepository $labelRepository): Response
    {
        $label = new Label();
        $form = $this->createForm(LabelType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $label->setAuthor($this->getUser());
            $labelRepository->save($label);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('label_index');
        }

        return $this->render(
            'label/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Label                          $label           Label entity
     * @param \App\Repository\LabelRepository            $labelRepository Label repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{code}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="label_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="label",
     * )
     */
    public function edit(Request $request, Label $label, LabelRepository $labelRepository): Response
    {
        $form = $this->createForm(LabelType::class, $label, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $labelRepository->save($label);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('label_index');
        }

        return $this->render(
            'label/edit.html.twig',
            [
                'form' => $form->createView(),
                'label' => $label,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Label                          $label           Label entity
     * @param \App\Repository\LabelRepository            $labelRepository Label repository
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
     *     name="label_delete",
     * )
     */
    public function delete(Request $request, Label $label, LabelRepository $labelRepository): Response
    {
        $form = $this->createForm(FormType::class, $label, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $labelRepository->delete($label);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('label_index');
        }

        return $this->render(
            'label/delete.html.twig',
            [
                'form' => $form->createView(),
                'label' => $label,
            ]
        );
    }
}