<?php

namespace App\EventSubscriber;

use App\Service\VisitorHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class VisitorSubscriber implements EventSubscriberInterface
{
    /**
     * @var VisitorHelper
     */
    private $visitorHelper;

    public function __construct(VisitorHelper $visitorHelper)
    {
        $this->visitorHelper = $visitorHelper;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
       $this->setVisitor($event);
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.request' => 'onKernelRequest',
        ];
    }

    private function setVisitor(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->getSession()->get('visitorStored')) {
            $data = [
                'ip'        => $request->getClientIp(),
                'userAgent' => $request->headers->get('User-Agent'),
                'sessionId' => $request->getSession()->getId()
            ];

            if (!empty($data)) {
                $this->visitorHelper->saveVisitor($data);

                $request->getSession()->set('visitorStored', true);
            }
        }
    }
}
