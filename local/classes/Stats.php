<?php
namespace local\classes;

use \cli\traits\utility as utility;

interface Stats
{
    use utility\Output;
    
    public function addServer(string $host, int $port) : bool;
    
    public function flushCache() : bool;
    
    public function getVersion();
    
    public function getServerList();
    
    public function getStats();
    
    public function getVariables(array $variables) : array;
}
