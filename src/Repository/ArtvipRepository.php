<?php

namespace App\Repository;

use App\Entity\Artvip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Artvip>
 *
 * @method Artvip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artvip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artvip[]    findAll()
 * @method Artvip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtvipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artvip::class);
    }

    public function save(Artvip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Artvip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function recherchename($artnom){
        $query=$this->createQueryBuilder('a')
            ->where('a.artnom like :artnom')
            ->setParameter('artnom','%'.$artnom.'%')
            ->getQuery()
            ->getResult();
        return $query;
    }

//    /**
//     * @return Artvip[] Returns an array of Artvip objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Artvip
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
