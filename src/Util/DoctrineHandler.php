<?php
namespace App\Util;

use App\Entity\Logs;
use App\Repository\LogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class DoctrineHandler extends AbstractProcessingHandler
{
    private $em;
    private $channel = 'app';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function write(array $record)
    {

        if ($this->channel != $record['channel']) return;

        $log = new Logs;
        $log->setInfo($record['message']);
        $log->setCreatedAt(time());

        $this->em->persist($log);
        $this->em->flush();
    }

}
