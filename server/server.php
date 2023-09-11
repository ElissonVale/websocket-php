<?php

require_once('./websocket.php');

use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

$server = new WebSocket();

$server->add_event("message", function (Server $server, Frame $message) {
    foreach($server->connections as $connection) 
    {
        if($connection === $message->fd) continue;
        
        $server->push($connection, json_encode([ 'sender' => $connection, 'type' => 'chat', 'text' => $message->data ]));
    }
});

$server->start();