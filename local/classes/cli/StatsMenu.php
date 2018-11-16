<?php
namespace local\classes\cli;

use cli\classes as cli;
use cli\traits\utility as utility;

class StatsMenu extends cli\Readline
{
    use utility\Output;

    private $dir = __DIR__;

    private $stats;

    public function __construct(Flag $flag)
    {
        if ($flag->help === TRUE) {
            exit($this->help());
        }
        $this->prompt = "Selection: ";
    }

    protected function continue(): void
    {
        echo $this->text("Enter to Continue.", 5, 31, 47) . "\n";
        readline();
    }
    
    
    protected function help(): void
    {
        echo $this->text('Run program and enter a key described in the menu') . "\n";
    }
}