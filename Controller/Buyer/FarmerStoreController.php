<?php

require_once __DIR__ . '/_bridge.php';

buyer_require_login();

/*
|--------------------------------------------------------------------------
| Buyer -> Existing Farmer Profile
|--------------------------------------------------------------------------
| Do NOT load View/Farmer/profile.php directly from this controller.
| Redirect through index.php so the original Farmer layout, logo,
| sidebar and CSS are loaded exactly as they are for the Farmer.
|--------------------------------------------------------------------------
*/

$farmer_id = (int)($_GET['farmer_id'] ?? 0);

/*
| Backward compatibility for older links that still send farmer name.
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
    exit('Farmer profile could not be found.');
}

/*
|--------------------------------------------------------------------------
| Confirm that this is a real Farmer.
|--------------------------------------------------------------------------
*/
$row = db_fetch_one(
    "SELECT u.user_id
     FROM users u
     INNER JOIN farmer_profiles fp
         ON fp.farmer_id = u.user_id
     WHERE u.user_id = ?
       AND u.role = 'FARMER'
     LIMIT 1",
    'i',
    [$farmer_id]
);

if (!$row) {
    http_response_code(404);
    exit('Farmer profile could not be found.');
}

/*
|--------------------------------------------------------------------------
| IMPORTANT:
| Go through the main index route. This keeps the exact original
| Farmer dashboard logo/sidebar/layout instead of loading the profile
| directly from Controller/Buyer/.
|--------------------------------------------------------------------------
*/
header(
    'Location: ' . url(
        'index.php?page=farmer_profile&farmer_id=' . $farmer_id
    )
);
exit;
