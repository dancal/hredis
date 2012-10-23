<?

require_once 'config.php';

######################################################
#insert test
######################################################

$begin_time = microtime(true);  

$client = new hRedis($sServer);

for ($i = 0; $i < $nMaxLoop; $i++) {
    $client->set("$sKey:$i", $i * 100000);
}

$duration	= microtime(true) - $begin_time;

echo "operation seconds : $duration \n";

$client->showStatus();

?>
