<?php

namespace App\Doctor;

use App\Doctor\Exception\DoctorAlreadyExistsException;
use App\Doctor\Exception\DoctorNotFoundException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class DoctorEventListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        match (true) {
            $exception instanceof DoctorNotFoundException      => $event->setResponse(
                new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_NOT_FOUND)
            ),
            $exception instanceof DoctorAlreadyExistsException => $event->setResponse(
                new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_CONFLICT)
            ),
            default => null,
        };
    }
}