<?php

declare(strict_types=1);

final class Cart
{
    private mysqli $db; // legacy class; integrated controllers use MySQLi bridge
    private float $deliveryFee = 350.00;

    public function __construct()
    {
        $this->db = db();
    }

    private function getCartId(): int
    {
        $userId = currentBuyerId();

        $stmt = $this->db->prepare(
            'SELECT id FROM carts WHERE user_id = ? LIMIT 1'
        );
        $stmt->execute([$userId]);

        $cartId = (int)$stmt->fetchColumn();

        if ($cartId > 0) {
            return $cartId;
        }

        $createStmt = $this->db->prepare(
            'INSERT INTO carts (user_id) VALUES (?)'
        );
        $createStmt->execute([$userId]);

        return (int)$this->db->lastInsertId();
    }

    public function getItems(?string $farmer = null): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                p.id,
                p.name,
                p.farmer AS seller,
                ci.quantity,
                p.price,
                p.unit,
                NULL AS old_price,
                p.image
             FROM cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             INNER JOIN products p ON p.id = ci.product_id
             WHERE c.user_id = ?
             ORDER BY ci.id'
        );
        $stmt->execute([currentBuyerId()]);

        $items = $stmt->fetchAll();
        if ($farmer === null || trim($farmer) === '') {
            return $items;
        }

        $farmer = trim($farmer);
        return array_values(array_filter($items, static function (array $item) use ($farmer): bool {
            return trim((string)($item['seller'] ?? '')) === $farmer;
        }));

    }

    public function add(int $productId, int $quantity = 1): bool
    {
        $cartId = $this->getCartId();
        $quantity = max(1, $quantity);

        $stmt = $this->db->prepare(
            'INSERT INTO cart_items (cart_id, product_id, quantity)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)'
        );

        return $stmt->execute([$cartId, $productId, $quantity]);
    }

    public function updateQuantity(int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        $stmt = $this->db->prepare(
            'UPDATE cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             SET ci.quantity = ?
             WHERE c.user_id = ? AND ci.product_id = ?'
        );

        return $stmt->execute([
            $quantity,
            currentBuyerId(),
            $productId,
        ]);
    }

    public function remove(int $productId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE ci
             FROM cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             WHERE c.user_id = ? AND ci.product_id = ?'
        );

        return $stmt->execute([
            currentBuyerId(),
            $productId,
        ]);
    }

    public function clear(): bool
    {
        $stmt = $this->db->prepare(
            'DELETE ci
             FROM cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             WHERE c.user_id = ?'
        );

        return $stmt->execute([currentBuyerId()]);
    }

    public function clearFarmer(string $farmer): bool
    {
        $farmer = trim($farmer);
        if ($farmer === '') {
            return false;
        }

        $stmt = $this->db->prepare(
            'DELETE ci
             FROM cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             INNER JOIN products p ON p.id = ci.product_id
             WHERE c.user_id = ? AND p.farmer = ?'
        );

        return $stmt->execute([currentBuyerId(), $farmer]);
    }

    public function getDeliveryFee(): float
    {
        return $this->deliveryFee;
    }

    public function calculateSubtotal(array $items): float
    {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $subtotal += (int)$item['quantity'] * (float)$item['price'];
        }

        return $subtotal;
    }

    public function calculateQuantity(array $items): int
    {
        $quantity = 0;

        foreach ($items as $item) {
            $quantity += (int)$item['quantity'];
        }

        return $quantity;
    }

    public function calculateTotal(array $items): float
    {
        $subtotal = $this->calculateSubtotal($items);
        return $subtotal + ($subtotal > 0 ? $this->deliveryFee : 0);
    }
}
