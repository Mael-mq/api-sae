<?php

namespace App\Repository;

use App\Entity\CoursAppUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoursAppUser>
 *
 * @method CoursAppUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursAppUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursAppUser[]    findAll()
 * @method CoursAppUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursAppUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursAppUser::class);
    }

//    /**
//     * @return CoursAppUser[] Returns an array of CoursAppUser objects
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

//    public function findOneBySomeField($value): ?CoursAppUser
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
