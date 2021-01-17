<?php

namespace App\Repository;

use App\Entity\OrderFournisseurLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderFournisseurLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderFournisseurLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderFournisseurLine[]    findAll()
 * @method OrderFournisseurLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderFournisseurLineRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderFournisseurLine::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(OrderFournisseurLine $orderLine): ?OrderFournisseurLine
    {
        $this->registry->getEntityManager()->persist($orderLine);
        $this->registry->getEntityManager()->flush();

        return $orderLine;
    }
}
