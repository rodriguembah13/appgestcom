<?php

namespace App\Repository;

use App\Entity\OrderFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderFournisseur[]    findAll()
 * @method OrderFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderFournisseurRepository extends ServiceEntityRepository
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderFournisseur::class);
        $this->registry = $registry;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(OrderFournisseur $order): ?OrderFournisseur
    {
        $this->registry->getEntityManager()->persist($order);
        $this->registry->getEntityManager()->flush();

        return $order;
    }
}
