<?php 
require 'includes/auth.php'; 
require 'includes/layout.php'; 

$categories=$conn->query(
    "SELECT category_id,category_name 
    FROM product_categories 
    WHERE is_active=1 
    ORDER BY category_name
")->fetch_all(MYSQLI_ASSOC); $p=[];

if($_SERVER['REQUEST_METHOD']==='POST'){ 
    $name=trim($_POST['product_name']); 
    $cat=(int)$_POST['category_id']; 
    $desc=trim($_POST['description']); 
    $price=(float)$_POST['unit_price']; 
    $qty=(float)$_POST['available_quantity']; 
    $unit=trim($_POST['unit_label'])?:'kg'; 
    $lt=$_POST['listing_type']; 
    $gm=$_POST['growing_method']?:null; 
    $grade=$_POST['declared_grade']?:null; 
    $status=$_POST['listing_status']; 
    $hd=$_POST['harvest_date']?:null; 
    $afd=$_POST['available_from_date']?:null; 
    $bb=$_POST['best_before_date']?:null; 
    $sg=trim($_POST['storage_guidance']);
    $st=$conn->prepare(
        'INSERT INTO products (farmer_id,category_id,product_name,description,unit_price,available_quantity,unit_label,listing_type,growing_method,declared_grade,harvest_date,available_from_date,best_before_date,storage_guidance,listing_status) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ');

    $st->bind_param('iissddsssssssss',$farmer_id,$cat,$name,$desc,$price,$qty,$unit,$lt,$gm,$grade,$hd,$afd,$bb,$sg,$status); 
    $st->execute(); $pid=$conn->insert_id;


    if(!empty($_FILES['image']['name']) && $_FILES['image']['error']===UPLOAD_ERR_OK){ 
        $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION)); 
        if(in_array($ext,['jpg','jpeg','png','webp'])){ 
            $fn='product_'.$pid.'_'.time().'.'.$ext; 
            move_uploaded_file($_FILES['image']['tmp_name'],__DIR__.'/uploads/products/'.$fn); 
            $path='uploads/products/'.$fn; 
            $im=$conn->prepare(
                'INSERT INTO product_images(product_id,image_path,is_primary) 
                VALUES (?,?,1)
            '); 

            $im->bind_param('is',$pid,$path); 
            $im->execute(); 
        }
    } 

    flash('success','Product added successfully.'); 
    redirect('products.php'); 
}

page_top('Add Product','products'); ?>





<div class="page-title">
    <div>
        <h1>Add Product</h1>
        <p>Create a new product listing.</p>
    </div>
    <a class="btn secondary" href="<?= farmerRoute('products') ?>">Back</a>
</div>
<form class="card" method="post" enctype="multipart/form-data">
    <div class="form-grid">
        <div class="field">
            <label>Product Name</label>
            <input class="input" name="product_name" required value="<?=e($p['product_name']??'')?>">
        </div>
        <div class="field">
            <label>Category</label>
            <select class="select" name="category_id" required>
                <?php foreach($categories as $c): ?>
                    <option value="<?=$c['category_id']?>" <?=($p['category_id']??0)==$c['category_id']?'selected':''?>>
                        <?=e($c['category_name'])?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field full">
            <label>Description</label>
            <textarea class="textarea" name="description">
                <?=e($p['description']??'')?>
            </textarea>
        </div>
        <div class="field">
            <label>Price (Rs.)</label>
            <input class="input" type="number" step="0.01" min="0" name="unit_price" required value="<?=e($p['unit_price']??'')?>">
        </div>
        <div class="field">
            <label>Quantity</label>
            <input class="input" type="number" step="0.001" min="0" name="available_quantity" required value="<?=e($p['available_quantity']??'0')?>">
        </div>
        <div class="field">
            <label>Unit</label>
            <input class="input" name="unit_label" value="<?=e($p['unit_label']??'kg')?>">
        </div>
        <div class="field">
            <label>Listing Type</label>
            <select class="select" name="listing_type">
                <?php foreach(['AVAILABLE_NOW','HARVEST_SOON','SEASONAL'] as $v): ?>
                    <option <?=($p['listing_type']??'AVAILABLE_NOW')===$v?'selected':''?>><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label>Growing Method</label>
            <select class="select" name="growing_method">
                <option value="">Not specified</option>
                <?php foreach(['ORGANIC','CONVENTIONAL','MIXED'] as $v): ?>
                    <option <?=($p['growing_method']??'')===$v?'selected':''?>><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label>Quality</label>
            <select class="select" name="declared_grade">
                <option value="">Not Applicable</option>
                <?php foreach(['A','B','C'] as $v): ?>
                    <option value="<?=$v?>" <?=($p['declared_grade']??'')===$v?'selected':''?>>Grade <?=$v?></option>
                <?php endforeach; ?>
            </select>
            <div class="help-text">Farmer Declared.</div>
        </div>
        <div class="field">
            <label>Status</label>
            <select class="select" name="listing_status"><?php foreach(['ACTIVE','INACTIVE','SOLD_OUT'] as $v): ?>
                <option <?=($p['listing_status']??'ACTIVE')===$v?'selected':''?>><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label>Harvest Date</label>
            <input class="input" type="date" name="harvest_date" value="<?=e($p['harvest_date']??'')?>">
        </div>
        <div class="field">
            <label>Available From</label>
            <input class="input" type="date" name="available_from_date" value="<?=e($p['available_from_date']??'')?>">
        </div>
        <div class="field">
            <label>Best Before</label>
            <input class="input" type="date" name="best_before_date" value="<?=e($p['best_before_date']??'')?>">
        </div>
        <div class="field">
            <label>Storage Instructions</label>
            <input class="input" name="storage_guidance" value="<?=e($p['storage_guidance']??'')?>">
        </div>
        <div class="field full">
            <label>Primary Product Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </div>
    </div>
    <div class="form-actions">
        <a class="btn secondary" href="<?= farmerRoute('products') ?>">Cancel</a>
        <button class="btn">Save Product</button>
    </div>
</form>

<?php page_bottom(); ?>