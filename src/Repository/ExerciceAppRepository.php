<?php

namespace App\Repository;

use App\Entity\ExerciceApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciceApp>
 *
 * @method ExerciceApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciceApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciceApp[]    findAll()
 * @method ExerciceApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciceApp::class);
    }

    public function findAllWithPagination($offset, $limit) {
        $qb = $this->createQueryBuilder('b')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return ExerciceApp[] Returns an array of ExerciceApp objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExerciceApp
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
