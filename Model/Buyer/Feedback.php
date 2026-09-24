<?php

declare(strict_types=1);

final class Feedback
{
    public function submitReview(array $data): array
    {
        $farmerRating = (int)($data['farmer_rating'] ?? 0);
        $deliveryRating = (int)($data['delivery_rating'] ?? 0);
        $orderNumber = trim((string)($data['order_id'] ?? ''));

        if ($farmerRating < 1 || $farmerRating > 5 || $deliveryRating < 1 || $deliveryRating > 5) {
            return ['success' => false, 'message' => 'Please give a rating from 1 to 5 for both areas.'];
        }
        if ($orderNumber === '') {
            return ['success' => false, 'message' => 'Please select a completed order to review.'];
        }

        $order = $this->getReviewableOrder($orderNumber);
        if (!$order) {
            return ['success' => false, 'message' => 'Only completed orders within 14 days of delivery can be reviewed.'];
        }

        $dup = db()->prepare('SELECT id FROM feedback WHERE user_id = ? AND order_id = ? LIMIT 1');
        $dup->execute([currentBuyerId(), (int)$order['id']]);
        if ($dup->fetchColumn()) {
            return ['success' => false, 'message' => 'A review has already been submitted for this order.'];
        }

        $stmt = db()->prepare(
            "INSERT INTO feedback (user_id, order_id, farmer_rating, delivery_rating, quality_comment, delivery_comment, status)
             VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
        );
        $stmt->execute([
            currentBuyerId(), (int)$order['id'], $farmerRating, $deliveryRating,
            trim((string)($data['quality_comment'] ?? '')),
            trim((string)($data['delivery_comment'] ?? '')),
        ]);

        return ['success' => true, 'message' => 'Thank you! Your review has been submitted successfully.'];
    }

    public function submitComplaint(array $data): array
    {
        $category = trim((string)($data['category'] ?? ''));
        $details = trim((string)($data['details'] ?? ''));
        $orderNumber = trim((string)($data['order_id'] ?? ''));
        $allowed = ['Product Quality','Damaged Product','Wrong Product','Incorrect Quantity','Delivery Issue','Other'];

        if (!in_array($category, $allowed, true) || $details === '' || $orderNumber === '') {
            return ['success' => false, 'message' => 'Please select an order, complaint category, and enter details.'];
        }

        $stmt = db()->prepare('SELECT id, status, delivered_at FROM orders WHERE order_number = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$orderNumber, currentBuyerId()]);
        $order = $stmt->fetch();
        if (!$order) {
            return ['success' => false, 'message' => 'The selected order could not be found.'];
        }
        if (empty($order['delivered_at'])) {
            return ['success' => false, 'message' => 'A complaint can be filed after the order is delivered.'];
        }
        $hours = (time() - strtotime((string)$order['delivered_at'])) / 3600;
        if ($hours > 24) {
            return ['success' => false, 'message' => 'The 24-hour dispute window for this order has expired.'];
        }

        $evidencePath = $this->storeEvidence($_FILES['photos'] ?? []);
        $insert = db()->prepare('INSERT INTO complaints (user_id, order_id, category, details, evidence_path) VALUES (?, ?, ?, ?, ?)');
        $insert->execute([currentBuyerId(), (int)$order['id'], $category, $details, $evidencePath]);

        return ['success' => true, 'message' => 'Your complaint has been submitted successfully.'];
    }

    private function storeEvidence(array $files): ?string
    {
        if (empty($files['name']) || !is_array($files['name'])) return null;
        $allowed = ['jpg','jpeg','png','webp'];
        $paths = [];
        $dir = dirname(__DIR__, 2) . '/uploads/complaints';
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        foreach ($files['name'] as $i => $name) {
            if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
            $ext = strtolower(pathinfo((string)$name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) continue;
            $safe = 'complaint_' . currentBuyerId() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $target = $dir . '/' . $safe;
            if (move_uploaded_file($files['tmp_name'][$i], $target)) $paths[] = 'uploads/complaints/' . $safe;
            if (count($paths) >= 5) break;
        }
        return $paths ? implode(',', $paths) : null;
    }

    public function getReviewableOrders(): array
    {
        $stmt = db()->prepare(
            "SELECT o.order_number, o.delivered_at, o.total
             FROM orders o
             LEFT JOIN feedback f ON f.order_id = o.id AND f.user_id = o.user_id
             WHERE o.user_id = ? AND o.status = 'Completed'
               AND o.delivered_at IS NOT NULL
               AND o.delivered_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
               AND f.id IS NULL
             ORDER BY o.delivered_at DESC"
        );
        $stmt->execute([currentBuyerId()]);
        return $stmt->fetchAll();
    }

    public function getComplaintOrders(): array
    {
        $stmt = db()->prepare(
            "SELECT o.order_number, o.delivered_at, o.total
             FROM orders o
             WHERE o.user_id = ? AND o.delivered_at IS NOT NULL
               AND o.delivered_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
             ORDER BY o.delivered_at DESC"
        );
        $stmt->execute([currentBuyerId()]);
        return $stmt->fetchAll();
    }

    private function getReviewableOrder(string $orderNumber): ?array
    {
        $stmt = db()->prepare(
            "SELECT id, delivered_at FROM orders
             WHERE order_number = ? AND user_id = ? AND status = 'Completed'
               AND delivered_at IS NOT NULL
               AND delivered_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) LIMIT 1"
        );
        $stmt->execute([$orderNumber, currentBuyerId()]);
        return $stmt->fetch() ?: null;
    }

    public function getReviews(): array
    {
        $stmt = db()->prepare(
            'SELECT f.*, o.order_number FROM feedback f LEFT JOIN orders o ON o.id = f.order_id WHERE f.user_id = ? ORDER BY f.created_at DESC, f.id DESC'
        );
        $stmt->execute([currentBuyerId()]);
        return $stmt->fetchAll();
    }

    public function updateReview(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            "UPDATE feedback SET farmer_rating = ?, delivery_rating = ?, quality_comment = ?, delivery_comment = ?, status = 'Pending' WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([
            (int)$data['farmer_rating'], (int)$data['delivery_rating'],
            trim((string)($data['quality_comment'] ?? '')), trim((string)($data['delivery_comment'] ?? '')),
            $id, currentBuyerId(),
        ]);
    }

    public function deleteReview(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM feedback WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, currentBuyerId()]);
    }

    public function getComplaints(): array
    {
        $stmt = db()->prepare(
            'SELECT c.*, o.order_number FROM complaints c LEFT JOIN orders o ON o.id = c.order_id WHERE c.user_id = ? ORDER BY c.created_at DESC, c.id DESC'
        );
        $stmt->execute([currentBuyerId()]);
        return $stmt->fetchAll();
    }

    public function updateComplaint(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            "UPDATE complaints SET category = ?, details = ? WHERE id = ? AND user_id = ? AND status = 'Open' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        );
        return $stmt->execute([trim((string)$data['category']), trim((string)$data['details']), $id, currentBuyerId()]);
    }

    public function deleteComplaint(int $id): bool
    {
        $stmt = db()->prepare(
            "DELETE FROM complaints WHERE id = ? AND user_id = ? AND status = 'Open' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        );
        return $stmt->execute([$id, currentBuyerId()]);
    }
}
