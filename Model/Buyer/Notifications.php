<?php

declare(strict_types=1);

final class Notifications
{
    private function map(array $row): array
    {
        $rawType = trim((string)($row['type'] ?? 'System'));
        $typeKey = strtolower($rawType);

        $typeAliases = [
            'order' => 'Orders',
            'orders' => 'Orders',
            'delivery' => 'Delivery',
            'deliveries' => 'Delivery',
            'payment' => 'Payments',
            'payments' => 'Payments',
            'promotion' => 'Promotions',
            'promotions' => 'Promotions',
            'complaint' => 'Complaints',
            'complaints' => 'Complaints',
            'review' => 'Reviews',
            'reviews' => 'Reviews',
            'system' => 'System',
        ];

        $type = $typeAliases[$typeKey] ?? ucfirst(strtolower($rawType));
        if (!in_array($type, ['Orders', 'Delivery', 'Payments', 'Promotions', 'Complaints', 'Reviews', 'System'], true)) {
            $type = 'System';
        }

        $iconByType = [
            'Orders' => 'shopping_bag',
            'Delivery' => 'local_shipping',
            'Payments' => 'payments',
            'Promotions' => 'local_offer',
            'Complaints' => 'report_problem',
            'Reviews' => 'rate_review',
            'System' => 'info',
        ];

        $defaultAction = [
            'Orders' => 'View Orders',
            'Delivery' => 'Track Order',
            'Payments' => 'View Orders',
            'Promotions' => 'Shop Now',
            'Complaints' => 'View Complaints',
            'Reviews' => 'View Reviews',
            'System' => 'View Products',
        ];

        $defaultUrl = [
            'Orders' => url('Controller/Buyer/OrdersController.php'),
            'Delivery' => url('Controller/Buyer/OrdersController.php'),
            'Payments' => url('Controller/Buyer/OrdersController.php'),
            'Promotions' => url('Controller/Buyer/ProductController.php'),
            'Complaints' => url('Controller/Buyer/ComplaintsController.php'),
            'Reviews' => url('Controller/Buyer/ReviewsController.php'),
            'System' => url('Controller/Buyer/ProductController.php'),
        ];

        $row['id'] = (int)($row['id'] ?? 0);
        $row['type'] = $type;
        $row['unread'] = !(bool)($row['is_read'] ?? 0);
        $row['icon'] = (string)($row['icon'] ?? ($iconByType[$type] ?? 'notifications'));
        $row['priority'] = (string)($row['priority'] ?? '');
        $row['high'] = $type === 'Orders' && $row['unread'];
        $row['promotion'] = $type === 'Promotions';
        $row['action'] = trim((string)($row['action_label'] ?? '')) ?: ($defaultAction[$type] ?? 'View');
        $row['action_url'] = trim((string)($row['action_url'] ?? '')) ?: ($defaultUrl[$type] ?? url('Controller/Buyer/DashboardController.php'));

        // If a notification contains an order number, make the action open that order's tracking page.
        if (preg_match('/ORD-[A-Z0-9-]+/i', (string)($row['message'] ?? ''), $match)) {
            $orderId = $match[0];
            if ($type === 'Delivery') {
                $row['action'] = 'Track Order';
                $row['action_url'] = url('Controller/Buyer/OrderTrackingController.php?id=' . urlencode($orderId));
            } elseif ($type === 'Orders' || $type === 'Payments') {
                $row['action'] = 'View Order';
                $row['action_url'] = url('Controller/Buyer/OrderTrackingController.php?id=' . urlencode($orderId));
            }
        }

        $row['time'] = !empty($row['created_at'])
            ? date('M d, Y H:i', strtotime((string)$row['created_at']))
            : 'Just now';

        return $row;
    }

    public function getAll(): array
    {
        $stmt = db()->prepare(
            'SELECT * FROM notifications
             WHERE user_id = ?
             ORDER BY created_at DESC, id DESC'
        );
        $stmt->execute([currentBuyerId()]);

        return array_map(
            fn(array $row) => $this->map($row),
            $stmt->fetchAll()
        );
    }

    public function find(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT * FROM notifications WHERE id = ? AND user_id = ? LIMIT 1'
        );
        $stmt->execute([$id, currentBuyerId()]);
        $row = $stmt->fetch();

        return $row ? $this->map($row) : null;
    }

    public function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO notifications
                (user_id, type, title, message, action_label, action_url, is_read)
             VALUES (?, ?, ?, ?, ?, ?, 0)'
        );
        $stmt->execute([
            currentBuyerId(),
            trim((string)($data['type'] ?? 'System')),
            trim((string)($data['title'] ?? 'Notification')),
            trim((string)($data['message'] ?? '')),
            trim((string)($data['action_label'] ?? '')) ?: null,
            trim((string)($data['action_url'] ?? '')) ?: null,
        ]);

        return (int)db()->lastInsertId();
    }

    public function markRead(int $id): bool
    {
        $stmt = db()->prepare(
            'UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?'
        );
        return $stmt->execute([$id, currentBuyerId()]);
    }

    public function markAllRead(): bool
    {
        $stmt = db()->prepare(
            'UPDATE notifications SET is_read = 1 WHERE user_id = ?'
        );
        return $stmt->execute([currentBuyerId()]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE notifications
             SET type = ?, title = ?, message = ?, action_label = ?, action_url = ?, is_read = ?
             WHERE id = ? AND user_id = ?'
        );

        return $stmt->execute([
            trim((string)($data['type'] ?? 'System')),
            trim((string)($data['title'] ?? 'Notification')),
            trim((string)($data['message'] ?? '')),
            trim((string)($data['action_label'] ?? '')) ?: null,
            trim((string)($data['action_url'] ?? '')) ?: null,
            !empty($data['is_read']) ? 1 : 0,
            $id,
            currentBuyerId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = db()->prepare(
            'DELETE FROM notifications WHERE id = ? AND user_id = ?'
        );
        return $stmt->execute([$id, currentBuyerId()]);
    }
}
