<?php
require_once __DIR__. '/lib/autoload.php';

use Predis\Distribution\IDistributionStrategy;
use Predis\Network\PredisCluster;

$__redis_option__ = array(
	'cluster' => function() {
	 	$distributor = new NaiveDistributionStrategy();
	    return new PredisCluster($distributor);
	 },
);

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

	public function __construct($sServerList, $databases = 0) {

		$nIndex			= 0;
		$this->rServers	= array();
		$lsServer		= explode(";", trim($sServerList,";"));	
		foreach ( $lsServer as $k => $list ) {

			$temp		= explode(":", $list);
			$host		= $temp[0];
			$port		= $temp[1];

			$lsport		= explode("-", $port);;
			$sport		= $lsport[0];
			$eport		= $lsport[1];

			for ( $i = $sport; $i <= $eport; $i++ ) {

				$rSvrItem	= array('host'=>$host, 'port' => $i, 'database'=>$databases, 'alias'=> $nIndex);
				array_push($this->rServers, $rSvrItem);

				$nIndex++;

			}

		}

		global $__redis_option__;
		parent::__construct($this->rServers, $__redis_option__);
	
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
