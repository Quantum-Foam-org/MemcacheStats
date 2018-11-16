<?php
namespace local\classes;

use \common\obj as obj;

abstract class ServerOpt extends obj\Config {
    protected $ip;
    protected $port;
    
    protected $config = array(
        'ip' => array(FILTER_VALIDATE_IP),
        'port' => array(FILTER_VALIDATE_INT)
    );
}