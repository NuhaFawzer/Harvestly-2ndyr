<?php

declare(strict_types=1);

final class Profile
{
    public function getBuyer(): array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([currentBuyerId()]);
        $buyer = $stmt->fetch();

        return $buyer ?: [];
    }

    public function save(array $data, ?array $file = null): array
    {
        $buyer = $this->getBuyer();

        $fields = ['name', 'email', 'phone', 'city', 'district', 'address'];
        $values = [];

        foreach ($fields as $field) {
            $values[$field] = trim(
                (string)($data[$field] ?? $buyer[$field] ?? '')
            );
        }

        if ($values['name'] === '') {
            throw new RuntimeException('Full name is required.');
        }

        if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Please enter a valid email address.');
        }

        $image = $buyer['profile_image'] ?? null;

        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $allowed = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];

            $mime = (string)($file['type'] ?? '');

            if (!isset($allowed[$mime])) {
                throw new RuntimeException('Please upload a JPG, PNG or WEBP image.');
            }

            if ((int)($file['size'] ?? 0) > 5 * 1024 * 1024) {
                throw new RuntimeException('Profile image must be less than 5MB.');
            }

            $directory = __DIR__ . '/../../uploads';

            if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
                throw new RuntimeException('Unable to create the profile image directory.');
            }

            $filename = 'buyer_' . currentBuyerId() . '_' . bin2hex(random_bytes(5)) . '.' . $allowed[$mime];
            $target = $directory . '/' . $filename;

            if (!move_uploaded_file($file['tmp_name'], $target)) {
                throw new RuntimeException('Unable to save the profile image.');
            }

            $image = url('uploads/' . $filename);
        }

        $stmt = db()->prepare(
            'UPDATE users
             SET name = ?, email = ?, phone = ?, city = ?, district = ?, address = ?, profile_image = ?
             WHERE id = ?'
        );

        $stmt->execute([
            $values['name'],
            $values['email'],
            $values['phone'],
            $values['city'],
            $values['district'],
            $values['address'],
            $image,
            currentBuyerId(),
        ]);

        $_SESSION['buyer_name'] = $values['name'];
        $_SESSION['buyer_email'] = $values['email'];

        return $this->getBuyer();
    }

    public function getStats(): array
    {
        $stmt = db()->prepare(
            'SELECT status, COUNT(*) AS c
             FROM orders
             WHERE user_id = ?
             GROUP BY status'
        );
        $stmt->execute([currentBuyerId()]);

        $stats = [
            'total' => 0,
            'delivered' => 0,
            'pending' => 0,
            'cancelled' => 0,
        ];

        foreach ($stmt->fetchAll() as $row) {
            $count = (int)$row['c'];
            $stats['total'] += $count;

            if ($row['status'] === 'Delivered' || $row['status'] === 'Completed') {
                $stats['delivered'] += $count;
            } elseif ($row['status'] === 'Cancelled') {
                $stats['cancelled'] += $count;
            } else {
                $stats['pending'] += $count;
            }
        }

        return $stats;
    }
}
