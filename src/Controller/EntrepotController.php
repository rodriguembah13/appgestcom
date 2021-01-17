<?php

namespace App\Controller;

use App\Entity\Entrepot;
use App\Form\EntrepotType;
use App\Repository\EntrepotRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entrepot")
 */
class EntrepotController extends AbstractController
{
    private $entrepotrepository;

    /**
     * EntrepotController constructor.
     * @param $entrepotrepository
     */
    public function __construct(EntrepotRepository $entrepotrepository)
    {
        $this->entrepotrepository = $entrepotrepository;
    }

    /**
     * @Route("/", name="entrepot_index", methods={"GET"})
     * @Security("is_granted('view_entrepot')")
     */
    public function index(EntrepotRepository $entrepotRepository): Response
    {
        return $this->render('entrepot/index.html.twig', [
            'entrepots' => $entrepotRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="entrepot_new", methods={"GET","POST"})
     * @Security("is_granted('create_entrepot')")
     */
    public function new(Request $request): Response
    {
        $entrepot = new Entrepot();
        $form = $this->createForm(EntrepotType::class, $entrepot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entrepot);
            $entityManager->flush();

            return $this->redirectToRoute('entrepot_index');
        }

        return $this->render('entrepot/new.html.twig', [
            'entrepot' => $entrepot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entrepot_show", methods={"GET"})
     * @Security("is_granted('update_entrepot')")
     */
    public function show(Entrepot $entrepot): Response
    {
        return $this->render('entrepot/show.html.twig', [
            'entrepot' => $entrepot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="entrepot_edit", methods={"GET","POST"})
     * @Security("is_granted('update_entrepot')")
     */
    public function edit(Request $request, Entrepot $entrepot): Response
    {
        $form = $this->createForm(EntrepotType::class, $entrepot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entrepot_index');
        }

        return $this->render('entrepot/edit.html.twig', [
            'entrepot' => $entrepot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entrepot_delete", methods={"DELETE"})
     * @Security("is_granted('delete_entrepot')")
     */
    public function delete(Request $request, Entrepot $entrepot): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entrepot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entrepot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entrepot_index');
    }
    /**
     * @Route("/delete/ajax", name="entrepot_delete_ajax", methods={"GET"})
     *  @Security("is_granted('delete_entrepot')")
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $em = $this->entrepotrepository->find($request->get('item_id'));
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($em);
            $entityManager->flush();
            $this->addFlash('success', 'operation effectue avec success');
        } catch (\Exception $exception) {
            $this->addFlash('error', 'operation impossible'.$exception->getMessage());
        }

        return new JsonResponse('success', 200);
    }
}
