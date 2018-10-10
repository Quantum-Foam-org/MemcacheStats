<?php
namespace local\classes\cli;

use cli\classes as cli;
use cli\traits\utility as utility;
use local\classes\memcache as memcache;

class MemcacheStatsMenu extends cli\Readline
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
        
        $this->stats = new memcache\Stats();
    }

    protected function help(): void
    {
        $this->text('Run program and enter a key described in the menu');
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

    protected function handleInput(string $text): void
    {
        $serverList = $this->stats->getServerList();
        if (empty($serverList) && $text != '4') {
            echo $this->text('You must add one or more servers, using option 4', 1) . "\n";
        } else {
            switch ($text) {
                case '1':
                    echo "\n\n" . str_repeat("_", 60) . "\n\n";
                    echo $this->printArray($this->stats->getVersion(), $this->text('Version', 1), 60);
                    echo str_repeat("_", 60) . "\n\n";
                    $this->continue();
                    echo str_repeat("_", 60) . "\n\n";
                    echo $this->printArray($serverList, $this->text('Server List', 1), 60);
                    echo str_repeat("_", 60) . "\n\n";
                    $this->continue();
                    echo str_repeat("_", 60) . "\n\n";
                    echo $this->printArray($this->stats->getStats(), $this->text('Stats', 1), 60);
                    echo str_repeat("_", 60) . "\n\n";
                    $this->continue();
                    break;
                case '2':
                   // uncomment this to test.
                   // $this->stats->addData();
                    
                    do {
                        echo $this->text("Keys to fetch: ", 1);
                    } while ($keys[] = readline());
                    
                    if (!empty($keys)) {
                        try {
                            echo $this->printArray($this->stats->getVariables($keys), $this->text('Variables', 1), 60);
                        } catch(\OutOfBoundsException | \UnexpectedValueException | \RuntimeException $oe) {
                            $this->text('Was not able get requested variables, invalid characters in variable names!', 5, 31, 47) . "\n";
                        }
                    }
                    $this->continue();
                    break;
                case '3':
                    if ($this->stats->flushCache()) {
                        echo $this->text('Cache Flushed', 1, 31, 47);
                    } else {
                        echo $this->text('Error Flushing the Cache', 1, 31, 47);
                    }
                    echo "\n";
                    $this->continue();
                    break;
                case '4':
                    echo $this->text(sprintf("Enter Server IP (default: %s): ", \common\Config::obj()->system['defaultIP']), 1);
                    $ip = readline();
                    
                    echo $this->text(sprintf("Enter Server Port (default: %s):", \common\Config::obj()->system['defaultPort']), 1);
                    $port = readline();
                    try {
                        if ($this->stats->addServer($ip, $port) === true) {
                            $this->text('Added new server successfuly', 5, 31, 47) . "\n";
                        } else {
                            $this->text('Was not able to add new server!', 5, 31, 47) . "\n";
                        }
                    } catch(\UnexpectedValue $e) {
                        $this->text($e->getMessage(), 5, 31, 47) . "\n";
                    }
                    break;
                case 'q':
                    exit(0);
                    break;
                default:
                    echo "\n\nCommand not found\n\n";
                    break;
            }
        }
    }

    private function continue(): void
    {
        echo $this->text("Enter to Continue.", 5, 31, 47) . "\n";
        readline();
    }
}
