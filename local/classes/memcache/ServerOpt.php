<?php
namespace local\classes\memcache;

use \local\classes as local;

class ServerOpt extends local\ServerOpt {
    protected $keys;
    
    public function __construct() {
        parent::__construct();
        
        $this->config['keys'] = array(FILTER_SANITIZE_STRING);
    }
}