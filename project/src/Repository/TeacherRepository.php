<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Teacher>
 *
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Teacher::class);
        $this->em = $em;
    }

    public function findAllWithFilter($instrument, $city, $difficulty, $frequence){
        $qb = $this->em->createQueryBuilder();

        $query = $qb->select('t')
            ->from('App\Entity\Teacher', 't')
            ->join('App\Entity\UserInstrument', 'ui', 'WITH', 't.User = ui.User')
        ;
        if($instrument){
            $query->andWhere('ui.Instrument = :instrument')
                ->setParameter('instrument', $instrument);
        }
        if($city){
            $query->andWhere('t.city = :city')
                ->setParameter('city', $city);
        }
        if($difficulty){
            $query->andWhere('t.difficulty = :difficulty')
                ->setParameter('difficulty', $difficulty);
        }
        if($frequence){
            $query->andWhere('t.frequence = :frequence')
                ->setParameter('frequence', $frequence);
        }
        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Teacher[] Returns an array of Teacher objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Teacher
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
