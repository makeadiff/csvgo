<?php

$response = load('https://creator.zoho.com/api/json/mad-recruit/view/All_Campaigns?authtoken=cdcfd4eb1b77b0835f4339827906e42a&scope=creatorapi', ['cache' => false]);

$json = str_replace(['var zohojithincn1view2725 = ', '}]};'], ['', '}]}'], $response);
$data_raw = json_decode($json, true);
$data = $data_raw['Create_Campaign'];
// dump($data); exit;