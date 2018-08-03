<?php
namespace local\classes\memcache;

use \common\obj as obj;

class ServerOpt extends obj\Config {
    protected $ip;
    protected $port;
    protected $key;
    
    private static $instance = null;
    
    protected $config = array(
        'ip' => array(FILTER_VALIDATE_IP),
        'port' => array(FILTER_VALIDATE_INT),
        'key' => array(FILTER_SANITIZE_STRING)
    );
    
    public static function obj() : ServerOpt {
        
        if (self::$instance === null) {
            self::$instance = new ServerOpt();
        }
        
        return self::$instance;
    }
}