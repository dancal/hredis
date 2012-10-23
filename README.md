<?

# Hredis #

## How to use Hredis ##

``` php
require_once 'hredis/autoload.php';

/*
	example
	$sServer    ='10.128.5.28:8000-8007;10.128.5.29:8008-8015;10.128.5.30:8016-8023;10.128.5.31:8024-8031';

	Necessary Servers or Ports
	2^x			= 2 port;
	2^x			= 4 port;
	2^x			= 8 port;
	2^x			= 16 port;
	2^x			= 31 port;
	2^x			= 64 port;
	2^x			= 128 port;
	2^x			= 256 port;
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
