<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout', false, false, false);

$data = implode(' ', array_slice($argv, 1));
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
$channel->basic_publish($msg, 'logs');
echo " [x] Sent ", $data , "\n";

$channel->close();
$connection->close();

