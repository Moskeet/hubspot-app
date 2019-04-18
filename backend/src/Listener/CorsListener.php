<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsListener
{
    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $responseHeaders = $event->getResponse()->headers;

        if (strpos($event->getRequest()->getUri(), '/api/') !== false) {
            $responseHeaders->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
            $responseHeaders->set('Access-Control-Allow-Origin', '*');
            $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        }
    }
}
