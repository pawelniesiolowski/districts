<?php

namespace Districts\Service;


class Logger
{
    static private $loggerInstance;
    private $fileDesc;

    private function __construct(string $fileName)
    {
        $this->openFile($fileName);
        $this->writeLine($this->createStartMessage());
    }

    static public function logException($e)
    {
        $logger = self::getInstance();
        $message = $logger->createMessageFromException($e);
        $date = $logger->createCurrentDate();
        $logger->writeLine($message);
        $logger->writeLine($date);
    }

    static public function logTextMessage(string $message)
    {
        $logger = self::getInstance();
        $trace = debug_backtrace(false, 1);
        $traceMessage = "(file: {$trace[0]['file']}, line: {$trace[0]['line']})";
        $info = "$message $traceMessage";
        $date = $logger->createCurrentDate();
        $logger->writeLine($info);
        $logger->writeLine($date);
    }

    private function openFile(string $fileName)
    {
        $this->fileDesc = fopen(__DIR__ . '/../../core/' . $fileName, 'a');
    }

    private function createStartMessage()
    {
        return '========================STARTING========================';
    }

    private function writeLine(string $text)
    {
        $line = $text . PHP_EOL;
        if ($this->fileDesc) {
            fwrite($this->fileDesc, $line);
        }
    }

    static private function getInstance()
    {
        if (!isset(self::$loggerInstance)) {
            self::$loggerInstance = new Logger('log.txt');
        }
        return self::$loggerInstance;
    }

    /**
     * @param \Exception $e
     * @return string
     */
    private function createMessageFromException($e): string
    {
        $message = $e->getMessage();
        $code = $e->getCode();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTraceAsString();
        return "$message (code: $code, file: $file, line: $line)" . PHP_EOL . "Trace: $trace";
    }

    private function createCurrentDate()
    {
        $date = new \DateTime();
        $date = $date->format('d-m-Y H:i:s');
        return $date;
    }

    public function __destruct()
    {
        $this->writeLine($this->createEndMessage());
        fclose($this->fileDesc);
    }

    private function createEndMessage()
    {
        return '========================ENDING========================';
    }
}