<?php

namespace App\Repository;

use App\Entity\Consomation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consomation>
 *
 * @method Consomation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consomation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consomation[]    findAll()
 * @method Consomation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsomationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consomation::class);
    }

    public function findAllConsomationByStartAndEndOfWeekByClient($id, $startWeek, $endWeek){
         $qb=$this->createQueryBuilder('c');
         $qb->where(
            $qb->expr()->andX(
                $qb->expr()->eq('c.clientId', ':clientId'),
                $qb->expr()->between('c.date',':startWeek',':endWeek'),
                ))
                ->setParameters(
                [
                'clientId' => $id,
                'startWeek' => $startWeek,
                'endWeek' => $endWeek
                ]
            )
                    ->getQuery()
                    ->getResult()
                ;
    }

    public function findLastConsomationByUser($id){

    return $this->createQueryBuilder('c')
    ->andWhere('c.clientId = :clientId')
    ->setParameter('clientId',$id)
    ->orderBy('c.date ', 'ASC')
    ->setMaxResults(20)
    ->getQuery()
    ->getResult()
;
}
//    /** findLastConsomationByUser
//     * @return Consomation[] Returns an array of Consomation objects
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

//    public function findOneBySomeField($value): ?Consomation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
