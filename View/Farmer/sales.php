<?php 
require 'includes/auth.php';
require 'includes/layout.php';

$st=$conn->prepare(
    "SELECT order_id,product_subtotal,farmer_marketplace_fee,order_status,created_at 
    FROM orders 
    WHERE farmer_id=? AND order_status 
    IN ('DELIVERED','COMPLETED') 
    ORDER BY created_at DESC"
);

$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();

page_top('Sales History','sales');?>




<div class="page-title">
    <div>
        <h1>Sales / Order History</h1>
        <p>Delivered and completed sales.</p>
    </div>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Product Subtotal</th>
                    <th>Marketplace Fee</th>
                    <th>Farmer Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            
            <tbody>
                <?php while($r=$rows->fetch_assoc()):?>
                    <tr>
                        <td>#HV<?=$r['order_id']?></td>
                        <td><?=e($r['created_at'])?></td>
                        <td><?=money($r['product_subtotal'])?></td>
                        <td><?=money($r['farmer_marketplace_fee'])?></td>
                        <td><?=money($r['product_subtotal']-$r['farmer_marketplace_fee'])?></td>
                        <td><?=e($r['order_status'])?></td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>

<?php page_bottom();?>