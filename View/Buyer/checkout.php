<?php

$cartItems = $cartItems ?? [];

$subtotal = (float)($subtotal ?? 0);

$deliveryFee = (float)($deliveryFee ?? 0);

$total = (float)($total ?? 0);

$totalQuantity = (int)($totalQuantity ?? 0);

$success = $success ?? false;

$successOrderId = $successOrderId ?? null;

$error = $error ?? "";
$farmer = $farmer ?? "";
$checkoutFarmers = [];
foreach ($cartItems as $checkoutItem) {
    $seller = trim((string)($checkoutItem['seller'] ?? ''));
    if ($seller !== '' && !in_array($seller, $checkoutFarmers, true)) {
        $checkoutFarmers[] = $seller;
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Harvestly - Checkout</title>

<link
    rel="stylesheet"
    href="/Harvestly/css/Buyer/checkout.css"
>

    <script src="/Harvestly/js/icon-fallback.js" defer></script>
</head>


<body>


<div class="checkout-page">


<!-- =====================================================
     HEADER
====================================================== -->

<header class="checkout-header">

    <a
        href="/Harvestly/Controller/Buyer/CartController.php"
        class="back-link"
    >

        <span class="material-symbols-outlined">
            arrow_back
        </span>

        Back to Cart

    </a>


    <a
        href="/Harvestly/Controller/Buyer/DashboardController.php"
        class="brand"
    >

        <img src="/Harvestly/assets/harvestly-logo.jpeg" alt="Harvestly" style="height:34px;width:auto;display:block;object-fit:contain;">

    </a>


    <div class="header-space"></div>

</header>


<!-- =====================================================
     ERROR
====================================================== -->

<?php if (!empty($error)): ?>

<div class="checkout-error">

    <?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>


<!-- =====================================================
     MAIN
====================================================== -->

<main class="checkout-container">


<div class="checkout-title">

    <h1>
        Checkout
    </h1>

    <?php if (count($checkoutFarmers) === 1): ?>
        <p class="checkout-farmer-label">Ordering from <strong><?php echo htmlspecialchars($checkoutFarmers[0]); ?></strong></p>
    <?php elseif (count($checkoutFarmers) > 1): ?>
        <p class="checkout-farmer-label">Ordering from <strong><?php echo count($checkoutFarmers); ?> farmers</strong></p>
    <?php endif; ?>

</div>


<form
    id="checkoutForm"
    action="/Harvestly/Controller/Buyer/CheckoutController.php"
    method="POST"
    class="checkout-grid"
>

<input type="hidden" name="farmer" value="<?php echo htmlspecialchars($farmer); ?>">


<!-- =====================================================
     LEFT
====================================================== -->

<section class="checkout-left">


<!-- DELIVERY -->

<div class="checkout-card">

<div class="card-heading">

<div class="heading-icon">

<span class="material-symbols-outlined">
    local_shipping
</span>

</div>

<h2>
    Delivery Address
</h2>

</div>


<div class="form-grid">


<div class="form-group full">

<label>
    Full Name
</label>

<input
    type="text"
    name="fullName"
    placeholder="John Doe"
    required
>

</div>


<div class="form-group full">

<label>
    Address Line 1
</label>

<input
    type="text"
    name="address"
    placeholder="123 Farm Road"
    required
>

</div>


<div class="form-group">

<label>
    City
</label>

<input
    type="text"
    name="city"
    placeholder="Colombo"
    required
>

</div>


<div class="form-group">

<label>
    Postal Code
</label>

<input
    type="text"
    name="postal"
    placeholder="00100"
    required
>

</div>


<div class="form-group full">

<label>
    Phone Number
</label>

<input
    type="tel"
    name="phone"
    placeholder="07X XXX XXXX"
    required
>

</div>

<div class="form-group full">

<label>
    Destination District
</label>

<select name="destination_district" required>
    <option value="">Select your district</option>
        <option value="Ampara" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Ampara') ? 'selected' : ''; ?>>Ampara</option>
        <option value="Anuradhapura" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Anuradhapura') ? 'selected' : ''; ?>>Anuradhapura</option>
        <option value="Badulla" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Badulla') ? 'selected' : ''; ?>>Badulla</option>
        <option value="Batticaloa" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Batticaloa') ? 'selected' : ''; ?>>Batticaloa</option>
        <option value="Colombo" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
        <option value="Galle" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Galle') ? 'selected' : ''; ?>>Galle</option>
        <option value="Gampaha" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Gampaha') ? 'selected' : ''; ?>>Gampaha</option>
        <option value="Hambantota" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Hambantota') ? 'selected' : ''; ?>>Hambantota</option>
        <option value="Jaffna" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Jaffna') ? 'selected' : ''; ?>>Jaffna</option>
        <option value="Kalutara" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Kalutara') ? 'selected' : ''; ?>>Kalutara</option>
        <option value="Kandy" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
        <option value="Kegalle" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Kegalle') ? 'selected' : ''; ?>>Kegalle</option>
        <option value="Kilinochchi" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Kilinochchi') ? 'selected' : ''; ?>>Kilinochchi</option>
        <option value="Kurunegala" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Kurunegala') ? 'selected' : ''; ?>>Kurunegala</option>
        <option value="Mannar" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Mannar') ? 'selected' : ''; ?>>Mannar</option>
        <option value="Matale" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Matale') ? 'selected' : ''; ?>>Matale</option>
        <option value="Matara" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Matara') ? 'selected' : ''; ?>>Matara</option>
        <option value="Monaragala" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Monaragala') ? 'selected' : ''; ?>>Monaragala</option>
        <option value="Mullaitivu" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Mullaitivu') ? 'selected' : ''; ?>>Mullaitivu</option>
        <option value="Nuwara Eliya" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Nuwara Eliya') ? 'selected' : ''; ?>>Nuwara Eliya</option>
        <option value="Polonnaruwa" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Polonnaruwa') ? 'selected' : ''; ?>>Polonnaruwa</option>
        <option value="Puttalam" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Puttalam') ? 'selected' : ''; ?>>Puttalam</option>
        <option value="Ratnapura" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Ratnapura') ? 'selected' : ''; ?>>Ratnapura</option>
        <option value="Trincomalee" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Trincomalee') ? 'selected' : ''; ?>>Trincomalee</option>
        <option value="Vavuniya" <?php echo ((string)($_GET['destination_district'] ?? '') === 'Vavuniya') ? 'selected' : ''; ?>>Vavuniya</option>
</select>

</div>

</div>

</div>



<!-- PAYMENT -->

<div class="checkout-card">

<div class="card-heading">

<div class="heading-icon">

<span class="material-symbols-outlined">
    payments
</span>

</div>

<h2>
    Payment Method
</h2>

</div>


<div class="payment-methods">


<label
    class="payment-method selected"
    data-method="card"
>

<input
    type="radio"
    name="payment"
    value="card"
    checked
>

<div class="radio-circle"></div>

<div class="payment-content">

<strong>
    PayHere Sandbox
</strong>

<span>
    Secure test payment through the PayHere Sandbox environment.
</span>

</div>

<span class="material-symbols-outlined payment-icon">
    credit_card
</span>

</label>



</div>


<!-- PAYMENT PROCESSING NOTE -->

<div
    id="cardDetails"
    class="card-details"
>

<p class="demo-note">
    Card details are not collected here. Credit/debit card payments will be handled securely through PayHere Sandbox when payment integration is enabled.
</p>

</div>


</div>


</section>


<!-- =====================================================
     RIGHT
====================================================== -->

<aside class="checkout-right">


<div class="summary-card">

<h2>
    Order Summary
</h2>


<div class="summary-divider"></div>


<div class="summary-products">


<?php if (empty($cartItems)): ?>

<p class="empty-cart">
    Your cart is empty.
</p>

<?php endif; ?>


<?php foreach ($cartItems as $item): ?>


<?php

$itemQty = (int)(
    $item['quantity']
    ?? $item['qty']
    ?? 1
);

$itemPrice = (float)(
    $item['price']
    ?? 0
);

$itemTotal = $itemQty * $itemPrice;

?>


<div class="summary-product">


<img
    src="<?= htmlspecialchars(
        $item['image']
        ?? '/Harvestly/assets/images/vegfr.jpg'
    ) ?>"
    alt="<?= htmlspecialchars(
        $item['name']
        ?? 'Product'
    ) ?>"
>


<div class="product-info">

<strong>
<?= htmlspecialchars(
    $item['name']
    ?? 'Product'
) ?>
</strong>

<span>
Qty: <?= $itemQty ?>
</span>

</div>


<strong class="product-price">

Rs.
<?= number_format($itemTotal) ?>

</strong>


</div>


<?php endforeach; ?>


</div>


<div class="summary-divider"></div>


<div class="summary-row">

<span>
    Subtotal
</span>

<strong>
    Rs. <?= number_format($subtotal) ?>
</strong>

</div>


<div class="summary-row">

<span>
    Buyer Service Fee
</span>

<strong>
    Rs. <?= number_format($serviceFee) ?>
</strong>

</div>


<div class="summary-row">

<span>
    Delivery Fee
</span>

<strong>
    Rs. <?= number_format($deliveryFee) ?>
</strong>

</div>


<div class="summary-total">

<span>
    Total
</span>

<strong>
    Rs. <?= number_format($total) ?>
</strong>

</div>


<button
    type="submit"
    id="confirmOrderBtn"
    class="confirm-order-btn"
    <?= empty($cartItems) ? 'disabled' : '' ?>
>

<span>
    Proceed to Payment
</span>

<span class="material-symbols-outlined">
    check_circle
</span>

</button>


<div class="secure-checkout">

<span class="material-symbols-outlined">
    verified_user
</span>

Secure Checkout

</div>


</div>


</aside>


</form>


</main>


<!-- =====================================================
     SUCCESS MODAL
====================================================== -->

<?php if ($success): ?>

<div
    id="successModal"
    class="modal"
>

<div class="success-box">


<div class="success-icon">

<span class="material-symbols-outlined">
    check
</span>

</div>


<span class="success-label">
    ORDER CONFIRMED
</span>


<h2>
    Order Placed Successfully!
</h2>


<p>

Your order

<strong>
    <?= htmlspecialchars($successOrderId) ?>
</strong>

has been placed and is now being processed.

</p>


<button
    type="button"
    id="viewOrders"
    class="view-orders"
>

View My Orders

<span class="material-symbols-outlined">
    arrow_forward
</span>

</button>


</div>

</div>

<?php endif; ?>


<!-- =====================================================
     FOOTER
====================================================== -->

<footer class="checkout-footer">

<p>
© 2026 Harvestly.
Bridging Sri Lankan Fields to Your Table.
</p>

</footer>


</div>


<script src="/Harvestly/js/Buyer/checkout.js"></script>

</body>

</html>