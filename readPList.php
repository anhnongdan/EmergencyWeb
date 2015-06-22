<?php
function readxmlfile(){
	$doc = new DOMDocument();
	$doc->load("Plist.xml");

	$listelements = array(); 
	$ele = $doc->getElementsByTagName( "li" );
	foreach($ele as $element){
		array_push($listelements, $element->nodeValue);
	}
	return $listelements;
}
?> 
