<?php

namespace App\Controller;

use App\Entity\Entrepot;
use App\Entity\Fournisseur;
use App\Entity\Models\OrderSupply;
use App\Entity\OrderFournisseur;
use App\Entity\OrderFournisseurLine;
use App\Entity\PaymentOder;
use App\Entity\Product;
use App\Entity\Stock;
use App\Form\OrderFournisseurType;
use App\Repository\EntrepotRepository;
use App\Repository\FactureLineRepository;
use App\Repository\FactureRepository;
use App\Repository\FournisseurRepository;
use App\Repository\OrderFournisseurLineRepository;
use App\Repository\OrderFournisseurRepository;
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
 * @Route("/orderfournisseur")
 * @Security("is_granted('view_order')")
 */
class OrderFournisseurController extends AbstractController
{
    private $productRepository;
    private $lineOrderRepository;
    private $orderRepository;
    private $factureRepository;
    private $lineFactureRepository;
    private $fournisseurRepository;
    private $stockRepository;
    private $entrepotRepository;
    private $paymentOrderRepository;

    /**
     * OrderController constructor.
     *
     * @param $productRepository
     * @param $lineOrderRepository
     * @param $orderRepository
     * @param $factureRepository
     * @param $lineFactureRepository
     */
    public function __construct(PaymentOderRepository $paymentOderRepository, EntrepotRepository $entrepotRepository, StockRepository $stockRepository, FournisseurRepository $fournisseurRepository, ProductRepository $productRepository, OrderFournisseurLineRepository $lineOrderRepository, OrderFournisseurRepository $orderRepository, FactureRepository $factureRepository, FactureLineRepository $lineFactureRepository)
    {
        $this->productRepository = $productRepository;
        $this->lineOrderRepository = $lineOrderRepository;
        $this->orderRepository = $orderRepository;
        $this->factureRepository = $factureRepository;
        $this->lineFactureRepository = $lineFactureRepository;
        $this->fournisseurRepository = $fournisseurRepository;
        $this->stockRepository = $stockRepository;
        $this->entrepotRepository = $entrepotRepository;
        $this->paymentOrderRepository = $paymentOderRepository;
    }

    /**
     * @Route("/", name="order_fournisseur_index", methods={"GET"})
     */
    public function index(OrderFournisseurRepository $orderFournisseurRepository): Response
    {
        return $this->render('order_fournisseur/index.html.twig', [
            'order_fournisseurs' => $orderFournisseurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_fournisseur_new", methods={"GET","POST"})
     * @Security("is_granted('create_order')")
     */
    public function new(Request $request): Response
    {
        $products = $this->productRepository->findAll();
        $createlineorder = new OrderFournisseurLine();
        $form_lineorder = $this->createFormBuilder($createlineorder)
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
            ])->add('tauxtva', NumberType::class, [
                'translation_domain' => 'order',
                'label' => 'table.tauxtva',
                'required' => false,
            ])->add('tauxremise', NumberType::class, [
                'translation_domain' => 'order',
                'label' => 'table.tauxremise',
                'required' => false,
            ])->getForm();
        $form_lineorder->handleRequest($request);
        $ordersupply = new OrderSupply();
        $form_ordersupply = $this->createFormBuilder($ordersupply)
            ->add('supply', EntityType::class, [
                'class' => Fournisseur::class,
                'multiple' => false,
                'label' => 'table.fournisseur',
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

        return $this->render('order_fournisseur/new.html.twig', [
            //'order_fournisseur' => $orderFournisseur,
            'form' => $form_lineorder->createView(),
            'form_order' => $form_ordersupply->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_fournisseur_show", methods={"GET"})
     */
    public function show(OrderFournisseur $orderFournisseur): Response
    {
        $lines = $this->lineOrderRepository->findBy(['orderFournisseur' => $orderFournisseur]);
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
        $paymentorders = $this->paymentOrderRepository->findBy(['idOrder' => $orderFournisseur->getId(), 'code' => 'order_supply']);

        return $this->render('order_fournisseur/show.html.twig', [
            'order_fournisseur' => $orderFournisseur,
            'order_fournisseurs' => $lines,
            'paymentorders' => $paymentorders,
            'form' => $form_payment->createView(),
        ]);
    }

    /**
     * @Route("/{id}/cmdeauto", name="order_fournisseur_cmde", methods={"GET"},options={"expose"=true})
     */
    public function showcmdeauto(Entrepot $entrepot): Response
    {
        return $this->render('order_fournisseur/showcmdeauto.html.twig', [
            'order_products' => $this->stockRepository->findByEntrepot($entrepot),
            'entrepot' => $entrepot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_fournisseur_edit", methods={"GET","POST"})
     * @Security("is_granted('update_order')")
     */
    public function edit(Request $request, OrderFournisseur $orderFournisseur): Response
    {
        $form = $this->createForm(OrderFournisseurType::class, $orderFournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_fournisseur_index');
        }

        return $this->render('order_fournisseur/edit.html.twig', [
            'order_fournisseur' => $orderFournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/change", name="order_fournisseur_change_status", methods={"GET"})
     */
    public function changeStatus(Request $request, OrderFournisseur $orderFournisseur): Response
    {
        $orderFournisseur->setStatus($request->get('status'));

        $this->getDoctrine()->getManager()->flush();
        $url = $this->generateUrl('order_fournisseur_show', ['id' => $orderFournisseur->getId()]);

        return $this->redirect($url);
    }

    /**
     * @Route("/{id}/addpayment", name="order_fournisseur_add_payment", methods={"GET"})
     * @Security("is_granted('create_order')")
     */
    public function addPayment(Request $request, OrderFournisseur $orderFournisseur): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        //$orderFournisseur->setStatus($request->get('amount'));
        $form = $request->get('form');
        $amount = $form['amount'];
        $method = $form['methodPayment'];
        $date = $form['createdAt'];
        $currency = $request->get('currency');
        $paymentOrder = new PaymentOder();
        $paymentOrder->setCode('order_supply');
        $paymentOrder->setIdOrder($orderFournisseur->getId());
        //$paymentOrder->setEmployee($this->getUser()->getEmployee());
        $paymentOrder->setAmount($amount);
        $paymentOrder->setMethodPayment($method);
        //$paymentOrder->setCreatedAt($date);
        $paymentOrder->setCreatedAt(DateTime::getDateTime($date));
        $entityManager->persist($paymentOrder);
        $this->getDoctrine()->getManager()->flush();
        $url = $this->generateUrl('order_fournisseur_show', ['id' => $orderFournisseur->getId()]);

        return $this->redirect($url);
        //return $this->redirectToRoute('order_fournisseur_show');
    }

    /**
     * @Route("/{id}", name="order_fournisseur_delete", methods={"DELETE"})
     * @Security("is_granted('delete_order')")
     */
    public function delete(Request $request, OrderFournisseur $orderFournisseur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderFournisseur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderFournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_fournisseur_index');
    }

    /**
     * @Route("/post/saveorderfournisseur", name="saveorderfournisseur", methods={"GET","POST"})
     * @Security("is_granted('create_order')")
     */
    public function postOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ob = $data['ob'];
        $entrepot = $this->entrepotRepository->find($data['entrepot']);
        $supply = $this->fournisseurRepository->find($data['supply']);
        if (null == $entrepot || null == $supply) {
            return new JsonResponse('error', 404);
        }
        $total = 0.0;
        $totalTax = 0.0;
        $totalRemise = 0.0;
        $order = new OrderFournisseur();
        $order->setUpdatedAt(DateTime::getDateTime());
        $order->setCreatedAt(DateTime::getDateTime());
        $order->setEntrepot($entrepot);
        $order->setFournisseur($supply);
        $order->setStatus($data['status']);
        $order->setDateLivraison(DateTime::getDateTime($data['datelivraison']));
        $order->setPaymentMethod($data['methodpaiement']);
        $this->orderRepository->create($order);
        for ($i = 0; $i < sizeof($ob); ++$i) {
            $stock = $this->stockRepository->find($ob[$i]['id']);
            //   $stock = $this->stockRepository->findOneBy(['product' => $product, 'entrepot' => $entrepot]);

            $quantity = $ob[$i]['quantity'];
            $tauxremise = $ob[$i]['tauxremise'];
            $tauxtva = $ob[$i]['tauxtva'];
            $mixed = $stock->getProduct()->getRegularPrice() * $quantity;
            $totaltva = ($mixed * $tauxtva) / 100;
            $totalremise = ($mixed * $tauxremise) / 100;
            $total += $mixed;
            $totalTax += $totaltva;
            $totalRemise += $totalremise;
            // $stock->setQuantity($stock->getQuantity() + $quantity);
            $line = new OrderFournisseurLine();
            $line->setProduct($stock->getProduct());
            $line->setQuantity($quantity);
            $line->setOrderFournisseur($order);
            $line->setTauxremise($tauxremise);
            $line->setTauxtva($tauxtva);
            $line->setTotal($mixed + $totaltva - $totalremise);
            $this->lineOrderRepository->create($line);
            // $this->stockRepository->create($stock);
          //  $product->setStockQuantity($product->getStockQuantity() + $quantity);
            //  $this->productRepository->create($product);
        }
        $order->setTotal($total);
        $order->setTotalTax($totalTax);
        $order->setDiscountTotal($totalRemise);
        $order->setCreatedBy($this->getUser());
        $this->orderRepository->create($order);

        return new JsonResponse($order, 200);
    }

    /**
     * @Route("/delete/ajax", name="order_fournisseur_delete_ajax", methods={"GET"})
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
