<?php

declare(strict_types=1);

final class Registration
{
    public function validate(array $data): array
    {
        $requiredFields = [
            'fullName',
            'email',
            'phone',
            'district',
            'city',
            'password',
            'confirmPassword',
        ];

        foreach ($requiredFields as $field) {
            if (trim((string)($data[$field] ?? '')) === '') {
                return [
                    'message' => 'Please fill in all required fields.',
                    'type' => 'error',
                ];
            }
        }

        $email = trim((string)$data['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'message' => 'Please enter a valid email address.',
                'type' => 'error',
            ];
        }

        $stmt = db()->prepare(
            'SELECT COUNT(*) FROM users WHERE email = ?'
        );
        $stmt->execute([$email]);

        if ((int)$stmt->fetchColumn() > 0) {
            return [
                'message' => 'An account already exists for this email.',
                'type' => 'error',
            ];
        }

        if ($data['password'] !== $data['confirmPassword']) {
            return [
                'message' => 'Passwords do not match.',
                'type' => 'error',
            ];
        }

        if (strlen((string)$data['password']) < 6) {
            return [
                'message' => 'Password must contain at least 6 characters.',
                'type' => 'error',
            ];
        }

        if (empty($data['terms'])) {
            return [
                'message' => 'Please accept the Terms & Conditions and Privacy Policy.',
                'type' => 'error',
            ];
        }

        return [
            'message' => 'Account created successfully.',
            'type' => 'success',
        ];
    }

    public function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO users
                (name, email, phone, district, city, password_hash)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            trim((string)$data['fullName']),
            trim((string)$data['email']),
            trim((string)$data['phone']),
            trim((string)$data['district']),
            trim((string)$data['city']),
            password_hash((string)$data['password'], PASSWORD_DEFAULT),
        ]);

        return (int)db()->lastInsertId();
    }
}
