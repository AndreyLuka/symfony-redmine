<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class CommentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param int $projectId
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findByProjectId(int $projectId, int $page = 1): Pagerfanta
    {
        $query = $this->createQueryBuilder('comment')
            ->where('comment.projectId = :projectId')
            ->setParameter('projectId', $projectId)
            ->orderBy('comment.publishedAt', 'DESC')
        ;

        return $this->createPaginator($query, $page);
    }

    /**
     * @param QueryBuilder $query
     * @param int          $page
     *
     * @return Pagerfanta
     */
    private function createPaginator(QueryBuilder $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(getenv('NUM_ITEMS'));
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
