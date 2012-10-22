<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require 'SharedConfigurations.php';

// Developers can customize the distribution strategy used by the client
// to distribute keys among a cluster of servers simply by creating a class
// that implements the Predis\Distribution\IDistributionStrategy interface.

use Predis\Distribution\IDistributionStrategy;
use Predis\Network\PredisCluster;

class NaiveDistributionStrategy implements IDistributionStrategy
{
    private $nodes;
    private $nodesCount;

    public function __construct()
    {
        $this->nodes = array();
        $this->nodesCount = 0;
    }

    public function add($node, $weight = null)
    {
        $this->nodes[] = $node;
        $this->nodesCount++;
    }

    public function remove($node)
    {
        $this->nodes = array_filter($this->nodes, function($n) use($node) {
            return $n !== $node;
        });

        $this->nodesCount = count($this->nodes);
    }

    public function get($key)
    {
        $count = $this->nodesCount;
        if ($count === 0) {
            throw new RuntimeException('No connections');
        }

        return $this->nodes[$count > 1 ? abs($key % $count) : 0];
    }

    public function generateKey($value)
    {
        return crc32($value);
    }
}

$options = array(
    'cluster' => function() {
        $distributor = new NaiveDistributionStrategy();
        return new PredisCluster($distributor);
    },
);

$client = new Predis\Client($multiple_servers, $options);
for ($i = 0; $i < 1000; $i++) {
	$client->del("key:$i");
    //$client->set("key:$i", $i * 100000);
    //echo $client->get("key:$i") . "\n";
}

$server1 = $client->getClientFor('1')->info();
$server2 = $client->getClientFor('2')->info();
$server2 = $client->getClientFor('3')->info();
$server2 = $client->getClientFor('4')->info();
$server2 = $client->getClientFor('5')->info();

printf("Server '%s' has %d keys while server '%s' has %d keys.\n",
    'first', $server1['db15']['keys'], 'second', $server2['db15']['keys']
);
