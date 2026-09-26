<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';

if (!function_exists('farmerRoute')) {
    function farmerRoute(string $page, string $query = ''): string {
        if ($page === 'logout') {
            return baseUrl('index.php?action=logout');
        }

        $path = baseUrl(
            'index.php?page=farmer_' . ltrim($page, '_')
        );

        return $path . (
            $query !== '' ? '&' . ltrim($query, '?') : ''
        );
    }
}

$role = strtolower((string)($_SESSION['role'] ?? ''));

$logged_in_user_id = isset($_SESSION['user_id'])
    ? (int)$_SESSION['user_id']
    : 0;

$farmer_id = (
    $logged_in_user_id > 0 &&
    $role === 'farmer'
) ? $logged_in_user_id : 0;

$is_buyer_farmer_view = false;
$view_farmer_id = 0;

if ($role === 'buyer' && isset($_GET['farmer_id'])) {
    $requested_farmer_id = (int)$_GET['farmer_id'];

    if ($requested_farmer_id > 0) {
        $view_farmer_id = $requested_farmer_id;
        $is_buyer_farmer_view = true;
    }
}

if ($farmer_id <= 0 && !$is_buyer_farmer_view) {
    header(
        'Location: ' .
        baseUrl(
            'index.php?page=login&error=' .
            urlencode('Please login to continue.')
        )
    );
    exit;
}

$conn = getDBConnection();

$profile_farmer_id = $is_buyer_farmer_view
    ? $view_farmer_id
    : $farmer_id;

$stmt = $conn->prepare(
    "SELECT
        u.user_id,
        u.full_name,
        u.email,
        u.phone,
        fp.farm_name,
        fp.pickup_address_line1,
        fp.pickup_city_town,
        fp.district_id
     FROM users u
     LEFT JOIN farmer_profiles fp
        ON fp.farmer_id = u.user_id
     WHERE u.user_id = ?
       AND u.role = 'FARMER'
     LIMIT 1"
);

$stmt->bind_param('i', $profile_farmer_id);
$stmt->execute();

$farmer_user = $stmt->get_result()->fetch_assoc()
    ?: ['full_name' => 'Farmer'];

$stmt->close();
