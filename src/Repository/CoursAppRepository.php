<?php

namespace App\Repository;

use App\Entity\CoursApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoursApp>
 *
 * @method CoursApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursApp[]    findAll()
 * @method CoursApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursApp::class);
    }

//    /**
//     * @return CoursApp[] Returns an array of CoursApp objects
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

//    public function findOneBySomeField($value): ?CoursApp
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
