<?php
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if (app()->environment('production')) {
            return parent::render($request, $e);
        }

        return parent::render($request, $e);
    }
}