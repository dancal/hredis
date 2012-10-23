<?

require_once 'config.php';

######################################################
#get test
######################################################

$begin_time = microtime(true);  

$client = new hRedis($sServer);
for ($i = 0; $i < $nMaxLoop; $i++) {
    echo $client->get("$sKey:$i") . "\n";
}

$duration	= microtime(true) - $begin_time;

$hours = (int)($duration/60/60);
$minutes = (int)($duration/60)-$hours*60;
$seconds = (int)$duration-$hours*60*60-$minutes*60;

echo "operation seconds : $seconds \n";

$client->showStatus();

?>
