<?
$nIndex	= 0;
$nPortStart = 8000;
for ( $i=28; $i<=31; $i++ ) {

    for ( $x=0; $x<8;$x++ ) {

        echo "\tarray('host'=>'10.128.5.".$i."','port'=>".$nPortStart.",'database'=>15,'alias'=>".$nIndex."), \n";

        $nPortStart++;
		$nIndex++;
    }

}
?>
