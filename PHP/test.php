<?php

$socket = stream_socket_server("udp://comm.u-aizu.ac.jp:2345", $errno, $errstr, STREAM_SERVER_BIND);
if (!$socket) {
    die("$errstr ($errno)");
}

do {
    $pkt = stream_socket_recvfrom($socket, 10000, 0, $peer);
    echo "$peer"."*** sender ***\n";
    $ackContent = "ACK\n";
    stream_socket_sendto($socket, $ackContent, 0, $peer);
    
  //print_r($values);
} while ($pkt !== false);

?>
