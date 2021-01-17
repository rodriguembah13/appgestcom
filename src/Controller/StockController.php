<?php

namespace App\Controller;

use App\Entity\Entrepot;
use App\Entity\Models\CaisseCustommer;
use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\EntrepotRepository;
use App\Repository\StockRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stock")
 * @Security("is_granted('view_stock')")
 */
class StockController extends AbstractController
{
    private $stockRepository;
    private $entrepotRepository;

    /**
     * StockController constructor.
     *
     * @param $stockRepository
     * @param $entrepotRepository
     */
    public function __construct(StockRepository $stockRepository, EntrepotRepository $entrepotRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->entrepotRepository = $entrepotRepository;
    }

    /**
     * @Route("/", name="stock_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $customer = new CaisseCustommer();
        $form = $this->createFormBuilder($customer)
            ->add('customer', EntityType::class, [
                'class' => Entrepot::class,
                'multiple' => false,
                'label' => ' ',
                'placeholder' => 'stock.placeholder_entrepot',
                'translation_domain' => 'stock',
                'required' => false,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])->getForm();
        $form->handleRequest($request);

        return $this->render('stock/index.html.twig', [
            'stocks' => $this->stockRepository->findAll(),
            'entrepots' => $this->entrepotRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="stock_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('stock_index');
        }

        return $this->render('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_show", methods={"GET"})
     */
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stock $stock): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stock_index');
        }

        return $this->render('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Stock $stock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stock_index');
    }
    /**
     * @Route("/delete/ajax", name="stock_delete_ajax", methods={"GET"})
     *  @Security("is_granted('delete_stock')")
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $em = $this->stockRepository->find($request->get('item_id'));
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
