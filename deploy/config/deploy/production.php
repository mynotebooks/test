<?php

Environment::configure('production', true, [
	'MYSQL_DB_HOST' => 'localhost',
	'MYSQL_USERNAME' => 'webapp',
	'MYSQL_PASSWORD' => 'passw0rd',
	'MYSQL_DB_NAME' => 'blog',
	'MYSQL_PREFIX' => '',
	'debug' => 0,
], function() {
});
