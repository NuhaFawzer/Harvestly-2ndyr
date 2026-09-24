<?php 
require 'includes/auth.php';
require 'includes/layout.php';

if($_SERVER['REQUEST_METHOD']==='POST'){ $id=(int)$_POST['id'];
$qty=max(0,(float)$_POST['qty']);
$st=$conn->prepare("
    UPDATE products 
    SET available_quantity=?, listing_status=IF(?=0,'SOLD_OUT',IF(listing_status='SOLD_OUT','ACTIVE',listing_status)) 
    WHERE product_id=? 
        AND farmer_id=?
    ");
$st->bind_param('ddii',$qty,$qty,$id,$farmer_id);
$st->execute();flash('success','Stock updated.');redirect('inventory.php');}
$st=$conn->prepare('
    SELECT product_id,product_name,available_quantity,unit_label,listing_status 
    FROM products 
    WHERE farmer_id=? 
    ORDER BY product_name');
    
$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();page_top('Inventory','inventory');
?>



<div class="page-title">
    <div>
        <h1>Inventory</h1>
        <p>Update real product stock levels.</p>
    </div>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                    <th>Update Stock</th>
                </tr>
            </thead>            
            <tbody>
                <?php while($r=$rows->fetch_assoc()):?>
                    <tr>
                        <td><?=e($r['product_name'])?></td>
                        <td><?=e($r['available_quantity'].' '.$r['unit_label'])?></td>
                        <td>
                            <span class="badge">
                                <?=e($r['listing_status'])?>
                            </span>
                        </td>
                        <td>
                            <form method="post" class="table-actions">
                                <input type="hidden" name="id" value="<?=$r['product_id']?>">
                                <input class="input" style="max-width:120px" type="number" step="0.001" min="0" name="qty" value="<?=e($r['available_quantity'])?>">
                                <button class="btn small">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>

<?php page_bottom();?>