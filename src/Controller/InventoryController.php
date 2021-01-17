<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\InventoryLine;
use App\Form\InventoryType;
use App\Repository\InventoryLineRepository;
use App\Repository\InventoryRepository;
use App\Repository\ProductRepository;
use App\Util\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inventory")
 * @Security("is_granted('view_inventory')")
 */
class InventoryController extends AbstractController
{
    private $productRepository;
    private $inventoryRepository;
    private $lineInventoryRepository;

    /**
     * InventoryController constructor.
     *
     * @param $productRepository
     * @param $inventoryRepository
     * @param $lineInventoryRepository
     */
    public function __construct(ProductRepository $productRepository, InventoryRepository $inventoryRepository, InventoryLineRepository $lineInventoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->lineInventoryRepository = $lineInventoryRepository;
    }

    /**
     * @Route("/", name="inventory_index", methods={"GET"})
     */
    public function index(InventoryRepository $inventoryRepository): Response
    {
        return $this->render('inventory/index.html.twig', [
            'inventories' => $inventoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="inventory_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $inventory = new Inventory();
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inventory);
            $entityManager->flush();

            return $this->redirectToRoute('inventory_index');
        }

        return $this->render('inventory/new.html.twig', [
            'inventory' => $inventory,
            'products' => $this->productRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inventory_show", methods={"GET"})
     */
    public function show(Inventory $inventory): Response
    {
        return $this->render('inventory/show.html.twig', [
            'inventory' => $inventory,
            'lines' => $this->lineInventoryRepository->findBy(['inventory' => $inventory]),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="inventory_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Inventory $inventory): Response
    {
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inventory_index');
        }

        return $this->render('inventory/edit.html.twig', [
            'inventory' => $inventory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inventory_delete", methods={"DELETE"})
     * @Security("is_granted('delete_inventory')")
     */
    public function delete(Request $request, Inventory $inventory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inventory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($inventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('inventory_index');
    }

    /**
     * @Route("/post/saveinventory", name="saveinventory", methods={"GET","POST"})
     * @Security("is_granted('create_inventory')")
     */
    public function postInventory(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ob = $data['ob'];
        //$customer=$this->customerRepository->find($data['customer']);
        $total = 0.0;
        //  $facture = new Facture();
        $inv = new Inventory();
        $inv->setCreatedBy($this->getUser());
        $now = DateTime::getDateTime();
        $inv->setLibelle('inv_'.$now->format('yyyy-m-d'));
        $this->inventoryRepository->create($inv);
        for ($i = 0; $i < sizeof($ob); ++$i) {
            $product = $this->productRepository->find($ob[$i]['id']);
            $quantity = $ob[$i]['quantity'];
            $mixed = $product->getSalePrice() * $quantity;
            $total += $mixed;
            $lineInv = new InventoryLine();
            $lineInv->setProduct($product);
            $lineInv->setStockQuantity($product->getStockQuantity());
            $lineInv->setRealQuantity($quantity);
            $lineInv->setInventory($inv);
            $this->lineInventoryRepository->create($lineInv);
        }

        return new JsonResponse($inv, 200);
    }
    /**
     * @Route("/delete/ajax", name="inventory_delete_ajax", methods={"GET"})
     *  @Security("is_granted('delete_inventory')")
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $em = $this->inventoryRepository->find($request->get('item_id'));
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
