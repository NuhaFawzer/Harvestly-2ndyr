<?php
require_once __DIR__ . '/_bridge.php';
buyer_require_login();
$farmer = trim((string)($_GET['farmer'] ?? ''));
if ($farmer === '') redirect('Controller/Buyer/ProductController.php');
$contact = db_fetch_one(
	"SELECT u.email,u.phone,fp.farm_name,fp.pickup_address_line1,fp.pickup_address_line2,
			fp.pickup_city_town city,fp.pickup_postal_code,d.district_name district
	 FROM users u
	 LEFT JOIN farmer_profiles fp ON fp.farmer_id=u.user_id
	 LEFT JOIN districts d ON d.district_id=fp.district_id
	 WHERE u.full_name=? AND u.role='FARMER' LIMIT 1",
	's',
	[$farmer]
);
require __DIR__ . '/../../View/Buyer/farmer-contact.php';
