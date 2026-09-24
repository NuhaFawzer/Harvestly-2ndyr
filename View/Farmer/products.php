<?php require 'includes/auth.php'; require 'includes/layout.php';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_id'])){
    $id=(int)$_POST['delete_id'];
    $own=$conn->prepare(
        'SELECT product_id, product_name 
        FROM products 
        WHERE product_id=? AND farmer_id=?'
    );
    $own->bind_param('ii',$id,$farmer_id); $own->execute(); $product=$own->get_result()->fetch_assoc();
    if(!$product){ flash('error','Product not found.'); redirect('products.php'); }

    $checks=['cart_items','order_items','preorders']; $inUse=false;
    foreach($checks as $table){
        $st=$conn->prepare(
            "SELECT COUNT(*) c 
            FROM $table 
            WHERE product_id=?"
        );
        $st->bind_param('i',$id); $st->execute();
        if((int)$st->get_result()->fetch_assoc()['c']>0){ $inUse=true; break; }
    }
    if($inUse){
        flash('error','This product is linked to cart, order, or preorder history, so it cannot be permanently deleted. Deactivate it instead.');
        redirect('products.php');
    }

    try{
        $conn->begin_transaction();
        $img=$conn->prepare(
            'DELETE FROM product_images 
            WHERE product_id=?'
        ); 
        $img->bind_param('i',$id); $img->execute();
        $del=$conn->prepare(
            'DELETE FROM products 
            WHERE product_id=? AND farmer_id=?'
        ); 
        $del->bind_param('ii',$id,$farmer_id); $del->execute();
        if($del->affected_rows!==1){ throw new Exception('Product could not be deleted.'); }
        $conn->commit();
        flash('success','Product permanently deleted.');
    }catch(Throwable $e){
        $conn->rollback();
        flash('error','Product could not be deleted safely. Deactivate it instead.');
    }
    redirect('products.php');
}


if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['toggle_id'])){ 
    $id=(int)$_POST['toggle_id'];
    $status=$_POST['status']==='ACTIVE'?'INACTIVE':'ACTIVE'; 
    $st=$conn->prepare(
        'UPDATE products 
        SET listing_status=? 
        WHERE product_id=? AND farmer_id=?'
    ); 
    $st->bind_param('sii',$status,$id,$farmer_id); 
    $st->execute(); 
    flash('success','Product status updated.'); 
    redirect('products.php'); 
}

$q=$conn->prepare(
    'SELECT p.*,c.category_name,(
        SELECT image_path 
        FROM product_images pi 
        WHERE pi.product_id=p.product_id 
        ORDER BY is_primary DESC,image_id LIMIT 1
        ) 
    image_path 
    FROM products p 
    JOIN product_categories c 
    ON c.category_id=p.category_id 
    WHERE p.farmer_id=? 
    ORDER BY p.created_at DESC'
); 

$q->bind_param('i',$farmer_id); 
$q->execute(); 
$rows=$q->get_result(); 

page_top('Products','products'); ?>




<div class="page-title">
    <div>
        <h1>Products</h1>
        <p>Manage your database-connected Harvestly listings.</p>
    </div>
    <a class="btn" href="<?= farmerRoute('add_product') ?>">+ Add Product</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Listing Type</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Farmer Declared Grade</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($r=$rows->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong><?=e($r['product_name'])?></strong>
                        </td>
                        <td><?=e($r['category_name'])?></td>
                        <td><?=e(str_replace('_',' ',$r['listing_type']))?></td>
                        <td><?=money($r['unit_price'])?>/<?=e($r['unit_label'])?></td>
                        <td><?=e($r['available_quantity'].' '.$r['unit_label'])?></td>
                        <td><?=e($r['declared_grade'] ?: 'N/A')?> 
                            <small class="muted">(Farmer Declared)</small>
                        </td>
                        <td>
                            <span class="badge"><?=e($r['listing_status'])?></span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a class="btn secondary small" href="<?= farmerRoute('product_details', 'id=' . (int)$r['product_id']) ?>">View</a>
                                <a class="btn secondary small" href="<?= farmerRoute('edit_product', 'id=' . (int)$r['product_id']) ?>">Edit</a>
                                
                                <form method="post" style="display:inline">
                                    <input type="hidden" name="toggle_id" value="<?=$r['product_id']?>">
                                    <input type="hidden" name="status" value="<?=e($r['listing_status'])?>">
                                    <button class="btn danger small"><?= $r['listing_status']==='ACTIVE'?'Deactivate':'Activate' ?></button>
                                </form>
                                
                                <form method="post" style="display:inline" onsubmit="return confirm('Permanently delete this product? This cannot be undone.');">
                                    <input type="hidden" name="delete_id" value="<?=$r['product_id']?>">
                                    <button class="btn danger small" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php page_bottom(); ?>