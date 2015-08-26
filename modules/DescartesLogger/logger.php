<?php

namespace DescartesLogger;

class logger
{
    /**
     * Level log from RFC 5424 syslog Protocol
     */
    
    /**
     * A faire :
     * Bracher critical PHP
     */
    
    const DEBUG = 7;
    const INFO = 6;
    const NOTICE = 5;
    const WARNING = 4;
    const ERROR = 3;
    const CRITICAL = 2;
    const ALERT = 1;
    const EMERGENCY = 0;
    
    private $level;
    
    private $file;
    
    private $name;
    
    private $processus;
    
    private $levelMail;
    
    private $email;
    
    private $levels = array(
        7 => "DEBUG",
        6 => "INFO",
        5 => "NOTICE",
        4 => "WARNING",
        3 => "ERROR",
        2 => "CRITICAL",
        1 => "ALERT",
        0 => "EMERGENCY"
    );
    
    public function __construct($level, $file = "logs/descartes.log", $name = "Descartes", $levelMail = self::ERROR, $email = null)
    {
        $this->level = $level;
        $this->file = $file;
        $this->name = $name;
        $this->processus = uniqId();
        $this->levelMail = $levelMail;
        $this->email = $email;
    }
    
    public function addDebug($message)
    {
        $this->writeLog(self::DEBUG, $message);
    }
    
    public function addInfo($message)
    {
        $this->writeLog(self::INFO, $message);
    }
    
    public function addNotice($message)
    {
        $this->writeLog(self::NOTICE, $message);
    }
    
    public function addWarning($message)
    {
        $this->writeLog(self::WARNING, $message);
    }
    
    public function addError($message)
    {
        $this->writeLog(self::ERROR, $message);
    }
    
    public function addCritical($message)
    {
        $this->writeLog(self::CRITICAL, $message);
    }
    
    public function addAlert($message)
    {
        $this->writeLog(self::ALERT, $message);
    }
    
    public function addEmergency($message)
    {
        $this->writeLog(self::EMERGENCY, $message);
    }
    
    private function writeLog($logLevel, $message)
    {
        
        if ($this->checkLevel($logLevel))
        {
            $date = new \DateTime();
            $message = "[" . $date->format("Y-m-d H:i:s") . "] " . $this->name . " [" . $this->processus . "]. " . $this->levels[$logLevel] . " : " .$message ."\n";
            $file = fopen($this->file, "a+");
            fwrite($file, $message);
            fclose($file);
            if ($this->levelMail < $logLevel && $this->email)
            {
                $this->sendMail($logLevel, $message);
            }
        }
    }
    
    private function checkLevel($logLevel)
    {
        if ($this->level >= $logLevel)
        {
            return true;
        }
        return false;
    }
    
    private function sendMail($logLevel, $message)
    {
        $object = "Log " . $this->name;
        mail($this->email, $object, $message);
    }
}