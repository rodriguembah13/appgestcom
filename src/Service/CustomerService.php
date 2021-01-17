<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Util\DateTime;

class CustomerService
{
    const JOB_ACTIVE = true;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerService constructor.
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Creates a new Job
     *  - All new jobs will created as active
     *  - createdAt and updatedAt will be the current UTC datetime.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Customer $customer): void
    {
        $now = DateTime::getDateTime();
        $dateBorn = DateTime::getDateTime($customer->getDateborn());
        $customer->setCreatedAt($now);
        $customer->setUpdatedAt($now);
        $customer->setDateborn($dateBorn);

        // $customer->setIsActive(static::JOB_ACTIVE);

        $this->customerRepository->create($customer);
    }

    /**
     * @return Customer[]
     */
    public function findAll()
    {
        return $this->customerRepository->findAll();
    }

    /**
     * @param $id
     *
     * @return Customer|null
     */
    public function find($id)
    {
        return $this->customerRepository->find($id);
    }
}
