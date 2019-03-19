<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Products;
use App\Entity\Customers;
use App\Entity\CustomerApiKeys;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $customer_ids=[];

      foreach(['Customers','Products','CustomerApiKeys'] as $table)
      {

        switch($table)
        {
          case "Customers":
            for ($i = 0; $i < 2; $i++)
            {
                $p = new Customers;
                $p->setFirstName('Serge'.$i);
                $p->setStatus('new');
                $manager->persist($p);
                $manager->flush();

                $customer_ids[]=$p->getId();
            }
            $manager->flush();


          break;

          case "Products":
          for ($i = 0; $i < 20; $i++)
          {
              $rnd_user = $customer_ids[array_rand($customer_ids,1)];
              $p = new Products;
              $p->setName('product '.$i);
              $p->setIssn(mt_rand(10, 100));
              $p->setStatus('new');
              $p->setCustomer($rnd_user);
              $manager->persist($p);
          }
          $manager->flush();
          break;

          case "CustomerApiKeys":
          for ($i = 0; $i < 10; $i++)
          {
              $rnd_user = $customer_ids[array_rand($customer_ids,1)];
              $p = new CustomerApiKeys;

              $p->setApiKey(md5(time().rand(11111,99999).$rnd_user).rand(11111,99999));
              $p->setStatus('new');
              $p->setCustomer($rnd_user);
              $manager->persist($p);
          }
          $manager->flush();
          break;
        }


      }



    }
}
