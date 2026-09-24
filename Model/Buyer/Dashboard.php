<?php

declare(strict_types=1);

final class Dashboard
{
    public function getBuyerData(): array
    {
        $buyerId = currentBuyerId();

        $userStmt = db()->prepare(
            'SELECT name FROM users WHERE id = ? LIMIT 1'
        );
        $userStmt->execute([$buyerId]);
        $buyerName = (string)($userStmt->fetchColumn() ?: 'Buyer');

        $notificationStmt = db()->prepare(
            'SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0'
        );
        $notificationStmt->execute([$buyerId]);
        $notificationCount = (int)$notificationStmt->fetchColumn();

        $cartStmt = db()->prepare(
            'SELECT COALESCE(SUM(ci.quantity), 0)
             FROM cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             WHERE c.user_id = ?'
        );
        $cartStmt->execute([$buyerId]);
        $cartCount = (int)$cartStmt->fetchColumn();

        return [
            'buyerName' => $buyerName,
            'notificationCount' => $notificationCount,
            'cartCount' => $cartCount,
        ];
    }
}
