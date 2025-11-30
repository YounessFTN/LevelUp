<?php

namespace App\Repository;

use App\Entity\Challenge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChallengeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Challenge::class);
    }

    /**
     * Récupère un défi aléatoire
     */
    public function findRandomChallenge(): ?Challenge
    {
        $count = $this->count([]);
        
        if ($count === 0) {
            return null;
        }
        
        $offset = rand(0, $count - 1);
        
        return $this->createQueryBuilder('c')
            ->setMaxResults(1)
            ->setFirstResult($offset)
            ->getQuery()
            ->getOneOrNullResult();
    }
}