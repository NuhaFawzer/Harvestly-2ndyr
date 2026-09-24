<?php

declare(strict_types=1);

final class OrderTracking
{
    public function getTrackingByOrder(array $order): array
    {
        $statuses = [
            'Order Placed',
            'Paid',
            'Accepted',
            'Preparing',
            'Ready for Delivery',
            'Pending Assignment',
            'Assigned',
            'Picked Up',
            'In Transit',
            'Out for Delivery',
            'Delivered',
            'Completed',
        ];

        $currentIndex = array_search(
            $order['status'],
            $statuses,
            true
        );

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $steps = [];

        foreach ($statuses as $index => $status) {
            $state = 'pending';

            if ($index < $currentIndex) {
                $state = 'completed';
            } elseif ($index === $currentIndex) {
                $state = 'active';
            }

            $steps[] = [
                'title' => $status,
                'state' => $state,
                'icon' => $index < 2 ? '✓' : ($index < 4 ? '🚚' : '✓'),
                'time' => $index <= $currentIndex
                    ? date('M d, Y', strtotime($order['created_at'] ?? 'now'))
                    : 'Awaiting update',
            ];
        }

        return [
            'id' => $order['id'],
            'status' => $order['status'],
            'delivery' => $order['delivery'] ?? date('F d, Y', strtotime('+2 days')),
            'total' => $order['total'],
            'items' => array_map(
                fn(array $item) => [
                    'name' => $item['name'],
                    'qty' => 'Qty: ' . $item['quantity'],
                    'image' => (mb_strtolower(trim((string)$item['name'])) === 'coconut' ? url('assets/coconut-sri-lanka.jpeg') : $item['image']),
                ],
                $order['items'] ?? []
            ),
            'steps' => $steps,
            'can_confirm' => $order['status'] === 'Delivered',
        ];
    }
}
