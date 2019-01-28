<?php

namespace App\Repository;

use App\Entity\CategoryComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryComment[]    findAll()
 * @method CategoryComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryComment::class);
    }
}
