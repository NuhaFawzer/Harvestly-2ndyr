<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';

if (!function_exists('farmerRoute')) {
    function farmerRoute(string $page, string $query = ''): string {
        if ($page === 'logout') {
            return baseUrl('index.php?action=logout');
        }
        $path = baseUrl('index.php?page=farmer_' . ltrim($page, '_'));
        return $path . ($query !== '' ? '&' . ltrim($query, '?') : '');
    }
}

$role = strtolower((string)($_SESSION['role'] ?? ''));
$farmer_id = (isset($_SESSION['user_id']) && $role === 'farmer') ? (int)$_SESSION['user_id'] : 0;
if ($farmer_id <= 0) {
    header('Location: ' . baseUrl('index.php?page=login&error=' . urlencode('Please login as a Farmer to continue.')));
    exit();
}
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT u.user_id,u.full_name,u.email,u.phone,fp.farm_name,fp.pickup_address_line1,fp.pickup_city_town,fp.district_id FROM users u LEFT JOIN farmer_profiles fp ON fp.farmer_id=u.user_id WHERE u.user_id=? AND u.role='FARMER' LIMIT 1");
$stmt->bind_param('i',$farmer_id);$stmt->execute();$farmer_user=$stmt->get_result()->fetch_assoc() ?: ['full_name'=>($_SESSION['user_name']??'Farmer')];$stmt->close();
