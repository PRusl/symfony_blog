<?php

namespace App\Service;


use App\Entity\Visitor;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\SimpleCache\CacheInterface;

class VisitorHelper
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        $this->registry = $registry;
        $this->cache = $cache;
    }

    public function saveVisitor(array $data)
    {
        $data = $this->prepareAgent($data);

        $key = md5(serialize($data));
        if ($this->cache->has($key)) {
            return;
        }

        $em = $this->registry->getManager();
        $repo = $em->getRepository(Visitor::class);

        $visitor = $repo->findOneBy($data);

        if (!$visitor) {
            $visitor = new Visitor();
            $visitor
                ->setIp($data['ip'])
                ->setUserAgent($data['userAgent'])
                ->setSessionId($data['sessionId'])
            ;

            $em->persist($visitor);
            $em->flush();

        }

        $this->cache->set($key, $visitor);
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
    }

}