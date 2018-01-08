<?php

declare(strict_types = 1);

namespace Gogart\Http\Request\Listener;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BodyListener
{
    /**
     * @param GetResponseEvent $event
     *
     * @throws \LogicException
     * @throws BadRequestHttpException
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();
        $contentType = $request->headers->get('Content-Type', '');

        if (strtolower($contentType) !== 'application/json') {
            return;
        }

        $content = $request->getContent();

        if (empty($content)) {
            return;
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid message received');
        }

        $request->request = new ParameterBag($data);
    }
}
