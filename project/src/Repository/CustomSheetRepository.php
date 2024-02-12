<?php

namespace App\Repository;

use App\Entity\CustomSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomSheet>
 *
 * @method CustomSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomSheet[]    findAll()
 * @method CustomSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomSheet::class);
    }

//    /**
//     * @return CustomSheet[] Returns an array of CustomSheet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CustomSheet
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
