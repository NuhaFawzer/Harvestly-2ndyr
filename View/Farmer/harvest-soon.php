<?php 
require 'includes/auth.php';
require 'includes/layout.php';


if($_SERVER['REQUEST_METHOD']==='POST'){ $id=(int)$_POST['id'];
$date=$_POST['harvest_date']?:null;
$avail=$_POST['available_from_date']?:null;
$st=$conn->prepare("
    UPDATE products 
    SET harvest_date=?,available_from_date=? 
    WHERE product_id=? 
        AND farmer_id=? 
        AND listing_type='HARVEST_SOON'
    ");
        
$st->bind_param('ssii',$date,$avail,$id,$farmer_id);
$st->execute();flash('success','Harvest dates updated.');redirect('harvest-soon.php');}$st=$conn->prepare("SELECT * FROM products WHERE farmer_id=? AND listing_type='HARVEST_SOON' ORDER BY harvest_date");$st->bind_param('i',$farmer_id);$st->execute();$rows=$st->get_result();

page_top('Harvest Soon','harvest-soon');
?>


<div class="page-title">
    <div>
        <h1>Pre-Listings / Harvest Soon</h1>
        <p>Manually maintain your actual harvest dates.</p>
    </div>
    <a class="btn" href="<?= farmerRoute('add_product') ?>">+ Add Listing</a>
</div>

<?php while($r=$rows->fetch_assoc()):?>
    
<form class="card" method="post">
    <input type="hidden" name="id" value="<?=$r['product_id']?>">
    <div class="section-head">
        <strong><?=e($r['product_name'])?></strong>
        <span class="badge">HARVEST SOON</span>
    </div>

    <div class="form-grid">
        <div class="field">
            <label>Harvest Date</label>
            <input class="input" type="date" name="harvest_date" value="
                <?=e($r['harvest_date'])?>">
        </div>
        <div class="field">
            <label>Available From</label>
            <input class="input" type="date" name="available_from_date" value="<?=e($r['available_from_date'])?>">
        </div>
    </div>
    
    <button class="btn">Update Dates</button>

</form><?php endwhile;?><?php page_bottom();
?>