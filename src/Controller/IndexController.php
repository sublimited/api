<?php
// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class IndexController extends AbstractController
{
  /**
 * @Route("/")
 */
    public function new(Request $request)
    {
        return $this->render('index/index.html.twig', ['test'=>'testing']);
    }
}
