<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Order $order): ?Order
    {

        $this->registry->getEntityManager()->persist($order);
        $this->registry->getEntityManager()->flush();

        return $order;
    }
}
