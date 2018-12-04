This project requires https://github.com/malaimo2900/php_cli and
https://github.com/malaimo2900/php_common to work.

It now supports Memcache and MongoDB.

You can access mongodb by using the --db=mongodb flag.

Run these commands:

1.) pecl install memcached ( make sure that memcaced server is installed)

2.) pecl install mongodb ( make sure that mongodb server is installed)

3.) mkdir memcachePHP

4.) cd memcachePHP

5.) git clone https://github.com/malaimo2900/MemcacheStats.git

6.) git clone https://github.com/malaimo2900/php_cli.git

7.) git clone https://github.com/malaimo2900/php_common.git

8.) cd MemcacheStats

9.) php Stats.php --db=memcached

Make sure you are running linux and have access to a memcache or mongodb
server.  Add the server by using option 4.

http://php.net/manual/en/book.memcached.php

http://php.net/manual/en/set.mongodb.php
