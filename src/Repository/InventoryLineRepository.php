<?php

namespace App\Repository;

use App\Entity\InventoryLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InventoryLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventoryLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventoryLine[]    findAll()
 * @method InventoryLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryLineRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryLine::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(InventoryLine $inventoryLine): ?InventoryLine
    {

        $this->registry->getEntityManager()->persist($inventoryLine);
        $this->registry->getEntityManager()->flush();

        return $inventoryLine;
    }
}
