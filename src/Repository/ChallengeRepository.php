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
     * Récupère un défi aléatoire parmi les défis publiés uniquement
     */
    public function findRandomChallenge(): ?Challenge
    {
        // Compte seulement les défis avec le statut "publié"
        $count = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.publicationStatus = :status')
            ->setParameter('status', 'publié')
            ->getQuery()
            ->getSingleScalarResult();
        
        if ($count === 0) {
            return null;
        }
        
        $offset = rand(0, $count - 1);
        
        // Récupère un défi aléatoire parmi les publiés
        return $this->createQueryBuilder('c')
            ->where('c.publicationStatus = :status')
            ->setParameter('status', 'publié')
            ->setMaxResults(1)
            ->setFirstResult($offset)
            ->getQuery()
            ->getOneOrNullResult();
    }
}