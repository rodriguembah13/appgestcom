<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Product;
use App\Entity\Stock;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends BaseController
{
    private $productRepository;

    /**
     * ProductController constructor.
     *
     * @param $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($product->getStockQuantity() > 0) {
                $product->setIsInStock(true);
            }
            if (null == $product->getSlug() || '' == $product->getSlug()) {
                $slug = str_replace(' ', '_', $product->getName());
                $product->setSlug($slug);
            } else {
                $slug = str_replace(' ', '_', $product->getSlug());
                $product->setSlug($slug);
            }
            $stock = new Stock();
            $stock->setQuantity($product->getStockQuantity());
            $stock->setProduct($product);
            $stock->setLowStockQuantity($product->getLowStockQuantity());
            $entityManager->persist($product);
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
    /**
     * @Route("/delete/ajax", name="product_delete_ajax", methods={"GET"})
     *  @Security("is_granted('delete_product')")
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $em = $this->productRepository->find($request->get('item_id'));
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
