<?php
 require 'includes/auth.php';
 require 'includes/layout.php';

 //product stats
 $st=$conn->prepare("
    SELECT COUNT(*) products,
        SUM(listing_status='ACTIVE') active,
        SUM(available_quantity<=10) low_stock 
    FROM products 
    WHERE farmer_id=?
");
 $st->bind_param('i',$farmer_id);
 $st->execute();
 $ps=$st->get_result()->fetch_assoc();

 //order stats
 $st=$conn->prepare("
    SELECT COUNT(*) orders,
        SUM(order_status IN ('PAID','ACCEPTED','PREPARING','READY_FOR_DELIVERY')) action_orders,
        COALESCE(SUM(
            CASE 
                WHEN order_status IN ('DELIVERED','COMPLETED') 
                THEN product_subtotal-farmer_marketplace_fee 
                ELSE 0 
            END
        ),0) sales 
    FROM orders 
    WHERE farmer_id=?
");
 $st->bind_param('i',$farmer_id);
 $st->execute();
 $os=$st->get_result()->fetch_assoc();

 //recent orders
 $st=$conn->prepare('
    SELECT order_id,order_status,product_subtotal,created_at 
    FROM orders 
    WHERE farmer_id=? 
    ORDER BY created_at DESC 
    LIMIT 5
');
 $st->bind_param('i',$farmer_id);
 $st->execute();
 $orders=$st->get_result();
 
 page_top('Farmer Dashboard','dashboard');
 ?>
 
 <section class="farmer-welcome">
    <div>
        <span class="dashboard-eyebrow">FARMER OVERVIEW</span>
        <h1>Welcome back, <?= e($farmer_user['full_name'] ?? 'Farmer') ?></h1>
        <p>Keep your harvest moving with a live view of listings, orders, and sales.</p>
    </div>
    <a class="btn welcome-action" href="<?= farmerRoute('add_product') ?>">+ Add Product</a>
 </section>

 <div class="page-title dashboard-page-title">
    <div>
        <h2>At a glance</h2>
        <p>Live summary from the Harvestly marketplace.</p>
    </div>
</div>


<div class="dashboard-stats">
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">▦</div>
        <div>
            <p>Products</p>
            <h3><?=e($ps['products'] ?? 0)?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✓</div>
        <div>
            <p>Active Listings</p>
            <h3><?=e($ps['active'] ?? 0)?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">▣</div>
        <div>
            <p>Orders Needing Action</p>
            <h3><?=e($os['action_orders'] ?? 0)?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">₨</div>
        <div>
            <p>Delivered/Completed Sales</p>
            <h3><?=money($os['sales'] ?? 0)?></h3>
        </div>
    </div>
</div>
</div>
<div class="card recent-orders-card">
    <div class="section-head">
        <div>
            <span class="dashboard-eyebrow">LATEST ACTIVITY</span>
            <h2>Recent Orders</h2>
        </div>
        <a class="btn secondary small" href="<?= farmerRoute('orders') ?>">View all orders</a>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows === 0): ?>
                    <tr><td colspan="4" class="empty">No orders yet. Your latest orders will appear here.</td></tr>
                <?php endif; ?>
                <?php while($r=$orders->fetch_assoc()):?>
                    <tr>
                        <td>
                            <a href="<?= farmerRoute('order_details', 'id=' . (int)$r['order_id']) ?>">#HV<?=$r['order_id']?></a>
                        </td>
                        <td><?=e($r['created_at'])?></td>
                        <td><?=money($r['product_subtotal'])?></td>
                        <td>
                            <span class="badge <?= in_array($r['order_status'], ['DELIVERED', 'COMPLETED'], true) ? '' : 'badge-pending' ?>"><?=e($r['order_status'])?></span>
                        </td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>
<?php page_bottom();?>