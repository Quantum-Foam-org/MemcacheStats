<?php
namespace local\classes\PostgreSQL;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
    
    public const DBTYPE = 'postgresql';
    
    protected $postgresql;
    
    public function __construct() {
        $this->postgresql;
    }
    
    public function addServer(?string $host, ?string $port) : bool {
    	try {
            $so = $this->getHostPort($host, $port);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        
    	return false;
    }
    
    public function flushCache() : bool {
        return false;
    }
    
    public function getVersion() : string  {
        return '';
    }
    
    public function getServerList() : array {
        
        return array();
    }
    
    public function getStats() : array  {
        return array();
    }
    
    public function getVariables(array $variables) : array {
        return array();
    }
    
    public function ping() : string {
        return '';
    }
}