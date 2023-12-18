<?php

namespace App\Repository;

use App\Entity\ExerciceAppUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciceAppUser>
 *
 * @method ExerciceAppUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciceAppUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciceAppUser[]    findAll()
 * @method ExerciceAppUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceAppUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciceAppUser::class);
    }

//    /**
//     * @return ExerciceAppUser[] Returns an array of ExerciceAppUser objects
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

//    public function findOneBySomeField($value): ?ExerciceAppUser
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
