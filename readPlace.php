<?php
function readPlaceFile(){
	$doc = new DOMDocument();
	$doc->load("place.xml");

	$areas = $doc->getElementsByTagName( "area" );
	
	echo"<script type=\"text/javascript\">\n";
	echo"<!-- hide from older browsers. \n";
	foreach($areas as $area){
		$name = $area->getElementsByTagName("name")->item(0)->nodeValue;
		$center_lat = $area->getElementsByTagName("center_lat")->item(0)->nodeValue;
		$center_long = $area->getElementsByTagName("center_long")->item(0)->nodeValue;
		$lat_min = $area->getElementsByTagName("lat_min")->item(0)->nodeValue;
		$lat_max = $area->getElementsByTagName("lat_max")->item(0)->nodeValue;
		$long_min = $area->getElementsByTagName("long_min")->item(0)->nodeValue;
		$long_max = $area->getElementsByTagName("long_max")->item(0)->nodeValue;
		echo"	var areadef = new AreaDefinition(\"$name\", \"$center_lat\", \"$center_long\", \"$lat_min\", \"$lat_max\", \"$long_min\", \"$long_max\");\n";
		echo"	window.AreasDef.push(areadef);\n";
		//echo"	alert(window.AreasDef.length);\n";
	}
	echo"// -->\n";				
	echo"</script>\n";
}
?> 

