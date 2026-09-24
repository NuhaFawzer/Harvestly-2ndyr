<?php 
require 'includes/auth.php';
require 'includes/layout.php';

//total earnings
$st=$conn->prepare("
    SELECT COALESCE(
        SUM(o.product_subtotal-o.farmer_marketplace_fee),
        0
    ) total 
    FROM orders o 
    JOIN payments p 
        ON p.order_id=o.order_id 
        AND p.payment_status='SUCCESS' 
    WHERE o.farmer_id=?
");
$st->bind_param('i',$farmer_id);
$st->execute();
$total=$st->get_result()->fetch_assoc()['total'];


//payment rows
$st=$conn->prepare("
    SELECT 
        o.order_id,
        o.product_subtotal,
        o.farmer_marketplace_fee,
        p.payment_status,
        p.paid_at 
    FROM orders o 
    JOIN payments p 
        ON p.order_id=o.order_id 
    WHERE o.farmer_id=? 
    ORDER BY p.created_at DESC
");
$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();

page_top('Earnings','earnings');
?>

    

<div class="page-title">
    <div>
        <h1>Earnings</h1>
        <p>Database payment information. No real bank transfer is performed here.</p>
    </div>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">₨</div>
        <div>
            <p>Successful Payment Earnings</p>
            <h3><?=money($total)?></h3>
        </div>
    </div>
</div>
<div class="card">
    <div class="section-head">
        <h2>Earnings History</h2>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Payment</th>
                    <th>Farmer Amount</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($r=$rows->fetch_assoc()):?>
                    <tr>
                        <td>#HV<?=$r['order_id']?></td>
                        <td><?=e($r['payment_status'])?></td>
                        <td><?=money($r['product_subtotal']-$r['farmer_marketplace_fee'])?></td>
                        <td><?=e($r['paid_at']?:'-')?></td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>
<?php page_bottom();?>