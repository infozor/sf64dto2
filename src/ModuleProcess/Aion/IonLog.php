<?php

namespace App\ModuleProcess\Aion;


class IonLog
{

    private $projectDir;
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    function logMethod($message = '')
    {
    	$this->log('process', date('d.m.Y H:i:s') . ' ' . $message . "\n");
    }
    
    public function log($type, $message)
    {
        $log_path = $this->get_log_path();
        $path = $log_path . "/"."Command";
        if (!file_exists($path))
        {
            mkdir($path);
        }

        $file = $path . '/' . $type . ".log";
        return ( bool ) file_put_contents($file, $message, FILE_APPEND) . "\n";
    }

    function get_log_path()
    {
        $path = realpath($this->projectDir . "/var/logs");
        return $path;
    }

}