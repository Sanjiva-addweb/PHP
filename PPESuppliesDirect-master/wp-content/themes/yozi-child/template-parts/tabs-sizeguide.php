<?php

/**
 * Size guide tab content
 */

global $product;
$sizeinfometa = get_post_meta( $product->get_id(),'sizeguide' );

if( empty( $sizeinfometa ) ) {
	echo "<table class='sizeguide'>";
	if($sizeinfo->other[0]->C != 'Size:') {
		echo "<tr>";
		foreach($sizeinfo->size as $i=>$info) {
			if($i>1 && $info !='') {
				echo "<th>".$info."</th>";
			}
		}
		echo "</tr>";
	}

	foreach( $sizeinfo->other as $otherinfo ) {
		echo "<tr>";
		foreach( $otherinfo as $k=>$info ) {
			if( $k != 'A' && $k !='B' && $info != '' ) {
				if( $otherinfo->C == 'Size:' ) {
					echo "<th>".$info."</th>";
				}else {
					echo "<td>".$info."</td>";
				}
			}
		}
		echo "</tr>";
	}
	echo "</table>";
}else {
	echo html_entity_decode( $sizeinfometa[0] );
}