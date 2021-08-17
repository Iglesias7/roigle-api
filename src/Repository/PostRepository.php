<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] 
     */
    public function getResponses($postId): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.parent', 'parent')
            ->andWhere('p.title is null')
            ->andWhere('parent.id = :postId')
            ->setParameter('postId', $postId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[] 
     */
    public function newest(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title is not null')
            ->orderBy('p.timestamp', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[] 
     */
    public function getAll(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title is not null')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[] 
     */
    public function getVote(): array
    {
        return $this->createQueryBuilder('p')

            ->andWhere('p.title is not null')
            ->orderBy('p.hightScore', 'DESC')
            ->orderBy('p.timestamp', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[] 
     */
    public function getActive(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title is not null')
            ->andWhere('p.title is not null')
            ->orderBy('p.timestamp', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
