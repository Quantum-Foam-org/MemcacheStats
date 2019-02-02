<?php
namespace local\classes\cli;

use cli\classes as cli;
use cli\traits\utility as utility;

abstract class StatsMenu extends cli\Readline
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
    
    

    public function menu(): void
    {
        $output = array(
            1 => array(
                'Press',
                '`1`',
                "for Statistics"
            ),
            2 => array(
                'Press',
                '`2`',
                "for Variables"
            ),
            3 => array(
                'Press',
                '`3`',
                "to Flush the Cache"
            ),
            4 => array(
                'Press',
                '`4`',
                "to add a Server"
            ),
            5 => array(
                'Press',
                '`q`',
                "to quit"
            )
        );
        foreach ($output as $i => $t) {
            echo $this->text($i . '.) ', 1) . $this->text($t[0] . ' ', 1) . $this->text($t[1] . ' ', 0, 31, 0) . $this->text($t[2]) . "\n";
        }
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