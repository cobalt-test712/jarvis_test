<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

/*  Manage Exception Http */
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event) 
    {
        $exception = $event->getThrowable();
        
        $data = ["status" =>Response::HTTP_INTERNAL_SERVER_ERROR,"message" =>"Server Error"];
        if(method_exists($exception, "getStatusCode")){
            $statusCode = $exception->getStatusCode() ;
            if( $statusCode == Response::HTTP_NOT_FOUND || 
                $statusCode == Response::HTTP_METHOD_NOT_ALLOWED)
                $data = ["status" => $exception->getStatusCode(),"message" =>"Resource not found"];
        
            if( $statusCode == Response::HTTP_BAD_REQUEST )
                $data = ["status" => $exception->getStatusCode(),"message" =>"Bad Request"];
        }
        $response = new JsonResponse($data);
        $event->setResponse($response);    
    }

}