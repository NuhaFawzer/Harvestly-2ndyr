<?php 
require 'includes/auth.php'; 
require 'includes/layout.php'; 

$id=(int)($_GET['id']??0);
$st=$conn->prepare(
    'SELECT p.*,c.category_name 
    FROM products p 
    JOIN product_categories c 
    ON c.category_id=p.category_id 
    WHERE p.product_id=? AND p.farmer_id=?
');

$st->bind_param('ii',$id,$farmer_id);
$st->execute();
$p=$st->get_result()->fetch_assoc();
if(!$p)exit('Product not found.');

page_top('Product Details','products');?>



<div class="page-title">
    <div>
        <h1><?=e($p['product_name'])?></h1>
        <p>Database product details.</p>
    </div>
    <a class="btn" href="<?= farmerRoute('edit_product', 'id=' . (int)$id) ?>">Edit</a>
</div>
<div class="card">
    <div class="form-grid">
        <div>
            <strong>Category</strong>
            <p><?=e($p['category_name'])?></p>
        </div>
        <div>
            <strong>Price</strong>
            <p><?=money($p['unit_price'])?> / <?=e($p['unit_label'])?></p>
        </div>
        <div>
            <strong>Stock</strong>
            <p><?=e($p['available_quantity'].' '.$p['unit_label'])?></p>
        </div>
        <div>
            <strong>Listing Type</strong>
            <p><?=e($p['listing_type'])?></p>
        </div>
        <div>
            <strong>Farmer Declared Grade</strong>
            <p><?=e($p['declared_grade']?:'N/A')?></p>
        </div>
        <div>
            <strong>Status</strong>
            <p><?=e($p['listing_status'])?></p>
        </div>
        <div class="field full">
            <strong>Description</strong>
            <p><?=e($p['description'])?></p>
        </div>
    </div>
</div>

<?php page_bottom();?>