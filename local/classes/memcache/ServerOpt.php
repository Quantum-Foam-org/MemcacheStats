<?php
namespace local\classes\memcache;

use \local\classes as local;

class ServerOpt extends local\ServerOpt {
    protected $key;
    
    public function __construct() {
        parent::__construct();
        
        $this->config['key'] = array(FILTER_SANITIZE_STRING);
    }
}