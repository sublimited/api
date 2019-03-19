<?php

namespace App\Repository;

use App\Entity\Customers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Customers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customers[]    findAll()
 * @method Customers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Customers::class);
    }


    public function getCustomers()
    {
      $customers = $this->findAll();
      $customers_arr = [];
      foreach($customers as $c)
      {
        $customers_arr[]=[
                          'id'=>$c->getId(),
                          'first_name'=>$c->getFirstName(),
                          'status'=>$c->getStatus(),
                        ];
      }
      return $customers_arr;
    }

}
