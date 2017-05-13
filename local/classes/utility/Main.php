<?php

namespace \local\classes\utility;


class Main {
    public static function printArray($var, $header = null) {
	if ($header !== null) {
	    echo $header . "\n\n";
	}
	foreach ($var as $key => $data) {
	    if (is_array($data)) {
		echo $key . "\n";
		printArray($data);
		echo "____________________________________\n";
	    } else {
		echo str_pad($key, 30) . ":\t" . $data . "\n";
	    }
	}
    }
}