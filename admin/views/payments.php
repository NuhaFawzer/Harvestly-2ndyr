<?php
/**
 * Admin Payments Dedicated View (PayHere Escrow Transaction Audit)
 * Security Check: No raw card numbers or CVV displayed or stored.
 */
$currentPage = 'admin_payments';
?>

<div class="view-container">
    <div class="page-header mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Payment Audit & PayHere Sandbox Transactions</h1>
        <p class="text-sm text-on-surface-variant">Escrow payment logs, transaction verification, and secure payment status monitor</p>
    </div>

    <!-- Security Compliance Banner -->
    <div class="alert alert-info mb-6 flex items-center gap-3">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div>
            <strong>PCI-DSS Compliant Payment Logging:</strong> Raw credit card numbers and CVV codes are never logged, stored, or rendered in administrative screens. Payments are processed securely via PayHere Gateway & Escrow.
        </div>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Order #</th>
                        <th>Buyer</th>
                        <th>PayHere / Gateway Reference</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-on-surface-variant">No transaction payment records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $pay): ?>
                            <tr>
                                <td><strong>#PAY-<?= sprintf('%04d', $pay['id']); ?></strong></td>
                                <td class="font-bold text-primary"><?= sanitize($pay['order_number']); ?></td>
                                <td><?= sanitize($pay['buyer_name']); ?></td>
                                <td><code class="text-xs bg-surface-container px-2 py-1 rounded"><?= sanitize($pay['provider_reference'] ?? 'PAYHERE-SANDBOX-REF'); ?></code></td>
                                <td><strong class="text-primary">Rs. <?= number_format($pay['amount'], 2); ?></strong></td>
                                <td>
                                    <?php
                                    $st = strtoupper($pay['payment_status']);
                                    $badgeClass = 'badge-info';
                                    if ($st === 'SUCCESS' || $st === 'PAID') $badgeClass = 'badge-success';
                                    else if ($st === 'PENDING') $badgeClass = 'badge-warning';
                                    else if ($st === 'FAILED' || $st === 'CANCELLED') $badgeClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass; ?>"><?= sanitize($st); ?></span>
                                </td>
                                <td><?= date('M d, Y H:i', strtotime($pay['paid_at'] ?? 'now')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
