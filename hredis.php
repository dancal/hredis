<?php
require_once __DIR__. '/lib/autoload.php';

use Predis\Distribution\IDistributionStrategy;
use Predis\Network\PredisCluster;

class NaiveDistributionStrategy implements IDistributionStrategy {
    private $nodes;
    private $nodesCount;

    public function __construct() {
        $this->nodes = array();
        $this->nodesCount = 0;
    }

    public function add($node, $weight = null) {
        $this->nodes[] = $node;
        $this->nodesCount++;
    }

    public function remove($node) {
        $this->nodes = array_filter($this->nodes, function($n) use($node) {
            return $n !== $node;
        });

        $this->nodesCount = count($this->nodes);
    }

    public function get($key) {
        $count = $this->nodesCount;
        if ($count === 0) {
            throw new RuntimeException('No connections');
        }

        return $this->nodes[$count > 1 ? abs($key % $count) : 0];
    }

    public function generateKey($value) {
        return crc32($value);
    }
}

class hRedis extends Predis\Client {

	public $rServers = array(
        array('host'=>'10.128.5.28','port'=>8000,'database'=>0,'alias'=>0),
        array('host'=>'10.128.5.28','port'=>8001,'database'=>0,'alias'=>1),
        array('host'=>'10.128.5.28','port'=>8002,'database'=>0,'alias'=>2),
        array('host'=>'10.128.5.28','port'=>8003,'database'=>0,'alias'=>3),
        array('host'=>'10.128.5.28','port'=>8004,'database'=>0,'alias'=>4),
        array('host'=>'10.128.5.28','port'=>8005,'database'=>0,'alias'=>5),
        array('host'=>'10.128.5.28','port'=>8006,'database'=>0,'alias'=>6),

        array('host'=>'10.128.5.28','port'=>8007,'database'=>0,'alias'=>7),
        array('host'=>'10.128.5.29','port'=>8008,'database'=>0,'alias'=>8),
        array('host'=>'10.128.5.29','port'=>8009,'database'=>0,'alias'=>9),
        array('host'=>'10.128.5.29','port'=>8010,'database'=>0,'alias'=>10),
        array('host'=>'10.128.5.29','port'=>8011,'database'=>0,'alias'=>11),
        array('host'=>'10.128.5.29','port'=>8012,'database'=>0,'alias'=>12),
        array('host'=>'10.128.5.29','port'=>8013,'database'=>0,'alias'=>13),
        array('host'=>'10.128.5.29','port'=>8014,'database'=>0,'alias'=>14),
        array('host'=>'10.128.5.29','port'=>8015,'database'=>0,'alias'=>15),

        array('host'=>'10.128.5.30','port'=>8016,'database'=>0,'alias'=>16),
        array('host'=>'10.128.5.30','port'=>8017,'database'=>0,'alias'=>17),
        array('host'=>'10.128.5.30','port'=>8018,'database'=>0,'alias'=>18),
        array('host'=>'10.128.5.30','port'=>8019,'database'=>0,'alias'=>19),
        array('host'=>'10.128.5.30','port'=>8020,'database'=>0,'alias'=>20),
        array('host'=>'10.128.5.30','port'=>8021,'database'=>0,'alias'=>21),
        array('host'=>'10.128.5.30','port'=>8022,'database'=>0,'alias'=>22),
        array('host'=>'10.128.5.30','port'=>8023,'database'=>0,'alias'=>23),

        array('host'=>'10.128.5.31','port'=>8024,'database'=>0,'alias'=>24),
        array('host'=>'10.128.5.31','port'=>8025,'database'=>0,'alias'=>25),
        array('host'=>'10.128.5.31','port'=>8026,'database'=>0,'alias'=>26),
        array('host'=>'10.128.5.31','port'=>8027,'database'=>0,'alias'=>27),
        array('host'=>'10.128.5.31','port'=>8028,'database'=>0,'alias'=>28),
        array('host'=>'10.128.5.31','port'=>8029,'database'=>0,'alias'=>29),
        array('host'=>'10.128.5.31','port'=>8030,'database'=>0,'alias'=>30),
        array('host'=>'10.128.5.31','port'=>8031,'database'=>0,'alias'=>31),
	);
	public $options = null;

	public function __construct($rServer = null, $options = null) {

		if ( empty($options) ) {
			$this->options = array(
    			'cluster' => function() {
	    	    	$distributor = new NaiveDistributionStrategy();
	    		    return new PredisCluster($distributor);
	    		},
			);
		} else {
			$this->options = $options;
		}

		if ( !empty($rServer) ) {
			$this->rServers	= $rServer;
		}

		parent::__construct($this->rServers,$options);
	
	}
	
	public function showStatus() {

		echo "<pre>\n";
		foreach ($this->rServers as $k => $v ) {

			$alias 		= $v['alias'];
			$database	= $v['database'];
			$host		= $v['host'];
			$port		= $v['port'];

			$server 	= $this->getClientFor($alias)->info();
			echo 'host : ' . $host . ', port : ' . $port . ', keys distribution : ' . $server['db'.$database]['keys'] . "\n";

		}
		echo "</pre>\n";
		
	}

}

?>
