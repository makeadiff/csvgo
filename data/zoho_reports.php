<?php
require_once __DIR__ . '/../common.php';
$worker_app_folder = __DIR__ . '/../../worker';
require_once $worker_app_folder . '/vendor/autoload.php';
require_once $worker_app_folder . '/../commons/includes/classes/ZohoClient.php';
require_once $worker_app_folder . '/includes/zoho.php';

try {
    $oauth_secret = json_decode(file_get_contents(realpath($worker_app_folder . '/system/zoho_oauth_secret.json')));
    $zoho_client = new ZohoClient($oauth_secret->client_id, $oauth_secret->client_secret, 'https://makeadiff.in/apps/worker/zoho_oauth_callback.php',
        [
            'access_token_file' => realpath($worker_app_folder . '/system/access_token.dat'),
            'verbose' => 0,
        ]);
} catch(Exception $e) {
    die("Zoho Connection Error : " . $e);
}

$verbose = 1;
$from = 0;
$max_rows = 1000000;
$applicants = [];

$all_applicants = $sql->getById("SELECT U.id, U.name, C.name AS city, G.name AS user_group, U.user_type, V.name AS vertical, U.joined_on
                                    FROM User U
                                    INNER JOIN City C ON C.id = U.city_id
                                    LEFT JOIN UserGroup UG ON UG.user_id = U.id AND UG.year = 2021
                                    LEFT JOIN `Group` G ON G.id=UG.group_id 
                                    LEFT JOIN Vertical V ON V.id = G.vertical_id
                                    WHERE U.zoho_user_id IS NOT NULL AND U.zoho_user_id != '' AND U.joined_on >= '2021-01-01 00:00:00'");
if($verbose) print "Found " . count($all_applicants) . " users who joined this year.\n";

$fields_to_save = [
    'MAD_Applicant_Id',
    'ID',
    'No_of_Vertical_Evaluations',
    'Latest_RW_Invite',
    'Final_Evaluation',
    'Latest_RW_RSVP_response',
    'RW_Invite__Counter',
    'Group_Activity_score',
    'Telephonic_Conversation_Initiated',
    'HC_Round_Selected',
    'GA_Selected',
    'HC_Evaluation_Round_score',
    'Vertical_Round_score',
    'Attendance_for_RW',
    'Available_for_RW',
    'Telephonic_Conversation_completed',
    'Moved_to_next_round',
    'Applicant_Level',
    'Trivia_Counter',
];
$api_endpoint = 'https://creator.zoho.com/api/v2/jithincn1/mad-recruit/report/Visibility_Tracker1';
if($verbose) print "Fetching Zoho Data...\n";
while($from < $max_rows) {
    try {
        if($verbose) print "\tGetting $from\n";
        $response = $zoho_client->request('GET', $api_endpoint . '?from=' . $from, []);
        if($response) {
            foreach($response['data'] as $usr) {
                $user_id = $usr['MAD_Applicant_Id'];
                if(!is_numeric($user_id)) continue;
                // $applicants[$usr['MAD_Applicant_Id']] = array_pluck($usr, $fields_to_save);
                $user = array_pluck($usr, $fields_to_save);

                if(isset($all_applicants[$user_id])) {
                    $madapp_user = $all_applicants[$user_id];
                    unset($all_applicants[$user_id]); // Memory saving.
                } else {
                    $madapp_user = $sql->getAssoc("SELECT U.id,U.name, C.name AS city, G.name AS user_group, U.user_type, V.name AS vertical, U.joined_on
                                                FROM User U
                                                INNER JOIN City C ON C.id = U.city_id
                                                LEFT JOIN UserGroup UG ON UG.user_id = U.id AND UG.year = 2021
                                                LEFT JOIN `Group` G ON G.id=UG.group_id 
                                                LEFT JOIN Vertical V ON V.id = G.vertical_id
                                                WHERE U.id = $user_id");
                }

                if($madapp_user) {
                    $data[] = array_merge($user, $madapp_user);
                }
            }
        }
    } catch(GuzzleHttp\Exception\ClientException $e) {
        if($e->getResponse()->getStatusCode() == 404) {
            break; // Done. Ran out of rows.
        }
        // echo "Error: " . $e->getResponse()->getStatusCode() . " : " . $e->getResponse()->getReasonPhrase();
    } catch(Exception $e) {
        // echo "Error: " . $e->getMessage() . "\n";
    }
    $from += 200;
}
if($verbose) print "All Downloaded. Saving to file.";
@file_put_contents(__DIR__ . '/Zoho_Applicant_Data.csv', array2csv($data));

// Remove all keys in an array NOT given in the $fields array
function array_pluck($src, $fields) {
    foreach($src as $key => $value) {
        if(!in_array($key, $fields)) {
            unset($src[$key]);
        }
    }
    return $src;
}