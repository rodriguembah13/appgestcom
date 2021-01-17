<?php

namespace App\Repository;

use App\Entity\Mouvement;
use App\Entity\MouvementLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Mouvement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mouvement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mouvement[]    findAll()
 * @method Mouvement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mouvement::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(Mouvement $mouvement): ?Mouvement
    {

        $this->registry->getEntityManager()->persist($mouvement);
        $this->registry->getEntityManager()->flush();

        return $mouvement;
    }
}
