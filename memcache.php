<?php
require './common.php';

if(!class_exists('Memcached', false)) {
	die("Memcache PHP bindings not found.");
}

$mem = new Memcached();
$mem->addServer("localhost", 11211);

$all_keys = $mem->getAllKeys();

$key = i($QUERY, 'key', false);
$contents = false;

if($key) {
	$contents = $mem->get($key);
}

render();
