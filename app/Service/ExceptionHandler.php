<?php

namespace Districts\Service;


class ExceptionHandler
{
    public function __construct()
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    public function handleException($e)
    {
        Logger::logException($e);
        exit('Strona jest chwilowo niedostępna');
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @throws \ErrorException
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}