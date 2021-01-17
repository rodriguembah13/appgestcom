<?php

namespace App\Repository;

use App\Entity\FactureLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FactureLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureLine[]    findAll()
 * @method FactureLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureLineRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureLine::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(FactureLine $factureLine): ?FactureLine
    {

        $this->registry->getEntityManager()->persist($factureLine);
        $this->registry->getEntityManager()->flush();

        return $factureLine;
    }
}
