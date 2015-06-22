<?php
include("xmlreader.php");

$socket = stream_socket_server("udp://comm.u-aizu.ac.jp:2345", $errno, $errstr, STREAM_SERVER_BIND);
if (!$socket) {
    die("$errstr ($errno)");
}

do {
    $pkt = stream_socket_recvfrom($socket, 10000, 0, $peer);
    echo "$peer"."*** sender ***\n";
    //echo "$pkt\n\n\n";
    $ackContent = "";
    $ackContent = $ackContent."<?xml version = \"1.0\" encoding=\"UTF-8\"?>\n";
    $ackContent = $ackContent."<alert xmlns = \"urn:oasis:names:tc:emergency:cap:1.1\">\n";
    $ackContent = $ackContent."<identifier>#01</identifier>\n";
    $ackContent = $ackContent."<sender>SYSTEM</sender>\n";
    $ackContent = $ackContent."<sent>2011-11-24T15:00:00-09:00</sent>\n";
    $ackContent = $ackContent."<status>Actual</status>\n";
    $ackContent = $ackContent."<msgType>ACK</msgType>\n";
    //$ackContent = $ackContent."<duplicate>2</duplicate>\n";
    $ackContent = $ackContent."<note>THIS IS AN ACK MESSAGE</note>\n";
    $ackContent = $ackContent."<alert>\n";
    stream_socket_sendto($socket, $ackContent, 0, $peer);
    
    handleXML($pkt);
  //print_r($values);
} while ($pkt !== false);

?>
