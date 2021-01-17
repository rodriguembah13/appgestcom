<?php

namespace App\Controller;

use App\Entity\Entrepot;
use App\Entity\Models\CaisseCustommer;
use App\Entity\Mouvement;
use App\Entity\MouvementLine;
use App\Entity\OrderLine;
use App\Entity\Product;
use App\Entity\Stock;
use App\Form\MouvementType;
use App\Repository\EntrepotRepository;
use App\Repository\MouvementLineRepository;
use App\Repository\MouvementRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Util\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mouvement")
 * @Security("is_granted('view_mouvement')")
 */
class MouvementController extends AbstractController
{
    private $productRepository;
    private $mouvementRepository;
    private $stockRepository;
    private $entrepotrepository;
    private $mouvementlineRepository;

    /**
     * MouvementController constructor.
     *
     * @param $productRepository
     * @param $mouvementRepository
     * @param $stockRepository
     */
    public function __construct(MouvementLineRepository $mouvementLineRepository, EntrepotRepository $entrepotRepository, ProductRepository $productRepository, MouvementRepository $mouvementRepository, StockRepository $stockRepository)
    {
        $this->productRepository = $productRepository;
        $this->mouvementRepository = $mouvementRepository;
        $this->stockRepository = $stockRepository;
        $this->entrepotrepository = $entrepotRepository;
        $this->mouvementlineRepository = $mouvementLineRepository;
    }

    /**
     * @Route("/", name="mouvement_index", methods={"GET"})
     */
    public function index(MouvementRepository $mouvementRepository): Response
    {
        return $this->render('mouvement/index.html.twig', [
            'mouvements' => $mouvementRepository->findBy(['in_stock' => true]),
        ]);
    }

    /**
     * @Route("/out", name="mouvement_index_out", methods={"GET"})
     */
    public function indexout(MouvementRepository $mouvementRepository): Response
    {
        return $this->render('mouvement/indexout.html.twig', [
            'mouvements' => $mouvementRepository->findBy(['in_stock' => false]),
        ]);
    }

    /**
     * @Route("/new", name="mouvement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        /*$mouvement = new Mouvement();
        $form = $this->createForm(MouvementType::class, $mouvement);
        $form->handleRequest($request);*/
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
        $products = $this->productRepository->findAll();
        $createlineorder = new OrderLine();
        $form_line = $this->createFormBuilder($createlineorder)
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'multiple' => false,
                'choices' => $products,
                'placeholder' => 'table.product',
                'translation_domain' => 'order',
                'label' => 'table.product',
                'required' => false,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])
            ->add('quantity', NumberType::class, [
                'translation_domain' => 'order',
                'label' => 'table.quantity',
                'required' => false,
            ])->getForm();
        $form_line->handleRequest($request);
        /*if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mouvement);
            $entityManager->flush();

            return $this->redirectToRoute('mouvement_index');
        }*/

        return $this->render('mouvement/new.html.twig', [
            'form_line' => $form_line->createView(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new_sortie", name="mouvement_new_sorite", methods={"GET","POST"})
     */
    public function newSortie(Request $request): Response
    {
        /*$mouvement = new Mouvement();
        $form = $this->createForm(MouvementType::class, $mouvement);
        $form->handleRequest($request);*/
        $customer = new CaisseCustommer();
        $form = $this->createFormBuilder($customer)
            ->add('customer', EntityType::class, [
                'class' => Entrepot::class,
                'multiple' => false,
                'label' => 'stock.table.entrepot',
                'placeholder' => 'stock.placeholder_entrepot',
                'translation_domain' => 'stock',
                'required' => false,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])->getForm();
        $form->handleRequest($request);
        $products = $this->productRepository->findAll();
        $createlineorder = new OrderLine();
        $form_line = $this->createFormBuilder($createlineorder)
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'multiple' => false,
                'choices' => $products,
                'placeholder' => 'table.product',
                'translation_domain' => 'order',
                'label' => 'table.product',
                'required' => false,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])
            ->add('quantity', NumberType::class, [
                'translation_domain' => 'order',
                'label' => 'table.quantity',
                'required' => false,
            ])->getForm();
        $form_line->handleRequest($request);
        /*if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mouvement);
            $entityManager->flush();

            return $this->redirectToRoute('mouvement_index');
        }*/

        return $this->render('mouvement/newsortie.html.twig', [
            'form_line' => $form_line->createView(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mouvement_show", methods={"GET"})
     */
    public function show(Mouvement $mouvement): Response
    {
        return $this->render('mouvement/show.html.twig', [
            'mouvement' => $mouvement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mouvement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mouvement $mouvement): Response
    {
        $form = $this->createForm(MouvementType::class, $mouvement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mouvement_index');
        }

        return $this->render('mouvement/edit.html.twig', [
            'mouvement' => $mouvement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mouvement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mouvement $mouvement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mouvement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mouvement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mouvement_index');
    }

    /**
     * @Route("/get/getproduct", name="entrepotgetproduct", methods={"GET"})
     */
    public function getProduct(Request $request): JsonResponse
    {
        $product = $this->productRepository->find($request->query->getInt('id_product'));
        $entrepot = $this->entrepotrepository->find($request->query->getInt('id_entrepot'));
        $stock = $this->stockRepository->findOneBy(['product' => $product, 'entrepot' => $entrepot]);
        if (null == $entrepot || null == $product || null == $stock) {
            return new JsonResponse('error', 404);
        }

        $jsonData = [];
        $idx = 0;

        $responseArray[] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'on_sale' => $product->getOnSale(),
            'is_in_stock' => $product->getIsInStock(),
            'is_decomposable' => $product->getIsDecomposable(),
            'stockQuantity' => $product->getStockQuantity(),
            'salePrice' => $product->getSalePrice(),
            'minQuantitySale' => $product->getMinQuantitySale(),
            'maxQuantitySale' => $product->getMaxQuantitySale(),
            'regularPrice' => $product->getRegularPrice(),
            'stock_status' => $product->getStockStatus(),
            'low_stock_quantity' => $product->getLowStockQuantity(),
        ];

        return new JsonResponse($responseArray);
    }

    /**
     * @Route("/get/getproductout", name="entrepotgetproductout", methods={"GET"})
     */
    public function getProductSortie(Request $request): JsonResponse
    {
        $product = $this->productRepository->find($request->query->getInt('id_product'));
        $entrepot = $this->entrepotrepository->find($request->query->getInt('id_entrepot'));
        $quantity = $request->get('quantity');
        $stock = $this->stockRepository->findOneBy(['product' => $product, 'entrepot' => $entrepot]);
        if (null == $entrepot || null == $product || null == $stock) {
            return new JsonResponse('error', 404);
        }
        if ($quantity > $stock->getQuantity()) {
            return new JsonResponse('Stock insuffissant', 404);
        }

        $jsonData = [];
        $idx = 0;

        $responseArray[] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'on_sale' => $product->getOnSale(),
            'is_in_stock' => $product->getIsInStock(),
            'is_decomposable' => $product->getIsDecomposable(),
            'stockQuantity' => $product->getStockQuantity(),
            'salePrice' => $product->getSalePrice(),
            'minQuantitySale' => $product->getMinQuantitySale(),
            'maxQuantitySale' => $product->getMaxQuantitySale(),
            'regularPrice' => $product->getRegularPrice(),
            'stock_status' => $product->getStockStatus(),
            'low_stock_quantity' => $product->getLowStockQuantity(),
        ];

        return new JsonResponse($responseArray);
    }

    /**
     * @Route("/post/savemouvement", name="savemouvement", methods={"GET","POST"})
     * @Security("is_granted('create_mouvement')")
     */
    public function postProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ob = $data['ob'];
        $entrepot = $this->entrepotrepository->find($data['entrepot']);
        if (null == $entrepot) {
            return new JsonResponse('error', 404);
        }
        $total = 0;
        $mvt = new Mouvement();
        $now = DateTime::getDateTime();
        $mvt->setInStock(true);
        $mvt->setUpdatedAt(DateTime::getDateTime());
        $mvt->setCreatedAt(DateTime::getDateTime());
        $mvt->setEntrepot($entrepot);
        $this->mouvementRepository->create($mvt);
        for ($i = 0; $i < sizeof($ob); ++$i) {
            $product = $this->productRepository->find($ob[$i]['id']);
            $stock = $this->stockRepository->findOneBy(['product' => $product, 'entrepot' => $entrepot]);
            if (null == $stock) {
                $stock = new Stock();
                $stock->setEntrepot($entrepot);
                $stock->setProduct($product);
                $stock->setLowStockQuantity(0);
            }
            $quantity = $ob[$i]['quantity'];
            // $mixed = $product->getSalePrice() * $quantity;
            $total += $quantity;
            $stock->setQuantity($stock->getQuantity() + $quantity);
            $lineMvt = new MouvementLine();
            $lineMvt->setProduct($product);
            $lineMvt->setQuantity($quantity);
            $lineMvt->setMouvement($mvt);
            $this->mouvementlineRepository->create($lineMvt);
            $this->stockRepository->create($stock);
            $product->setStockQuantity($product->getStockQuantity() + $quantity);
            //  $this->productRepository->create($product);
        }
        $mvt->setInQuantity($total);
        $mvt->setLibelle('MV_in_'.$mvt->getId().'_'.$now->format('Y-m-d'));
        $this->mouvementRepository->create($mvt);

        return new JsonResponse($mvt, 200);
    }

    /**
     * @Route("/post/savemouvementout", name="savemouvementout", methods={"GET","POST"})
     * @Security("is_granted('create_mouvement')")
     */
    public function postProductout(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ob = $data['ob'];
        $entrepot = $this->entrepotrepository->find($data['entrepot']);
        if (null == $entrepot) {
            return new JsonResponse('error', 404);
        }
        $total = 0;
        $mvt = new Mouvement();
        $now = DateTime::getDateTime();
        $mvt->setInStock(false);
        $mvt->setUpdatedAt(DateTime::getDateTime());
        $mvt->setCreatedAt(DateTime::getDateTime());
        $mvt->setEntrepot($entrepot);
        $this->mouvementRepository->create($mvt);
        for ($i = 0; $i < sizeof($ob); ++$i) {
            $product = $this->productRepository->find($ob[$i]['id']);
            $stock = $this->stockRepository->findOneBy(['product' => $product, 'entrepot' => $entrepot]);
            /* if (null == $stock) {
                 $stock = new Stock();
                 $stock->setEntrepot($entrepot);
                 $stock->setProduct($product);
                 $stock->setLowStockQuantity(0);
             }*/
            $quantity = $ob[$i]['quantity'];
            // $mixed = $product->getSalePrice() * $quantity;
            $total += $quantity;
            $stock->setQuantity($stock->getQuantity() - $quantity);
            $lineMvt = new MouvementLine();
            $lineMvt->setProduct($product);
            $lineMvt->setQuantity($quantity);
            $lineMvt->setMouvement($mvt);
            $this->mouvementlineRepository->create($lineMvt);
            $this->stockRepository->create($stock);
            //  $product->setStockQuantity($product->getStockQuantity() + $quantity);
            //  $this->productRepository->create($product);
        }
        $mvt->setOutQuantity($total);
        $mvt->setLibelle('MV_out_'.$mvt->getId().'_'.$now->format('Y-m-d'));
        $this->mouvementRepository->create($mvt);

        return new JsonResponse($mvt, 200);
    }

    /**
     * @Route("/delete/ajax", name="mouvement_delete_ajax", methods={"GET"})
     *  @Security("is_granted('delete_mouvement')")
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $mv = $this->mouvementRepository->find($request->get('item_id'));
        try {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($mv->getMouvementLines() as $line) {
                $entityManager->remove($line);
            }
            $entityManager->remove($mv);
            $entityManager->flush();
            $this->addFlash('success', 'operation effectue avec success');
        } catch (\Exception $exception) {
            $this->addFlash('error', 'operation impossible'.$exception->getMessage());
        }

        return new JsonResponse('success', 200);
    }
}
