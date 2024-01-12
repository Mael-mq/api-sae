<?php

namespace App\Repository;

use App\Entity\VaultSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VaultSheet>
 *
 * @method VaultSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method VaultSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method VaultSheet[]    findAll()
 * @method VaultSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaultSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VaultSheet::class);
    }

    public function findAllWithPagination($offset, $limit) {
        $qb = $this->createQueryBuilder('b')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return VaultSheet[] Returns an array of VaultSheet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VaultSheet
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
