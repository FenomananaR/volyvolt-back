<?php

namespace App\Repository;

use App\Entity\ConsomationPredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConsomationPredit>
 *
 * @method ConsomationPredit|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsomationPredit|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsomationPredit[]    findAll()
 * @method ConsomationPredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsomationPreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsomationPredit::class);
    }

    public function findConsomationPredictedByClient(int $id, bool $isConsomationReel): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.consomationReel = :isConsomationReel')
            ->andWhere('c.client = :clientId')
            ->setParameters(
                    [
                    'clientId' => $id,
                    'isConsomationReel' => $isConsomationReel
                    ]
                )
            ->orderBy('c.startWeek', 'ASC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult()
        ;

       /* $qb=$this->createQueryBuilder('c');
         $qb->where(
            $qb->expr()->andX(
                $qb->expr()->eq('c.client', ':clientId'),
                $qb->expr()->eq('c.consomationReel', 'false'),
                //$qb->expr()->between('c.date',':startWeek',':endWeek'),
                ))
                ->setParameter('clientId',$id);
                ->setParameters(
                    [
                    'clientId' => $id,
                    'isConsomationReel' => $isConsomationReel
                    ]
                );
        return $qb->getQuery()
                    ->getResult()
                ;*/
    }

/*
->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('c.users', ':user'),
                    $qb->expr()->eq('c.cashoutSuccessed','true'),
                ))

*/

//    /**
//     * @return ConsomationPredit[] Returns an array of ConsomationPredit objects
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

//    public function findOneBySomeField($value): ?ConsomationPredit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
