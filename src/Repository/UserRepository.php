<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->registry = $registry;
    }

    /**
     * @param string $username
     *
     * @return mixed|\Symfony\Component\Security\Core\User\UserInterface|null
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username = :username')
            ->orWhere('u.email = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getSingleResult();
    }
    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(User $user): ?User
    {

        $this->registry->getEntityManager()->persist($user);
        $this->registry->getEntityManager()->flush();

        return $user;
    }
    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function update(User $user): ?User
    {

        $this->registry->getEntityManager()->persist($user);
        $this->registry->getEntityManager()->flush();

        return $user;
    }
}
