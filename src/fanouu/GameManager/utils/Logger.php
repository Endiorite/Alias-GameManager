<?php

namespace fanouu\GameManager\utils;

class Logger
{

    public function __construct(
        private readonly string $prefix
    )
    {
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function emergency($message){
        $this->log(LogLevel::EMERGENCY, $message);
    }

    public function alert($message){
        $this->log(LogLevel::ALERT, $message);
    }

    public function critical($message){
        $this->log(LogLevel::CRITICAL, $message);
    }

    public function error($message){
        $this->log(LogLevel::ERROR, $message);
    }

    public function warning($message){
        $this->log(LogLevel::WARNING, $message);
    }

    public function notice($message){
        $this->log(LogLevel::NOTICE, $message);
    }

    public function info($message){
        $this->log(LogLevel::INFO, $message);
    }

    public function debug($message){
        $this->log(LogLevel::DEBUG, $message);
    }

    public function log($level, $message){
        echo "[". strtoupper($this->getPrefix()) ."][" . strtoupper($level) . "] " . $message . PHP_EOL;
    }

    public function logException(\Throwable $e, $trace = null){
        $this->critical($e->getMessage());
        echo $e->getTraceAsString();
    }
}