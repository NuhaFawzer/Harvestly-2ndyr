<?php 
require 'includes/auth.php';
require 'includes/layout.php';


$id=(int)($_GET['id']??0);
$st=$conn->prepare(
    'SELECT o.*,u.full_name buyer_name 
    FROM orders o 
    JOIN users u 
    ON u.user_id=o.buyer_id 
    WHERE o.order_id=? AND o.farmer_id=?'
);
    
$st->bind_param('ii',$id,$farmer_id);
$st->execute();
$o=$st->get_result()->fetch_assoc();

if(!$o)exit('Order not found.');
$allowed=['PAID'=>'ACCEPTED','ACCEPTED'=>'PREPARING','PREPARING'=>'READY_FOR_DELIVERY'];

if($_SERVER['REQUEST_METHOD']==='POST' && isset($allowed[$o['order_status']])){
    $next=$allowed[$o['order_status']];
    $conn->begin_transaction();
    try{$u=$conn->prepare(
        "UPDATE orders 
        SET order_status=?, accepted_at=
        IF(?='ACCEPTED',NOW(),accepted_at), ready_for_delivery_at=IF(?='READY_FOR_DELIVERY',NOW(),ready_for_delivery_at) 
        WHERE order_id=? AND farmer_id=?"
        );
        $u->bind_param('sssii',$next,$next,$next,$id,$farmer_id);
        $u->execute();$h=$conn->prepare(
            'INSERT INTO order_status_history(order_id,status,changed_by_user_id,note) 
            VALUES (?,?,?,?)');
    
        $note='Updated by farmer';
        $h->bind_param('isis',$id,$next,$farmer_id,$note);
        $h->execute();
        $conn->commit();
        flash('success','Order moved to '.$next.'.');
        redirect('order-details.php?id='.$id);
    }
    catch(Throwable $e){
        $conn->rollback();
        throw $e;}
}

$it=$conn->prepare(
    'SELECT * FROM order_items 
    WHERE order_id=?'
);

$it->bind_param('i',$id);
$it->execute();
$items=$it->get_result();

page_top('Order Details','orders');
?>



<div class="page-title">
    <div>
        <h1>Order Details — #HV<?=$id?></h1>
        <p><?=e($o['buyer_name'])?> · <?=e($o['order_status'])?></p>
    </div>
    <a class="btn secondary" href="<?= farmerRoute('orders') ?>">Back</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while($i=$items->fetch_assoc()):?>
                    <tr>
                        <td><?=e($i['product_name_snapshot'])?></td>
                        <td><?=money($i['unit_price_snapshot'])?></td>
                        <td><?=e($i['quantity'])?></td>
                        <td><?=money($i['line_total'])?></td>
                    </tr>
                <?php endwhile;?>    
            </tbody>
        </table>
    </div>
    <p>
        <strong>Product subtotal:</strong> 
        <?=money($o['product_subtotal'])?>
    </p>
    <p>
        <strong>Delivery:</strong> 
        <?=e($o['delivery_address_line1'])?>
    </p>
    <?php if(isset($allowed[$o['order_status']])):?>
        
        <form method="post">
            <button class="btn">Move to <?=e(str_replace('_',' ',$allowed[$o['order_status']]))?></button>
        </form>
        
    <?php elseif($o['order_status']==='READY_FOR_DELIVERY'):?>
        
    <div class="notice success">Ready for Delivery. Courier assignment begins after this stage.</div>
        <?php endif;?>
    </div>
<?php page_bottom();?>