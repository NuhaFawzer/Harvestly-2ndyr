<?php

declare(strict_types=1);

final class Buyer
{
    public function validateLogin(string $email, string $password): array
    {
        if ($email === '' || $password === '') {
            return ['Please enter your email and password.'];
        }

        $buyer = $this->findByEmail($email);

        if (!$buyer || $buyer['role'] !== 'buyer' || empty($buyer['password_hash'])) {
            return ['Invalid email or password.'];
        }

        if (!password_verify($password, $buyer['password_hash'])) {
            return ['Invalid email or password.'];
        }

        return [];
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = db()->prepare(
            'SELECT * FROM users WHERE email = ? LIMIT 1'
        );
        $stmt->execute([$email]);
        $buyer = $stmt->fetch();

        return $buyer ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT * FROM users WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $buyer = $stmt->fetch();

        return $buyer ?: null;
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

    public function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE users
             SET name = ?, email = ?, phone = ?, district = ?, city = ?, address = ?
             WHERE id = ?'
        );

        return $stmt->execute([
            trim((string)$data['name']),
            trim((string)$data['email']),
            trim((string)$data['phone']),
            trim((string)$data['district']),
            trim((string)$data['city']),
            trim((string)$data['address']),
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        if ($id <= 0 || $id !== currentBuyerId()) {
            return false;
        }

        $stmt = db()->prepare(
            "DELETE FROM users WHERE id = ? AND role = 'buyer'"
        );

        return $stmt->execute([$id]) && $stmt->rowCount() > 0;
    }
}
