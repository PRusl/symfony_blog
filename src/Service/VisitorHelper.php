<?php

namespace App\Service;

use App\Entity\Visitor;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VisitorHelper
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function saveVisitor(array $data)
    {
        $data = $this->prepareAgent($data);

        $em = $this->registry->getManager();

        $visitor = new Visitor();
        $visitor
            ->setIp($data['ip'])
            ->setUserAgent($data['userAgent'])
            ->setSessionId($data['sessionId'])
        ;

        $em->persist($visitor);
        $em->flush();
    }

    public function getVisitorStatistic()
    {
        $em = $this->registry->getManager();
        $repo = $em->getRepository(Visitor::class);

        return $repo->getStatisticByAgents();
    }

    private function prepareAgent(array $data)
    {
        $browsers = ["Chrome", "Firefox", "Safari", "Opera",
            "MSIE", "Trident", "Edge"];

        foreach ($browsers as $browser) {
            if (strpos($data['userAgent'], $browser) !== false) {
                $data['userAgent'] = $browser;

                return $data;
            }
        }

        return $data;
    }
}