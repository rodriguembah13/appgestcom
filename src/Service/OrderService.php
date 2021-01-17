<?php

namespace App\Service;

use App\Entity\Facture;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Repository\CustomerRepository;
use App\Repository\EntrepotRepository;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentOderRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Util\DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    private $entrepotRepository;
    private $customerRepository;
    private $productRepository;
    private $lineOrderRepository;
    private $stockRepository;
    private $payementRepository;

    /**
     * OrderService constructor.
     * @param ProductRepository $productRepository
     * @param OrderLineRepository $orderLineRepository
     * @param StockRepository $stockRepository
     * @param PaymentOderRepository $paymentOderRepository
     * @param EntrepotRepository $entrepotRepository
     * @param CustomerRepository $customerRepository
     * @param OrderRepository $orderRepository
     */
    public function __construct(ProductRepository $productRepository, OrderLineRepository $orderLineRepository, StockRepository $stockRepository, PaymentOderRepository $paymentOderRepository, EntrepotRepository $entrepotRepository, CustomerRepository $customerRepository, OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;

        $this->entrepotRepository = $entrepotRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->lineOrderRepository = $orderLineRepository;
        $this->stockRepository = $stockRepository;
        $this->payementRepository = $paymentOderRepository;
    }

    public function getErrorIds($data): bool
    {
        $customer = $this->customerRepository->find($data['customer']);
        $entrepot = $this->entrepotRepository->find($data['entrepot']);
        if (null == $entrepot || null == $customer) {
            return true;
        }

        return false;
    }

    public function create($data): void
    {
        $ob = $data['ob'];
        $customer = $this->customerRepository->find($data['customer']);
        $entrepot = $this->entrepotRepository->find($data['entrepot']);
        /* if (null == $entrepot || null == $customer) {
             return new JsonResponse('error', 404);
         }*/
        $total = 0.0;
        $facture = new Facture();
        $order = new Order();
        $order->setCreatedBy($data['user']);
        $order->setCurrency('FCFA');
        $order->setDatePaid(DateTime::getDateTime());
        $order->setCustomer($customer);
        $order->setEntrepot($entrepot);
        $order->setStatus($data['status']);
        $order->setDateLivraison(DateTime::getDateTime($data['datelivraison']));
        $order->setPaymentMethod($data['methodpaiement']);
        $this->orderRepository->create($order);
        for ($i = 0; $i < sizeof($ob); ++$i) {
            $product = $this->productRepository->find($ob[$i]['id']);
            $quantity = $ob[$i]['quantity'];
            $taux = $ob[$i]['tauxtva'];
            $mixed = $product->getSalePrice() * $quantity;
            $total += $mixed;
            $lineOrder = new OrderLine();
            $lineOrder->setProduct($product);
            $lineOrder->setQuantity($quantity);
            $lineOrder->setTauxtva($taux);
            $lineOrder->setTotal($total);
            $lineOrder->setOrder($order);
            $this->lineOrderRepository->create($lineOrder);
        }
        $order->setTotal($total);
        $order->setOrderKey(base64_encode('order_'.$customer->getFirstname().'_'.$order->getId()));
        $this->orderRepository->create($order);
    }

    /**
     * @return Order[]
     */
    public function findAll()
    {
        return $this->orderRepository->findAll();
    }

    /**
     * @param $id
     *
     * @return Order|null
     */
    public function find($id)
    {
        return $this->orderRepository->find($id);
    }

    /**
     * @param $user
     *
     * @return Order[]
     */
    public function findByUser($user)
    {
        return $this->orderRepository->findBy(['createdBy' => $user]);
    }
}
