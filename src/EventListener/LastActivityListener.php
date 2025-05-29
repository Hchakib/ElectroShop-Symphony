<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LastActivityListener
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var int
     */
    private $timeout;

    public function __construct(Security $security, RouterInterface $router, int $timeout)
    {
        $this->security = $security;
        $this->router   = $router;
        $this->timeout  = $timeout;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        if (!$session || !$session->isStarted()) {
            return;
        }

        if (null === $this->security->getUser()) {
            return;
        }

        $now          = time();
        $lastActivity = $session->get('lastActivityTime', $now);

        if (($now - $lastActivity) > $this->timeout) {
            $session->invalidate();
            $session->getFlashBag()->add('info', 'Vous avez été déconnecté(e) pour inactivité.');

            $response = new RedirectResponse($this->router->generate('app_login'));
            $event->setResponse($response);
            return;
        }

        $session->set('lastActivityTime', $now);
    }
}
