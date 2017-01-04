<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);
list($queue_name, ,) = $channel->queue_declare("", false, false, false, false);

$serverities = array_slice($argv, 1);
print_r($serverities);
if (empty($serverities)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [info] [warning] [error]\n");
    exit(1);
}
foreach ($serverities as $serverity){
    $channel->queue_bind($queue_name, 'direct_logs', $serverity);
}

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

/*$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo "[x] Done", "\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

};*/

$callback = function($msg) {
   echo ' [x] ', $msg->delivery_info['routing_key'], ": ", $msg->body, "\n";

};

$channel->basic_qos(null, 1, null);
$channel->basic_consume($queue_name, '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

