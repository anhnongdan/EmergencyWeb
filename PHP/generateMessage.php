<?php
include	("xmlreader.php");
$xmlContent = "";
function generate($userMessageID,$userID,$category,$event,$urgency,$severity,$certainty,$instruction,
			$longitude,$latitude,$elevation,$IPAddress, $areaDesc)
{  
	$datetime = new DateTime();
	$status = "Alert";
	
    global $xmlContent;
    $xmlContent = "";
    $xmlContent = $xmlContent."<?xml version = \"1.0\" encoding=\"UTF-8\"?>\n";
    $xmlContent = $xmlContent."<alert xmlns = \"urn:oasis:names:tc:emergency:cap:1.1\">\n";
    $xmlContent = $xmlContent."<identifier>".$userMessageID."</identifier>\n";	
    $xmlContent = $xmlContent."<sender>".$userID." </sender>\n";
    $xmlContent = $xmlContent."<sent>".$datetime->format('Y-m-d H:i:s')."</sent>\n"; // sent date
    $xmlContent = $xmlContent."<msgStatus>".$status."</msgStatus>\n";
    $xmlContent = $xmlContent."<msgType>Actual</msgType>\n";
    $xmlContent = $xmlContent."<duplicate></duplicate>\n";
    $xmlContent = $xmlContent."<scope>Public</scope>\n";
    $xmlContent = $xmlContent."<restriction></restriction>\n";
    $xmlContent = $xmlContent."<address></address>\n";
    $xmlContent = $xmlContent."<reference></reference>\n";
    $xmlContent = $xmlContent."<note></note>\n";
    $xmlContent = $xmlContent."<info>\n";
    $xmlContent = $xmlContent."<language></language>\n";
    $xmlContent = $xmlContent."<category>".$category."</category>\n"; // ---------
    $xmlContent = $xmlContent."<event>".$event."</event>\n"; 		   // --------
    $xmlContent = $xmlContent."<urgency>".$urgency."</urgency>\n"; // -------
    $xmlContent = $xmlContent."<severity>".$severity."</severity>\n"; // --------
    $xmlContent = $xmlContent."<certainty>".$certainty."</certainty>\n"; // --------
    $xmlContent = $xmlContent."<instruction>".$instruction."</instruction>\n"; // --------
    $xmlContent = $xmlContent."<sendername>".""."</sendername>\n";
    $xmlContent = $xmlContent."<area>\n";
    $xmlContent = $xmlContent."<areaDesc>".$areaDesc."</areaDesc>\n";
    $xmlContent = $xmlContent."<longitude>".$longitude."</longitude>\n"; // --------
    $xmlContent = $xmlContent."<latitude>".$latitude."</latitude>\n";   // -------	
    $xmlContent = $xmlContent."<elevation>".$elevation."</elevation>\n";
    $xmlContent = $xmlContent."<IPAddress>".$IPAddress."</IPAddress>";      // -------
    $xmlContent = $xmlContent."</area>\n";
    $xmlContent = $xmlContent."</info>\n";
    $xmlContent = $xmlContent."</alert>\n";
}
// main()

    // edit some parameter here
    $userMessageID = 1;
    $userID = "AIZU0001";
    $category = "Rescue";
    $event = "leg broken";
    $urgency = "Expected";
    $severity = "Extreme";
    $certainty = "Observed";
    $instruction = "Go";
    $longitude = "140.04111000000001";
    $latitude = "37.52415000000000";
    $elevation = "0";
    $IPAddress = "";
    $areaDesc = "Democracy detected!!";
    // generate message
    generate($userMessageID,$userID,$category,$event,$urgency,$severity,$certainty,$instruction,
    			$longitude,$latitude, $elevation, $IPAddress, $areaDesc);
    handleXML($xmlContent);

?>
