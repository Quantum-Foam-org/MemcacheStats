<?php
namespace local\classes\mongodb;

use \local\classes as local;

class ServerOpt extends local\ServerOpt {
    protected $collection;
    protected $db;
    
    public function __construct() {
        $this->config['collection'] = array(FILTER_SANITIZE_STRING);
        $this->config['db'] = array(FILTER_SANITIZE_STRING);
        
        parent::__construct();
    }
}