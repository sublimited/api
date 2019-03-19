<?php

namespace App\Repository;

use App\Entity\Products;
use App\Entity\Customers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Products::class);
    }



    /**
     * @return Products[] Returns an array of Products objects
     */

    public function getProductsByCustomer($user_id)
    {
        return $this->createQueryBuilder('p')
            ->select("p.id","p.issn","p.name","p.status","p.customer","p.created_at","p.updated_at")
            ->andWhere('p.customer = :customer','p.status!=:status')
            ->setParameter('customer', $user_id)
            ->setParameter('status', 'deleted')
            ->orderBy('p.id', 'desc')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    public function getAllProductsByTs($ts=0)
    {
        return $this->createQueryBuilder('p')
            ->select("p.id","p.issn","p.name","p.status","p.customer","p.created_at","p.updated_at","c.first_name","c.last_name")
            ->andWhere('p.status = :status')
            ->setParameter('status', 'pending')
            ->andWhere('p.created_at <= :ts')
            ->setParameter('ts', $ts)
            ->leftJoin(
                'App\Entity\Customers',
                'c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'p.customer = c.id'
            )
            ->orderBy('p.created_at', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }


    public function getProductsById($id,$user_id)
    {
        return $this->createQueryBuilder('p')
            ->select("p.id","p.issn","p.name","p.status","p.customer","p.created_at","p.updated_at")
            ->andWhere('p.id = :id','p.customer = :customer','p.status!=:status')
            ->setParameter('id', $id)
            ->setParameter('customer', $user_id)
            ->setParameter('status', 'deleted')
            ->getQuery()
            ->getResult()
        ;
    }

}
