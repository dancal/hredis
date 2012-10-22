<?

# Hredis #

Hredis is a Predis(http://github.com/nrk/predis/) Extends Module.

## How to use Hredis ##

``` php
require_once 'hredis/hredis.php';

/*
	example
	$sServer    ='10.128.5.28:8000-8007;10.128.5.29:8008-8015;10.128.5.30:8016-8023;10.128.5.31:8024-8031';
*/
$sServer	= "{SERVERIP}:{START_PORT}-{END-PORT};{SERVERIP}:{START_PORT}-{END-PORT}";

/*
	insert test
*/
$client = new hRedis($sServer);
for ($i = 0; $i < 1000; $i++) {
    $client->set("key:$i", $i * 100000);
}
$client->showStatus();

/*
	get test
*/
$client = new hRedis($sServer);
for ($i = 0; $i < 1000; $i++) {
    echo $client->get("key:$i") . "\n";
}
$client->showStatus();

/*
	delete test
*/
$client = new hRedis($sServer);
for ($i = 0; $i < 1000; $i++) {
    $client->del("key:$i");
}
$client->showStatus();
```

?>
