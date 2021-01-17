<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Entrepot;
use App\Entity\Facture;
use App\Entity\Models\CaisseCustommer;
use App\Entity\Models\OrderCustomer;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\PaymentOder;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\CustomerRepository;
use App\Repository\EntrepotRepository;
use App\Repository\FactureLineRepository;
use App\Repository\FactureRepository;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentOderRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Util\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 * @Security("is_granted('view_order')")
 */
class OrderController extends AbstractController
{
    private $productRepository;
    private $lineOrderRepository;
    private $orderRepository;
    private $factureRepository;
    private $lineFactureRepository;
    private $customerRepository;
    private $stockRepository;
    private $payementRepository;
    private $entrepotRepository;

    /**
     * OrderController constructor.
     *
     * @param $productRepository
     * @param $lineOrderRepository
     * @param $orderRepository
     * @param $factureRepository
     * @param $lineFactureRepository
     */
    public function __construct(EntrepotRepository $entrepotRepository, PaymentOderRepository $paymentOderRepository, StockRepository $stockRepository, CustomerRepository $customerRepository, ProductRepository $productRepository, OrderLineRepository $lineOrderRepository, OrderRepository $orderRepository, FactureRepository $factureRepository, FactureLineRepository $lineFactureRepository)
    {
        $this->productRepository = $productRepository;
        $this->lineOrderRepository = $lineOrderRepository;
        $this->orderRepository = $orderRepository;
        $this->factureRepository = $factureRepository;
        $this->lineFactureRepository = $lineFactureRepository;
        $this->customerRepository = $customerRepository;
        $this->stockRepository = $stockRepository;
        $this->payementRepository = $paymentOderRepository;
        $this->entrepotRepository = $entrepotRepository;
    }

    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     * @Security("is_granted('create_order')")
     */
    public function new(Request $request): Response
    {
        $products = $this->productRepository->findAll();
        $createlineorder = new OrderLine();
        $form_lineorder = $this->createFormBuilder($createlineorder)
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'multiple' => false,
                'choices' => $products,
                'placeholder' => 'table.product',
                'translation_domain' => 'order',
                'label' => 'table.product',
                'required' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])
            ->add('quantity', NumberType::class, [
                'translation_domain' => 'order',
                'label' => 'table.quantity',
            ])->add('tauxtva', NumberType::class, [
                'translation_domain' => 'order',
                'label' => 'table.tauxtva',
            ])->getForm();
        $form_lineorder->handleRequest($request);
        /*  $customer = new CaisseCustommer();
          $form_customer = $this->createFormBuilder($customer)
              ->add('customer', EntityType::class, [
                  'class' => Customer::class,
                  'multiple' => false,
                  'label' => 'table.customer',
                  'translation_domain' => 'order',
                  'required' => true,
                  'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
              ])->getForm();
          $form_customer->handleRequest($request);*/
        $ordercustomer = new OrderCustomer();
        $form_ordersupply = $this->createFormBuilder($ordercustomer)
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'multiple' => false,
                'label' => 'table.customer',
                'translation_domain' => 'order',
                'required' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])->add('entrepot', EntityType::class, [
                'class' => Entrepot::class,
                'multiple' => false,
                'label' => 'table.entrepot',
                'translation_domain' => 'order',
                'required' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])->add('datelivraison', DateType::class, [
                'label' => 'table.date_livraison',
                'translation_domain' => 'order',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
            ])->getForm();
        $form_ordersupply->handleRequest($request);

        return $this->render('order/new.html.twig', [
            'form' => $form_lineorder->createView(),
            'form_order' => $form_ordersupply->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        $lines = $this->lineOrderRepository->findBy(['order' => $order]);
        $createPayement = new PaymentOder();
        $form_payment = $this->createFormBuilder($createPayement)
            ->add('amount')
            ->add('methodPayment', ChoiceType::class, [
                'choices' => ['CARTE BANCAIRE' => 'CARTE BANCAIRE', 'CASH' => 'CASH', 'VIREMENT BANCAIRE' => 'VIREMENT BANCAIRE', 'MOBIL MONEY' => 'MOBIL MONEY'],
                'label' => 'Methode',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
            ])->getForm();
        //var_dump($form_payment);
        $paymentorders = $this->payementRepository->findBy(['idOrder' => $order->getId(), 'code' => 'order_customer']);

        return $this->render('order/show.html.twig', [
            'order' => $order,
            'paymentorders' => $paymentorders,
            'orders' => $lines,
            'form_payment' => $form_payment->createView(),
        ]);
    }

    /**
     * @Route("/caisse/init", name="order_caisse", methods={"GET","POST"})
     */
    public function caisse(Request $request): Response
    {
        $products = $this->productRepository->findAll();
        $createlineorder = new OrderLine();
        $form_lineorder = $this->createFormBuilder($createlineorder)
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'multiple' => false,
                'choices' => $products,
                'placeholder' => 'titre.classe_select',
                'translation_domain' => 'inscription',
                'label' => 'table.classe',
                'required' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])
            ->add('quantity')->getForm();
        $form_lineorder->handleRequest($request);
        $customer = new CaisseCustommer();
        $form_customer = $this->createFormBuilder($customer)
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'multiple' => false,
                'label' => 'Customer',
                'required' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])->getForm();
        $form_customer->handleRequest($request);

        return $this->render('order/caisse.html.twig', [
            'form' => $form_lineorder->createView(),
            'form_customer' => $form_customer->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/change", name="order_change_status", methods={"GET"})
     * @Security("is_granted('create_order')")
     */
    public function changeStatus(Request $request, Order $order): Response
    {
        $order->setStatus($request->get('status'));

        $this->getDoctrine()->getManager()->flush();
        $url = $this->generateUrl('order_show', ['id' => $order->getId()]);

        return $this->redirect($url);
    }

    /**
     * @Route("/{id}/addpayment", name="order_add_payment", methods={"GET"})
     * @Security("is_granted('create_order')")
     */
    public function addPayment(Request $request, Order $order): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        // $order->setStatus($request->get('amount'));
        $form = $request->get('form');
        $amount = $form['amount'];
        $method = $form['methodPayment'];
        $date = $form['createdAt'];
        $currency = $request->get('currency');
        $paymentOrder = new PaymentOder();
        $paymentOrder->setCode('order_customer');
        $paymentOrder->setIdOrder($order->getId());
        //$paymentOrder->setEmployee($this->getUser()->getEmployee());
        $paymentOrder->setAmount($amount);
        $paymentOrder->setMethodPayment($method);
        //$paymentOrder->setCreatedAt($date);
        $paymentOrder->setCreatedAt(DateTime::getDateTime($date));
        $entityManager->persist($paymentOrder);
        $this->getDoctrine()->getManager()->flush();
        $url = $this->generateUrl('order_show', ['id' => $order->getId()]);

        return $this->redirect($url);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     * @Security("is_granted('delete_order')")
     */
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index');
    }

    /**
     * @Route("/get/getproduct", name="ordergetproduct", methods={"GET"})
     */
    public function getProduct(Request $request): JsonResponse
    {
        $product = $this->productRepository->find($request->query->getInt('id_product'));
        // $year = $this->yearRepository->find($request->query->getInt('id_year'));

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
     * @Route("/post/saveorder", name="ordersaveproduct", methods={"GET","POST"})
     * @Security("is_granted('create_order')")
     */
    public function postProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ob = $data['ob'];
        $customer = $this->customerRepository->find($data['customer']);
        $entrepot = $this->entrepotRepository->find($data['entrepot']);
        if (null == $entrepot || null == $customer) {
            return new JsonResponse('error', 404);
        }
        $total = 0.0;
        $facture = new Facture();
        $order = new Order();
        $order->setCreatedBy($this->getUser());
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
            //$stock = $this->stockRepository->findOneBy(['product' => $product]);
           // $stock->setQuantity($product->getStockQuantity() - $quantity);
           // $this->stockRepository->create($stock);
           // $product->setStockQuantity($product->getStockQuantity() - $quantity);
           // $this->productRepository->create($product);
        }
        $order->setTotal($total);
        $order->setOrderKey(base64_encode('order_'.$customer->getFirstname().'_'.$order->getId()));
        $this->orderRepository->create($order);

        return new JsonResponse($order, 200);
    }
    /**
     * @Route("/delete/ajax", name="order_delete_ajax", methods={"GET"})
     *  @Security("is_granted('delete_order')")
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $em = $this->orderRepository->find($request->get('item_id'));
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $lines = $this->lineOrderRepository->findBy(['order' => $em]);
            foreach ($lines as $line){
                $entityManager->remove($line);
            }
            $entityManager->remove($em);
            $entityManager->flush();
            $this->addFlash('success', 'operation effectue avec success');
        } catch (\Exception $exception) {
            $this->addFlash('error', 'operation impossible'.$exception->getMessage());
        }

        return new JsonResponse('success', 200);
    }
}
