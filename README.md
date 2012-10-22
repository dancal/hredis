<?
#
require_once 'hredis/hredis.php';

######################################################
#insert test
######################################################
$client = new hRedis();
for ($i = 0; $i < 1000; $i++) {
    $client->set("key:$i", $i * 100000);
}
$client->showStatus();

######################################################
#get test
######################################################
$client = new hRedis();
for ($i = 0; $i < 1000; $i++) {
    echo $client->get("key:$i") . "\n";
}
$client->showStatus();

######################################################
#delete test
######################################################
$client = new hRedis();
for ($i = 0; $i < 1000; $i++) {
    $client->del("key:$i");
}
$client->showStatus();

?>
