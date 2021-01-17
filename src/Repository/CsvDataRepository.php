<?php


namespace App\Repository;

use App\Entity\CsvData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CsvData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsvData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsvData[]    findAll()
 * @method CsvData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsvData::class);
    }
}
