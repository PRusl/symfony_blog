<?php

namespace App\EventSubscriber;

use App\Service\VisitorHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class VisitorSubscriber implements EventSubscriberInterface
{
    const IS_VISITOR_STORED = 'isVisitorStored';

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

        if (!$request->getSession()->get(self::IS_VISITOR_STORED)) {
            $this->visitorHelper->saveVisitor($request);

            $request->getSession()->set(self::IS_VISITOR_STORED, true);
        }
    }
}
