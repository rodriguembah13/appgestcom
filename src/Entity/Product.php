<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $on_sale = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_in_stock = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_decomposable = false;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockQuantity = 0;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $salePrice = 0.0;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minQuantitySale = 0;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxQuantitySale = 0;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularPrice = 0.0;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductCategory")
     */
    private $productCategories;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $stock_status = 'instock';
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $low_stock_quantity = 0;
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Fournisseur")
     */
    private $supplier;

    /**
     * Product constructor.
     *
     * @param $productCategories
     */
    public function __construct()
    {
        $this->productCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getOnSale()
    {
        return $this->on_sale;
    }

    /**
     * @param mixed $on_sale
     */
    public function setOnSale($on_sale): void
    {
        $this->on_sale = $on_sale;
    }

    /**
     * @return mixed
     */
    public function getIsInStock()
    {
        return $this->is_in_stock;
    }

    /**
     * @param mixed $is_in_stock
     */
    public function setIsInStock($is_in_stock): void
    {
        $this->is_in_stock = $is_in_stock;
    }

    /**
     * @return mixed
     */
    public function getIsDecomposable()
    {
        return $this->is_decomposable;
    }

    /**
     * @param mixed $is_decomposable
     */
    public function setIsDecomposable($is_decomposable): void
    {
        $this->is_decomposable = $is_decomposable;
    }

    /**
     * @return mixed
     */
    public function getStockQuantity()
    {
        return $this->stockQuantity;
    }

    /**
     * @param mixed $stockQuantity
     */
    public function setStockQuantity($stockQuantity): void
    {
        $this->stockQuantity = $stockQuantity;
    }

    /**
     * @return mixed
     */
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * @param mixed $salePrice
     */
    public function setSalePrice($salePrice): void
    {
        $this->salePrice = $salePrice;
    }

    /**
     * @return mixed
     */
    public function getMinQuantitySale()
    {
        return $this->minQuantitySale;
    }

    /**
     * @param mixed $minQuantitySale
     */
    public function setMinQuantitySale($minQuantitySale): void
    {
        $this->minQuantitySale = $minQuantitySale;
    }

    /**
     * @return mixed
     */
    public function getMaxQuantitySale()
    {
        return $this->maxQuantitySale;
    }

    /**
     * @param mixed $maxQuantitySale
     */
    public function setMaxQuantitySale($maxQuantitySale): void
    {
        $this->maxQuantitySale = $maxQuantitySale;
    }

    /**
     * @return mixed
     */
    public function getRegularPrice()
    {
        return $this->regularPrice;
    }

    /**
     * @param mixed $regularPrice
     */
    public function setRegularPrice($regularPrice): void
    {
        $this->regularPrice = $regularPrice;
    }

    public function getStockStatus(): string
    {
        return $this->stock_status;
    }

    public function setStockStatus(string $stock_status): void
    {
        $this->stock_status = $stock_status;
    }

    public function getLowStockQuantity(): int
    {
        return $this->low_stock_quantity;
    }

    public function setLowStockQuantity(int $low_stock_quantity): void
    {
        $this->low_stock_quantity = $low_stock_quantity;
    }

    /**
     * @return Collection|ProductCategory[]
     */
    public function getProductCategories()
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories[] = $productCategory;
        }

        return $this;
    }

    public function removeProductcategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->contains($productCategory)) {
            $this->productCategories->removeElement($productCategory);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier): void
    {
        $this->supplier = $supplier;
    }

    public function __toString()
    {
        return $this->name;
    }
}
