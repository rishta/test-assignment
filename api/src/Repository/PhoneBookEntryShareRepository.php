<?php

namespace App\Repository;

use App\Entity\PhoneBookEntryShare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhoneBookEntryShare|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneBookEntryShare|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneBookEntryShare[]    findAll()
 * @method PhoneBookEntryShare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneBookEntryShareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneBookEntryShare::class);
    }

    // /**
    //  * @return PhoneBookEntryShare[] Returns an array of PhoneBookEntryShare objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhoneBookEntryShare
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
