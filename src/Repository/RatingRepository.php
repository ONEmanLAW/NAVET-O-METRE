<?php

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function createFeedQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->addSelect('m')
            ->join('r.movie', 'm')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.ratedAt', 'DESC')
            ->addOrderBy('r.id', 'DESC');
    }
}
