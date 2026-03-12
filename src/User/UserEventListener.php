<?php

namespace App\User;

use App\User\Exception\UserAlreadyExistException;
use App\User\Exception\UserWrongCredentialsException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class UserEventListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        match (true) {
            $exception instanceof UserAlreadyExistException => $event->setResponse(
                new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_CONFLICT)
            ),
            $exception instanceof UserWrongCredentialsException => $event->setResponse(
                new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED)
            ) ,
            default => null
        };
    }
}
