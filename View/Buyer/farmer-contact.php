<?php

$farmer = $farmer ?? '';
$contact = $contact ?? null;

function h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$storeUrl = buyerRoute('FarmerStoreController.php', 'farmer=' . rawurlencode($farmer));
$productsUrl = buyerRoute('ProductController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact <?php echo h($farmer); ?> - Harvestly</title>

    <link rel="stylesheet" href="<?php echo h(url('css/Buyer/farmer-contact.css')); ?>">
        <script src="<?php echo h(url('js/icon-fallback.js')); ?>" defer></script>
</head>

<body>
<main class="contact-page">
    <div class="contact-card">

        <a class="back-link" href="<?php echo h($storeUrl); ?>">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Store
        </a>

        <div class="contact-icon">
            <span class="material-symbols-outlined">chat</span>
        </div>

        <p class="eyebrow">FARMER CONTACT</p>

        <h1>Contact <?php echo h($farmer); ?></h1>

        <?php if ($contact): ?>

            <p class="intro">
                You can use the available contact details below to reach this farmer.
            </p>

            <div class="contact-details">

                <?php if (!empty($contact['phone'])): ?>
                    <a class="contact-item" href="tel:<?php echo h($contact['phone']); ?>">
                        <span class="material-symbols-outlined">call</span>
                        <span>
                            <small>Phone</small>
                            <strong><?php echo h($contact['phone']); ?></strong>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($contact['email'])): ?>
                    <a class="contact-item" href="mailto:<?php echo h($contact['email']); ?>">
                        <span class="material-symbols-outlined">mail</span>
                        <span>
                            <small>Email</small>
                            <strong><?php echo h($contact['email']); ?></strong>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($contact['farm_name']) || !empty($contact['district']) || !empty($contact['city']) || !empty($contact['pickup_address_line1'])): ?>
                    <div class="contact-item">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>
                            <small>Location</small>
                            <strong><?php echo h(trim(implode(', ', array_filter([$contact['farm_name'] ?? '', $contact['pickup_address_line1'] ?? '', $contact['pickup_address_line2'] ?? '', $contact['city'] ?? '', $contact['district'] ?? '', $contact['pickup_postal_code'] ?? ''])))); ?></strong>
                        </span>
                    </div>
                <?php endif; ?>

            </div>

            <?php if (empty($contact['phone']) && empty($contact['email'])): ?>
                <div class="notice">
                    <span class="material-symbols-outlined">info</span>
                    <p>Farmer contact details are not available yet. They will appear here when the Farmer module provides them.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>

            <p class="intro">
                This farmer is available in the current product data, but contact details have not been provided yet.
            </p>

            <div class="notice">
                <span class="material-symbols-outlined">info</span>
                <p>
                    The Contact button is connected to the farmer contact flow.
                    Once the Farmer module provides the farmer's phone or email,
                    the real contact options will appear here automatically.
                </p>
            </div>

        <?php endif; ?>

        <div class="actions">
            <a class="secondary-btn" href="<?php echo h($productsUrl); ?>">Browse Products</a>
            <a class="primary-btn" href="<?php echo h($storeUrl); ?>">View Farmer Store</a>
        </div>

    </div>
</main>
</body>
</html>
