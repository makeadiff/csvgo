<?php
ini_set('max_execution_time', '300');

$cache = true;
if(i($QUERY, 'no_cache', false)) $cache = false;

// Purpose: Convert JSON to CSV
$response = load('https://creator.zoho.com/api/json/mad-recruit/view/Visibility_Tracker1?authtoken=cdcfd4eb1b77b0835f4339827906e42a&scope=creatorapi', ['cache' => $cache, 'cache_timeout' => 60 * 24]);

$json = str_replace(['var zohojithincn1view3117 = ', '}]};'], ['', '}]}'], $response);
$data_raw = json_decode($json, true);
$data = $data_raw['Registration'];
// dump($data); exit;