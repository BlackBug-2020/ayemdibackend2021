<?php

namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;
trait ExceptionTrait
{
    public function apiException($request,$e)
    {
        if ($this->isModel($e)){
            return response()->json([
                'error' => 'Model not found'
            ],404);
        }
            if ($this->isHttp($e)){
                return response()->json([
                    'error' => 'Incorrect route'
                ],404);

            }
                return parent::render($request, $e);
    }

    protected function isModel($e)
    {
        return $e instanceof ModelNotFoundException;
    }
    protected function isHttp($e)
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function ModelResponse($e)
    {

    }
    protected function HttpResponse($e)
    {

    }

}
