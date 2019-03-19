<?php

namespace App\Controller;


use App\Entity\Customers;
use App\Repository\CustomersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class CustomersController extends ApiController
{
  /**
  * @Route("/api/")
  */
  public function indexAction()
  {
    return $this->respond(0,[],['nothing to load']);

  }

  /**
  * @Route("/api/customers", methods="GET")
  */
  public function customersAction(Request $request, CustomersRepository $customersRepository)
  {
      $customers = $customersRepository->getCustomers();
      $m=[];
      if(!empty($customers))
      {
        $success=1;
      }
      else
      {
        $success=0;
        $m[]="no data";
      }

      $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$customers]));
      return $this->respond($success,$customers,$m);
  }

}
