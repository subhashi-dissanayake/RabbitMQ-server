<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info' ;
$data = implode(' ', array_slice($argv, 2));

//if(empty($data)) $data = "Hello world!";
//if(empty($data)) $data = "second msg..............................";
if(empty($data)) $data = "third msg";
//if(empty($data)) $data = "fourth msg..............................";
//if(empty($data)) $data = "fifth msg";
//if(empty($data)) $data = "sixth msg................................";
//if(empty($data)) $data = "seventh msg";
//if(empty($data)) $data = "eighth msg................................";
//if(empty($data)) $data = "ninth msg";
//if(empty($data)) $data = "tenth msg..............................";

$msg = new AMQPMessage($data, array('delivery_mode' => 2 ));
$channel->basic_publish($msg, 'direct_logs', $severity);
echo " [x] Sent ", $severity, ":", $data , "\n";

$channel->close();
$connection->close();

