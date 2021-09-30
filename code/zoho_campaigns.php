<?php
// First we create a Temperory table that can be JOINED with the User table. Otherwise this operation will be VERY expensive.
// $sql->execQuery("CREATE TEMPORARY TABLE Temp_UserCampaign SELECT id AS user_id, campaign AS campaign_id FROM User LIMIT 0");

$csv_file = joinPath(__DIR__, 'data/User_Campaign.csv');

$row_count = 0;
$user_campaign_mapping = [];
$user_data_campaign = [];
if (($handle = fopen($csv_file, "r")) !== FALSE) {
  while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $row_count++;
    if($row_count == 1) continue; // Skip header.
    // $user_campaign_mapping[$row[1]] = $row[3];
    // $sql->execQuery("INSERT INTO Temp_UserCampaign (user_id, campaign_id) VALUES ($row[1], '$row[3]')");
    $user_data_campaign[] = [
      // 'item'    => 'User',
      'item_id' => $row[1],
      // 'name'    => 'sourcing_campaign_id',
      'data'    => $row[3],
      // 'year'    => $year,
      // 'added_on'=> date('Y-m-d H:i:s')
    ];
    
  }
  fclose($handle);
}

$insert_qry = "INSERT INTO DATA (item,item_id,name,data,year,added_on) VALUES ";
$chunks = array_chunk($user_data_campaign, 50);
foreach ($chunks as $chunk) {
  $values = [];
  foreach($chunk as $chunk_data) {
    $values[] = "('User', " . $chunk_data['item_id'] . ",'sourcing_campaign_id', '" . $chunk_data['data'] . "', 2021, NOW())";
  }
  echo $insert_qry . implode(",", $values) . ";\n";
}

exit;


// $data = $sql->getAll("SELECT U.id, U.name, C.name AS city, TUC.campaign_id FROM User U
//                         INNER JOIN City C ON C.id = U.city_id
//                         LEFT JOIN Temp_UserCampaign TUC ON TUC.user_id = U.id
//                         WHERE U.user_type='volunteer' AND U.status='1'");


// This is the expensive option. Do NOT run this on production.
// $data = [];
// $all_cities = $sql->getById("SELECT id,name FROM City");

// $index = 0;
// foreach($user_campaign_mapping as $user_id => $campaign_id) {
//   $data[$index] = $sql->getAssoc("SELECT id,name,city_id, (SELECT COUNT(UC.id) FROM User UC WHERE UC.campaign='$campaign_id') AS sourced_count FROM User WHERE id=$user_id");
//   $data[$index]['city'] = $all_cities[$data[$index]['city_id']];
//   $index++;
// }

/*
I'm thinking I'll put the entire data set into the Data table.
item: User
item_id: 1
name: campaign_id
data: CAMP20214185
year: 2021


*/