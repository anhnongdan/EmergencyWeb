<?php
	$listcon = $_GET[listcontent];
	$ele = explode("-",$listcon);
	$myfile = "Plist.xml";
	unlink($myfile);
	$doc = new DOMDocument();
	$doc->formatOutput = true;
  
	$r = $doc->createElement( "listelements" );
	$doc->appendChild( $r );
	foreach($ele as $element){
		if($element=="") continue;
		$b = $doc->createElement( "li" ); 
		$b->appendChild($doc->createTextNode($element));
		$r->appendChild($b);
	}
	echo $doc->saveXML();
	$doc->save("Plist.xml");
?>
