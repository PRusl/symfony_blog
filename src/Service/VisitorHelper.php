<?php

namespace App\Service;

use App\Entity\Visitor;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function saveVisitor(Request $request)
    {
        $em = $this->registry->getManager();

        $visitor = new Visitor();
        $visitor
            ->setIp($request->getClientIp())
            ->setUserAgent($this->prepareAgent($request->headers->get('User-Agent')))
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

    private function prepareAgent(string $userAgent)
    {
        $browsers = ["Chrome", "Firefox", "Safari", "Opera",
            "MSIE", "Trident", "Edge"];

        foreach ($browsers as $browser) {
            if (strpos($userAgent, $browser) !== false) {
                return $browser;
            }
        }

        return $userAgent;
    }
}