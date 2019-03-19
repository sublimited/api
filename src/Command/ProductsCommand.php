<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\Products;
use App\Repository\ProductsRepository;

class ProductsCommand extends Command
{
    protected static $defaultName = 'app:products-pending';
    private $userManager;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Check for pending products')
            ->setHelp('This command will display pending products')
            ->addArgument('week', InputArgument::REQUIRED, 'how many weeks back (integer)');
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

      $week = $input->getArgument('week');
      $ts = strtotime("-".(int)$week." week");
      $output->writeln([
           'Pending Products',
           '============================================',
           '',
       ]);

       // outputs a message followed by a "\n"
       $output->writeln('[+] getting all products that are older than '.$week.' week(s) ...');

       try {
         $products = $this->productsRepository->getAllProductsByTs($ts);
         if(!empty($products))
         {
           $output->writeln(['[+] found ('.count($products).') products']);
           foreach($products as $pk=>$p)
           {
             $updated=$created="";
             if(!is_null($p['updated_at'])) $updated = "updated on ".date("Y-m-d H:s:i",$p['updated_at']);
             if(!is_null($p['created_at'])) $created = "created on ".date("Y-m-d H:s:i",$p['created_at']);

             $output->writeln(["","\t=================","","\t[Product: $p[id] - $p[name]]","\tCustomer: ($p[customer]) $p[first_name]","\tDates: $created $updated",""]);
           }
         }
         else
         {
          $output->writeln('[+] no products available, all good');
         }
       } catch (\Exception $e) {
         $output->writeln('[-] '.$e->getMessage());
       }


       $output->writeln('[+] completed');
    }
}
