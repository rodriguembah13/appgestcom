<?php

namespace App\Repository;

use App\Entity\MouvementLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MouvementLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method MouvementLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method MouvementLine[]    findAll()
 * @method MouvementLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementLineRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementLine::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(MouvementLine $mouvementLine): ?MouvementLine
    {

        $this->registry->getEntityManager()->persist($mouvementLine);
        $this->registry->getEntityManager()->flush();

        return $mouvementLine;
    }
}
