<?php

namespace App\Controller\Api;

use App\Controller\Base\BaseApiController;
use App\Entity\Customer;
use App\Form\Api\CustomerApiType;
use App\Service\CustomerService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerController extends BaseApiController
{
    /**
     * @var NormalizerInterface
     */
    protected $errorNormalizer;

    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * CustomerController constructor.
     */
    public function __construct(CustomerService $customerService, NormalizerInterface $errorNormalizer)
    {
        $this->customerService = $customerService;
        $this->errorNormalizer = $errorNormalizer;
    }

    /**
     * @Rest\Get("/api/customers", name="customer")
     *
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function index()
    {
        return View::create($this->customerService->findAll(), Response::HTTP_ACCEPTED);
    }

    /**
     * Creates new Customer resource.
     *
     * @Rest\Post("/api/customers")
     * @ApiSecurity(name="apiUser")
     * @ApiSecurity(name="apiToken")
     */
    public function postAction(Request $request): View
    {
        try {
            $form = $this->createForm(CustomerApiType::class, new Customer());
            $data = $this->getJsonDecodedFromRequest($request);
            $data['created_by'] = $this->getUserIdFromToken($request);

            $form->submit($data);
            /*if (!$form->isValid()) {
                $response = $this->getErrors($this->errorNormalizer, $form, Response::HTTP_BAD_REQUEST);

                return View::create($response, Response::HTTP_BAD_REQUEST);
            }*/
            $this->customerService->create($form->getNormData());

            return View::create($data, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
