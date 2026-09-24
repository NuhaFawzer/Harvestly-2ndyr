<?php

declare(strict_types=1);

require_once __DIR__ . '/Cart.php';
require_once __DIR__ . '/Orders.php';

final class Checkout
{
    private const SERVICE_FEE = 50.00;

    private Cart $cart;
    private Orders $orders;

    public function __construct()
    {
        $this->cart = new Cart();
        $this->orders = new Orders();
    }

    public function getCartItems(?string $farmer = null): array
    {
        return $this->cart->getItems($farmer);
    }

    public function getSummary(array $items): array
    {
        $subtotal = $this->cart->calculateSubtotal($items);
        $quantity = $this->cart->calculateQuantity($items);
        $deliveryFee = $quantity > 0 ? $this->cart->getDeliveryFee() : 0.0;

        return [
            'subtotal' => $subtotal,
            'quantity' => $quantity,
            'serviceFee' => self::SERVICE_FEE,
            'deliveryFee' => $deliveryFee,
            'total' => $subtotal + self::SERVICE_FEE + $deliveryFee,
        ];
    }

    public function validate(array $data): array
    {
        $requiredFields = [
            'fullName',
            'phone',
            'city',
            'address',
            'postal',
            'destination_district',
            'payment',
        ];

        foreach ($requiredFields as $field) {
            if (trim((string)($data[$field] ?? '')) === '') {
                return [
                    'valid' => false,
                    'message' => 'Please complete all required fields.',
                ];
            }
        }

        $phone = preg_replace('/\D+/', '', (string)$data['phone']);

        if (strlen($phone) < 9 || strlen($phone) > 12) {
            return [
                'valid' => false,
                'message' => 'Please enter a valid phone number.',
            ];
        }

        $payment = (string)$data['payment'];

        if (!in_array($payment, ['card', 'cash', 'bank'], true)) {
            return [
                'valid' => false,
                'message' => 'Invalid payment method.',
            ];
        }

        $districts = [
            'Ampara','Anuradhapura','Badulla','Batticaloa','Colombo','Galle','Gampaha','Hambantota',
            'Jaffna','Kalutara','Kandy','Kegalle','Kilinochchi','Kurunegala','Mannar','Matale',
            'Matara','Monaragala','Mullaitivu','Nuwara Eliya','Polonnaruwa','Puttalam','Ratnapura',
            'Trincomalee','Vavuniya'
        ];

        if (!in_array(trim((string)($data['destination_district'] ?? '')), $districts, true)) {
            return [
                'valid' => false,
                'message' => 'Please select a valid destination district.',
            ];
        }

        return [
            'valid' => true,
            'message' => '',
        ];
    }

    public function placeOrder(array $data, array $items): array
    {
        $order = $this->orders->createOrder(
            $items,
            $this->getSummary($items),
            $data
        );

        $farmer = trim((string)($data['farmer'] ?? ''));
        if ($farmer !== '') {
            $this->cart->clearFarmer($farmer);
        } else {
            $this->cart->clear();
        }

        return $order;
    }
}
