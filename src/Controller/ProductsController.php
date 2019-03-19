<?php

namespace App\Controller;


use App\Entity\Products;
use App\Entity\Logs;
use App\Repository\ProductsRepository;
use App\Entity\CustomerApiKeys;
use App\Repository\CustomerApiKeysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;




/**
 * @Route("", service="app.controller.productscontroller")
 */
class ProductsController extends ApiController
{

  protected $user_id;
  protected $dates;
  private $logger;

  public function __construct(CustomerApiKeysRepository $customerApiKeysRepository, LoggerInterface $logger)
  {
    $this->dates = ['created_at','updated_at','deleted_at','date_of_birth'];
    //auth
    $this->user_id = $this->auth($customerApiKeysRepository);
    $this->logger = $logger;
  }


  /**
  * @Route("/api/products/{id}/", methods="GET")
  */
  public function productsAction($id, ProductsRepository $productsRepository, Request $request)
  {
    if($this->user_id==-1) return $this->respondUnauthorized();

    $success=0;
    $products=$m=[];



      if(!is_numeric($id))
      $m[]='invalid customer id';

      if(empty($m))
      {
        try {
          $products = $productsRepository->getProductsById((int)$id, (int)$this->user_id);
          if(!empty($products))
          {
            $success=1;
            if(!empty($products))
            {
              $products = $this->prod_regen($products);
            }
          }
          else
          {
            $m[]="no data";
          }
        } catch(\Exception $e)
        {
          $m[]="unable to connect";
        }

      }


      $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$products]));
      return $this->respond($success,$products,$m);
  }


 /**
  * @Route("/api/products/", methods="POST")
  */
  public function create(Request $request, ProductsRepository $productsRepository, EntityManagerInterface $em)
  {
    if($this->user_id==-1) return $this->respondUnauthorized();

    $m=[];
    $success=0;
      $request_data = $this->transformJsonBody($request);
      if(!$request_data)
      $m[]="invalid request";


      if(empty($m) && !$request_data->get('name'))
      $m[]="invalid name";

      if(!$request->get('issn'))
      $m[]="invalid issn";

      if(empty($m))
      {
        try {
          $name = preg_replace("/[^a-zA-Z0-9_\ -]/","",$request_data->get('name'));
          $p = new Products;
          $p->setName($name);
          $p->setIssn((int)$request_data->get('issn'));
          $p->setStatus('new');
          $p->setCustomer($this->user_id);
          $em->persist($p);
          $em->flush();

          if(is_numeric($p->getId()))
          {
            $success=1;
            $res = $productsRepository->getProductsById((int)$p->getId(),(int)$this->user_id);
          }
          else
          {
            $res=[];
            $m[]="unable to save";
          }
        } catch(\Exception $e)
        {
          $m[]="unable to connect";
        }

        $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$res]));
        return $this->respondCreated($success,$res,$m);


      }
      else
      {
        $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$m]));
        return $this->respondValidationError($m);
      }


  }



  /**
   * @Route("/api/products/{id}/", methods="DELETE")
   */
   public function delete($id, Request $request, ProductsRepository $productsRepository, EntityManagerInterface $em)
   {
     if($this->user_id==-1) return $this->respondUnauthorized();

     $m=[];
     $success=0;
       if(!is_numeric($id))
       $m[]='invalid product id';

       if(empty($m))
       {
         try{
           $data = $em->getRepository(Products::class)->findby(['id'=>$id,'customer'=>$this->user_id]);

           if(empty($data))
           $m[]="no such record";
           else
           $data = $data[0];
         } catch(\Exception $e)
         {
           $m[]="unable to connect";
         }

       }

       if(empty($m))
       {

         if(is_numeric($data->getId()))
         {
           $success=1;
           $data->setStatus('deleted');
           $em->flush();
           $res = ['id'=>$data->getId()];
         }
         else
         {
           $res=[];
           $m[]="unable to save";
         }

         $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$res]));
         return $this->respondCreated($success,$res,$m);


       }
       else
       {
         $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$m]));
         return $this->respondValidationError($m);
       }

   }



  /**
   * @Route("/api/products/{id}/", methods={"PUT","POST"})
   */
   public function update($id, Request $request, ProductsRepository $productsRepository, EntityManagerInterface $em)
   {
     if($this->user_id==-1) return $this->respondUnauthorized();

     $m=[];
     $success=0;
     $allowed_status = ['pending','in review','approved','inactive','deleted'];
       $request_data = $this->transformJsonBody($request);
       if(!$request_data)
       $m[]="invalid request";

       if(!is_numeric($id))
       $m[]='invalid product id';


       if(empty($m))
       {

         if(!$request_data->get('name'))
         $m[]="invalid name";

         if(!$request_data->get('issn'))
         $m[]="invalid issn";

         if($request_data->get('status') && !in_array($request_data->get('status'),$allowed_status))
         $m[]="invalid status";
       }

       if(empty($m))
       {

         try{
           $data = $em->getRepository(Products::class)->findby(['id'=>$id,'customer'=>$this->user_id]);

           if(empty($data))
           $m[]="no such record";
           else
           $data = $data[0];
         } catch(\Exception $e)
         {
           $m[]="unable to connect";
         }

       }

       if(empty($m))
       {

         $name = preg_replace("/[^a-zA-Z0-9_\ -]/","",$request->get('name'));
         $issn = (int)$request_data->get('issn');
         $status = (!in_array($request_data->get('status'),$allowed_status) ? $data['status'] : $request_data->get('status'));

         try {
           $data->setName($name);
           $data->setStatus($status);
           $data->setIssn($issn);
           $em->flush();

           if(is_numeric($data->getId()))
           {
             $success=1;
             $res = ['id'=>$data->getId()];

           }
           else
           {
             $res=[];
             $m[]="unable to save";
           }
         } catch(\Exception $e){
           $m[]="unable to connect";
         }

         $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$res]));
         return $this->respondCreated($success,$res,$m);


       }
       else
       {
         $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$m]));
         return $this->respondValidationError($m);
       }

   }

   /**
   * @Route("/api/products/", methods="GET")
   */
   public function indexAction(ProductsRepository $productsRepository, Request $request)
   {
      if($this->user_id==-1) return $this->respondUnauthorized();
      $m=[];
      try {
        $products = $productsRepository->getProductsByCustomer((int)$this->user_id);
        if(!empty($products))
        {
          $success=1;
          $products = $this->prod_regen($products);
        }
        else
        {
          $success=0;
          $m[]="no data";
          $products=[];
        }
      } catch(\Exception $e){
        $m[]="unable to connect";
      }


      $this->logger->info(json_encode(["request_id"=>md5(time().rand(111,999)),"request"=>['method'=>$request->getMethod(),'uri'=>$request->getRequestUri(),'payload'=>$request->getContent()],"response"=>$products]));
     return $this->respond($success,$products,$m);
   }

   /**
   * @Route("/api/products/", methods="PUT")
   */
   public function invalidAction()
   {
     if($this->user_id==-1) return $this->respondUnauthorized();
     return $this->respond(0,[],['please request with a GET']);
   }


   // convert unix_timestamp to datetime
   public function prod_regen($products=[])
   {
     foreach($products as $pk=>$prod)
     {
        foreach($prod as $pkey=>$p)
        {
          if(in_array($pkey,$this->dates) && $p!=null)
          {
            $products[$pk][$pkey]=date("Y-m-d H:i:s",$p);
          }
        }
     }
     return $products;
   }



}
