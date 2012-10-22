$redis = new Predis\Client(array(
    array('host' => '10.0.0.1', 'port' => 6379),
    array('host' => '10.0.0.2', 'port' => 6379)
));

$replies = $redis->pipeline(function($pipe) {
    for ($i = 0; $i < 1000; $i++) {
        $pipe->set("key:$i", str_pad($i, 4, '0', 0));
        $pipe->get("key:$i");
    }
});
