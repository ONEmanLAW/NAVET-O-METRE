<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Model\MovieFilterDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function createFilteredQueryBuilder(MovieFilterDTO $filters): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        if ($filters->title !== null) {
            $queryBuilder
                ->andWhere('m.title LIKE :title')
                ->setParameter('title', '%' . $filters->title . '%');
        }

        if ($filters->year !== null) {
            $queryBuilder
                ->andWhere('m.releaseDate = :year')
                ->setParameter('year', $filters->year);
        }

        return $queryBuilder;
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}