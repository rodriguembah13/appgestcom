<?php

namespace App\Controller\Api;

use App\Controller\Base\BaseApiController;
use App\Entity\Order;
use App\Service\OrderService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OrderApiController extends BaseApiController
{
    /**
     * @var NormalizerInterface
     */
    protected $errorNormalizer;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * OrderApiController constructor.
     * @param NormalizerInterface $errorNormalizer
     * @param OrderService $orderService
     */
    public function __construct(NormalizerInterface $errorNormalizer, OrderService $orderService)
    {
        $this->errorNormalizer = $errorNormalizer;
        $this->orderService = $orderService;
    }

    /**
     * @Rest\Get("/api/orders", name="orders")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function index()
    {
        return View::create($this->orderService->findAll(), Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Get("/api/orders/user", name="ordersbyuser")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function getOrdersByUser()
    {
        return View::create($this->orderService->findByUser($this->getUser()), Response::HTTP_ACCEPTED);
    }

    /**
     * Creates new Order resource.
     *
     * @Rest\Post("/api/orders")
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function postAction(Request $request): View
    {
        try {
            $data = $this->getJsonDecodedFromRequest($request);
            $data['user'] = $this->getUser();
            if ($this->orderService->getErrorIds($data)) {
                return View::create('Magasin or customer is null', Response::HTTP_BAD_REQUEST);
            }
            $this->orderService->create($data);

            return View::create($data, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
