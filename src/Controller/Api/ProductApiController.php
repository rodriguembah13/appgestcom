<?php

namespace App\Controller\Api;

use App\Controller\Base\BaseApiController;
use App\Entity\Product;
use App\Form\Api\ProductApitype;
use App\Service\ProductService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductApiController extends BaseApiController
{
    /**
     * @var NormalizerInterface
     */
    protected $errorNormalizer;
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * ProductApiController constructor.
     */
    public function __construct(NormalizerInterface $errorNormalizer, ProductService $productService)
    {
        $this->errorNormalizer = $errorNormalizer;
        $this->productService = $productService;
    }

    /**
     * @Rest\Get("/api/products", name="products")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function index()
    {
        return View::create($this->productService->findAll(), Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Get("/api/products/{id}", name="productsballyentrepot")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     *
     * @param Request $request
     * @return View
     */
    public function getAllbyEntrepot(Request $request)
    {
        return View::create($this->productService->findAllByEntrepot($request->get('id')), Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Get("/api/products/stock/{entrepot}", name="productsinstockentrepot")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     *
     * @return View
     */
    public function getAllInStockbyEntrepot(Request $request)
    {
        return View::create($this->productService->findAllStockByEntrepot($request->get('entrepot')), Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Get("/api/products/stock/{product}/{entrepot}", name="productsbyentrepot")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     *
     * @return View
     */
    public function getOneInEntrepot(Request $request)
    {
        return View::create($this->productService->findStockInEntrepot($request->get('product'), $request->get('entrepot')), Response::HTTP_ACCEPTED);
    }

    /**
     * Creates new Product resource.
     *
     * @Rest\Post("/api/products")
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function postAction(Request $request): View
    {
        try {
            $form = $this->createForm(ProductApitype::class, new Product());
            $data = $this->getJsonDecodedFromRequest($request);
            $data['created_by'] = $this->getUserIdFromToken($request);

            $form->submit($data);
            /*if (!$form->isValid()) {
                $response = $this->getErrors($this->errorNormalizer, $form, Response::HTTP_BAD_REQUEST);

                return View::create($response, Response::HTTP_BAD_REQUEST);
            }*/
            $this->productService->create($form->getNormData());

            return View::create($data, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
