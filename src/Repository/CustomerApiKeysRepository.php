<?php

namespace App\Repository;

use App\Entity\CustomerApiKeys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerApiKeysRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomerApiKeys::class);
    }



    /**
     * @return Products[] Returns an array of Products objects
     */

    public function getCustomerApiKey($value)
    {
        $ret =  $this->createQueryBuilder('c')
            ->andWhere('c.api_key = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
            if(!empty($ret))
            return $ret->getCustomer();
            return -1;

        exit;
    }

}
