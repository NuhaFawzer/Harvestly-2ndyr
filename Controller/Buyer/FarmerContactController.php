<?php

require_once __DIR__ . '/_bridge.php';

buyer_require_login();

/*
|--------------------------------------------------------------------------
| Buyer -> Farmer Contact
|--------------------------------------------------------------------------
| Resolve the Farmer ID and open the existing Farmer Profile through
| index.php. The profile page displays the Farmer Contact section when
| show_contact=1.
|--------------------------------------------------------------------------
*/

$farmer_id = (int)($_GET['farmer_id'] ?? 0);

/*
| Backward compatibility for older links that send farmer name.
*/
if ($farmer_id <= 0) {
    $farmer = trim((string)($_GET['farmer'] ?? ''));

    if ($farmer !== '') {
        $row = db_fetch_one(
            "SELECT fp.farmer_id
             FROM farmer_profiles fp
             INNER JOIN users u ON u.user_id = fp.farmer_id
             WHERE u.role = 'FARMER'
               AND (u.full_name = ? OR fp.farm_name = ?)
             LIMIT 1",
            'ss',
            [$farmer, $farmer]
        );

        $farmer_id = (int)($row['farmer_id'] ?? 0);
    }
}

if ($farmer_id <= 0) {
    http_response_code(404);
    exit('Farmer contact could not be found.');
}

$row = db_fetch_one(
    "SELECT u.user_id
     FROM users u
     INNER JOIN farmer_profiles fp ON fp.farmer_id = u.user_id
     WHERE u.user_id = ?
       AND u.role = 'FARMER'
     LIMIT 1",
    'i',
    [$farmer_id]
);

if (!$row) {
    http_response_code(404);
    exit('Farmer contact could not be found.');
}

/*
|--------------------------------------------------------------------------
| Use the original Farmer profile UI and show its Contact section.
|--------------------------------------------------------------------------
*/
header(
    'Location: ' . url(
        'index.php?page=farmer_profile&farmer_id=' . $farmer_id . '&show_contact=1'
    )
);
exit;
