<?php
declare(strict_types=1);

require_once __DIR__ . '/_bridge.php';
buyer_require_login();

$cartItems = buyer_cart_normalized();
if (!$cartItems) {
    redirect('Controller/Buyer/CartController.php');
}

$s = buyer_cart_summary($cartItems);
$subtotal = $s['subtotal'];
$serviceFee = $s['serviceFee'];
$deliveryFee = $s['deliveryFee'];
$total = $s['total'];
$totalQuantity = $s['quantity'];
$success = false;
$successOrderId = null;
$error = '';
$farmer = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $data = [
            'buyer_id' => currentBuyerId(),
            'fullName' => trim((string)($_POST['fullName'] ?? '')),
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'city' => trim((string)($_POST['city'] ?? '')),
            'address' => trim((string)($_POST['address'] ?? '')),
            'postal' => trim((string)($_POST['postal'] ?? '')),
            'destination_district' => trim((string)($_POST['destination_district'] ?? '')),
            'payment' => trim((string)($_POST['payment'] ?? 'card')),
        ];

        foreach (['fullName','phone','city','address','postal','destination_district'] as $required) {
            if ($data[$required] === '') {
                throw new RuntimeException('Please complete all required delivery details.');
            }
        }

        $phoneDigits = preg_replace('/\D+/', '', $data['phone']);
        if (strlen($phoneDigits) < 9 || strlen($phoneDigits) > 12) {
            throw new RuntimeException('Please enter a valid phone number.');
        }

        if ($data['payment'] !== 'card') {
            throw new RuntimeException('Please select PayHere Sandbox as the payment method.');
        }

        $orders = buyer_checkout_place_orders($data, $cartItems);
        $firstOrder = $orders[0] ?? null;

        echo json_encode([
            'success' => true,
            'message' => 'Your order was placed successfully through the PayHere Sandbox demo flow.',
            'order' => [
                'id' => $firstOrder['id'] ?? '',
                'count' => count($orders),
            ],
            'orders' => $orders,
        ]);
        exit;
    } catch (Throwable $e) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage() ?: 'Unable to place the order.',
        ]);
        exit;
    }
}

require __DIR__ . '/../../View/Buyer/checkout.php';
