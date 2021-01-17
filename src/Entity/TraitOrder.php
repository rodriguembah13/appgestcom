<?php

namespace App\Entity;

use App\Util\DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TraitOrder
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentMethod;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currency;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $order_key;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $total;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $refundTotal;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $shippingTotal;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $discountTotal;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $totalTax;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $amount;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $createdBy;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $date_paid;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $status;


    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->createdAt = DateTime::getDateTime();
        $this->updatedAt = DateTime::getDateTime();
    }


    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getRefundTotal()
    {
        return $this->refundTotal;
    }

    /**
     * @param mixed $refundTotal
     */
    public function setRefundTotal($refundTotal): void
    {
        $this->refundTotal = $refundTotal;
    }

    /**
     * @return mixed
     */
    public function getShippingTotal()
    {
        return $this->shippingTotal;
    }

    /**
     * @param mixed $shippingTotal
     */
    public function setShippingTotal($shippingTotal): void
    {
        $this->shippingTotal = $shippingTotal;
    }

    /**
     * @return mixed
     */
    public function getDiscountTotal()
    {
        return $this->discountTotal;
    }

    /**
     * @param mixed $discountTotal
     */
    public function setDiscountTotal($discountTotal): void
    {
        $this->discountTotal = $discountTotal;
    }

    /**
     * @return mixed
     */
    public function getTotalTax()
    {
        return $this->totalTax;
    }

    /**
     * @param mixed $totalTax
     */
    public function setTotalTax($totalTax): void
    {
        $this->totalTax = $totalTax;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getOrderKey()
    {
        return $this->order_key;
    }

    /**
     * @param mixed $order_key
     */
    public function setOrderKey($order_key): void
    {
        $this->order_key = $order_key;
    }

    /**
     * @return mixed
     */
    public function getDatePaid()
    {
        return $this->date_paid;
    }

    /**
     * @param mixed $date_paid
     */
    public function setDatePaid($date_paid): void
    {
        $this->date_paid = $date_paid;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
