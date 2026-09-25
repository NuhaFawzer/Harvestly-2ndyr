<?php
$__buyerNotificationCount = (int)($notificationCount ?? ($unreadNotifications ?? 0));
$__buyerCartCount = (int)($cartCount ?? 0);
?>

<div class="buyer-global-icons" aria-label="Buyer quick actions">
    <a
        href="<?= e(BASE_URL) ?>/Controller/Buyer/NotificationsController.php"
        class="buyer-global-icon buyer-global-notification"
        title="Notifications"
        aria-label="Notifications"
    >
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M18 8C18 4.686 15.314 2 12 2C8.686 2 6 4.686 6 8C6 13 4 15 3 16H21C20 15 18 13 18 8Z" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 20C10.5 21 11.2 21.5 12 21.5C12.8 21.5 13.5 21 14 20" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
        </svg>
        <?php if ($__buyerNotificationCount > 0): ?>
            <span class="buyer-global-badge buyer-global-badge-notification">
                <?= $__buyerNotificationCount > 99 ? '99+' : $__buyerNotificationCount ?>
            </span>
        <?php endif; ?>
    </a>

    <a
        href="<?= e(BASE_URL) ?>/Controller/Buyer/CartController.php"
        class="buyer-global-icon buyer-global-cart"
        title="Shopping Cart"
        aria-label="Shopping Cart"
    >
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3 4H5L7.2 14.5C7.4 15.4 8.2 16 9.1 16H17.5C18.3 16 19 15.5 19.3 14.8L21 9H6" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="9.5" cy="20" r="1.5" stroke="currentColor" stroke-width="1.9"/>
            <circle cx="17" cy="20" r="1.5" stroke="currentColor" stroke-width="1.9"/>
        </svg>
        <?php if ($__buyerCartCount > 0): ?>
            <span class="buyer-global-badge buyer-global-badge-cart">
                <?= $__buyerCartCount > 99 ? '99+' : $__buyerCartCount ?>
            </span>
        <?php endif; ?>
    </a>
</div>
