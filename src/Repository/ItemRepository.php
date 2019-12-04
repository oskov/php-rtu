<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findByFilters(string $category = null, int $minPrice = null, int $maxPrice = null)
    {
        $querryBuilder = $this->createQueryBuilder('item');
        if ($category ?? false) {
            $querryBuilder = $querryBuilder->andWhere('item.category = :cat')->setParameter('cat', $category);
        }
        if ($minPrice ?? false) {
            $querryBuilder = $querryBuilder->andWhere('item.price >= :min')->setParameter('min', $minPrice);
        }
        if ($maxPrice ?? false) {
            $querryBuilder = $querryBuilder->andWhere('item.price <= :max')->setParameter('max', $maxPrice);
        }

        return $querryBuilder->getQuery()->getResult();
    }

    public function getDistinctCategories()
    {
        return array_column($this->createQueryBuilder('item')
            ->select('item.category')
            ->distinct()
            ->getQuery()
            ->getResult(), 'category');
    }
    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
