<?php

namespace App\Repository;

use App\Entity\Entrepot;
use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Stock $stock): ?Stock
    {
        $this->registry->getEntityManager()->persist($stock);
        $this->registry->getEntityManager()->flush();

        return $stock;
    }

    /**
     * @return Stock[] Returns an array of Classe objects
     */
    public function findByEntrepot(Entrepot $value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.quantity <= s.low_stock_quantity')
            ->andWhere('s.entrepot = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
            ;
    }
}
