<?php

namespace App\Doctor;

use App\Doctor\Exception\DoctorAlreadyExistsException;
use App\Doctor\Exception\DoctorNotFoundException;
use App\Doctor\Exception\DoctorWithLicenseNumberAlreadyExistException;
use App\Doctor\Exception\DoctorWithUserIdAlreadyExistException;
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
        $statusCode =  match (true) {
            $exception instanceof DoctorNotFoundException => Response::HTTP_NOT_FOUND,
            $exception instanceof DoctorAlreadyExistsException, $exception instanceof DoctorWithLicenseNumberAlreadyExistException => Response::HTTP_CONFLICT,
            $exception instanceof DoctorWithUserIdAlreadyExistException => Response::HTTP_UNPROCESSABLE_ENTITY,
            default => null,
        };

        if (!$statusCode) {
            return;
        }

        $response = new JsonResponse(['error' => $exception->getMessage()], $statusCode);
        $event->setResponse($response);
    }
}
