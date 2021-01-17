<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Stock;
use App\Repository\EntrepotRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use App\Util\DateTime;

class ProductService
{
    const JOB_ACTIVE = true;

    /**
     * @var ProductRepository
     */
    private $productRepository;
    private $stockRepository;
    private $entrepotRepository;

    /**
     * CustomerService constructor.
     */
    public function __construct(StockRepository $stockRepository, EntrepotRepository $entrepotRepository, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
        $this->entrepotRepository = $entrepotRepository;
    }

    /**
     * Creates a new Product.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Product $product): void
    {
        $now = DateTime::getDateTime();

        $this->productRepository->create($product);
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * @param $entrepot
     *
     * @return Product[]
     */
    public function findAllByEntrepot($entrepotId): array
    {
        $entrepot = $this->entrepotRepository->find($entrepotId);
        $stocks = $this->stockRepository->findBy(['entrepot' => $entrepot]);
        $products = [];
        foreach ($stocks as $stock) {
            $products[] = $stock->getProduct();
        }

        return $products;
    }

    /**
     * @param $entrepotId
     *
     * @return Stock[]
     */
    public function findAllStockByEntrepot($entrepotId)
    {
        $entrepot = $this->entrepotRepository->find($entrepotId);
        $stocks = $this->stockRepository->findBy(['entrepot' => $entrepot]);

        return $stocks;
    }

    /**
     * @param $id
     */
    public function find($id): ?Product
    {
        return $this->productRepository->find($id);
    }

    /**
     * @param $id
     */
    public function findStockInEntrepot($productId, $entrepotId): ?Stock
    {
        $product = $this->productRepository->find($productId);
        $entrepot = $this->entrepotRepository->find($entrepotId);

        return $this->stockRepository->findOneBy(['product' => $product, 'entrepot' => $entrepot]);
    }
}
