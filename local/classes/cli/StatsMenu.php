<?php
namespace local\classes\cli;

use cli\classes as cli;
use cli\traits\utility as utility;

class MongoDbStatsMenu extends cli\Readline
{
    use utility\Output;

    private $dir = __DIR__;

    private $stats;

    public function __construct($argv, $argc)
    {
        if ($argc >= 1) {
            $f = new Flag();
            try {
                $f->exchangeArray(array_slice($argv, 1));
            } catch (\UnexpectedValueException $e) {
                exit(\common\logging\Logger::obj()->writeException($e));
            }
            if ($f->help === TRUE) {
                exit($this->help());
            }
            if (strlen($f->dir) > 0) {
                $this->dir = $f->dir;
            }
        }
        
        $this->prompt = "Selection: ";
    }

    protected function continue(): void
    {
        echo $this->text("Enter to Continue.", 5, 31, 47) . "\n";
        readline();
    }