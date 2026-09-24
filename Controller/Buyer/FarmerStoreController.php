<?php
require_once __DIR__ . '/_bridge.php';
buyer_require_login();
$farmer = trim((string)($_GET['farmer'] ?? ''));
if ($farmer === '') redirect('Controller/Buyer/ProductController.php');
$products = array_values(array_filter(buyer_products(), fn($p) => $p['farmer'] === $farmer));
if (!$products) { http_response_code(404); exit('Farmer store not found.'); }
$first = $products[0];
$store = [
	'name' => $farmer,
	'rating' => $first['farmer_rating'],
	'district' => $first['farm'] ?: 'Sri Lanka',
	'farm' => $first['farm'],
	'address' => $first['farmer_address'],
	'phone' => $first['farmer_phone'],
	'email' => $first['farmer_email'],
	'verification' => $first['farmer_verification'],
	'experience' => $first['experience'],
	'delivery' => 'District-based delivery available',
	'products' => $products,
];
require __DIR__ . '/../../View/Buyer/farmer-store.php';
