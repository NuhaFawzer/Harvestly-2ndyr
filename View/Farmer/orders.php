<?php 
require 'includes/auth.php';
require 'includes/layout.php';


$st=$conn->prepare(
    'SELECT o.*,u.full_name buyer_name 
    FROM orders o 
    JOIN users u 
    ON u.user_id=o.buyer_id 
    WHERE o.farmer_id=? 
    ORDER BY o.created_at DESC'
);

$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();

page_top('Farmer Orders','orders');?>



<div class="page-title">
    <div>
        <h1>Farmer Orders</h1>
        <p>Orders belonging to your farmer account.</p>
    </div>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Buyer</th>
                    <th>Date</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while($r=$rows->fetch_assoc()):?>
                    <tr>
                        <td>#HV<?=e($r['order_id'])?></td>
                        <td><?=e($r['buyer_name'])?></td>
                        <td><?=e($r['created_at'])?></td>
                        <td><?=money($r['product_subtotal'])?></td>
                        <td>
                            <span class="badge"><?=e($r['order_status'])?></span>
                        </td>
                        <td>
                            <a class="btn secondary small" href="<?= farmerRoute('order_details', 'id=' . (int)$r['order_id']) ?>">View</a>
                        </td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>

<?php page_bottom();?>