<?php

declare(strict_types=1);

final class ForgotPassword
{
    public function validate(string $email): array
    {
        if ($email === '') {
            return [
                'success' => false,
                'message' => 'Please enter your email address.',
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Please enter a valid email address.',
            ];
        }

        // A real mail service can be connected here later. We intentionally
        // return the same message for existing and non-existing accounts.
        return [
            'success' => true,
            'message' => 'If an account exists for this email, a reset link has been sent.',
        ];
    }
}
