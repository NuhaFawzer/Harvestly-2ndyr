<?php 
require 'includes/auth.php';
require 'includes/layout.php';

if($_SERVER['REQUEST_METHOD']==='POST'){ 
    $full=trim($_POST['full_name']);
    $phone=trim($_POST['phone']);
    $farm=trim($_POST['farm_name']);
    $a1=trim($_POST['pickup_address_line1']);
    $a2=trim($_POST['pickup_address_line2']);
    $city=trim($_POST['pickup_city_town']);
    $postal=trim($_POST['pickup_postal_code']);
    $conn->begin_transaction();
    try{
        $u=$conn->prepare(
            'UPDATE users 
            SET full_name=?,phone=? 
            WHERE user_id=?'
        );
        $u->bind_param('ssi',$full,$phone,$farmer_id);
        $u->execute();
        $f=$conn->prepare(
            'UPDATE farmer_profiles 
            SET farm_name=?,pickup_address_line1=?,pickup_address_line2=?,pickup_city_town=?,pickup_postal_code=? 
            WHERE farmer_id=?
        ');
        $f->bind_param('sssssi',$farm,$a1,$a2,$city,$postal,$farmer_id);
        $f->execute();
        $conn->commit();
        flash('success','Profile updated.');
        redirect('profile.php');
    }
    catch(Throwable $e){
        $conn->rollback();throw $e;
    }
}

$st=$conn->prepare(
    'SELECT u.*,fp.*,d.district_name 
    FROM users u 
    JOIN farmer_profiles fp 
    ON fp.farmer_id=u.user_id 
    JOIN districts d 
    ON d.district_id=fp.district_id 
    WHERE u.user_id=?'
);

$st->bind_param('i',$farmer_id);
$st->execute();
$p=$st->get_result()->fetch_assoc();
$docs=$conn->prepare(
    'SELECT document_type,original_file_name,status 
    FROM verification_documents 
    WHERE user_id=? 
    ORDER BY created_at DESC'
);

$docs->bind_param('i',$farmer_id);
$docs->execute();
$dr=$docs->get_result();

page_top('Farmer Profile','profile');?>




<div class="page-title">
    <div>
        <h1>Farmer Profile</h1>
        <p>Manage permitted farmer profile information.</p>
    </div>
</div>

<form class="card" method="post">
    <div class="form-grid">
        <div class="field">
            <label>Full Name</label>
            <input class="input" name="full_name" value="<?=e($p['full_name'])?>">
        </div>
        <div class="field">
            <label>Email</label>
            <input class="input" value="<?=e($p['email'])?>" disabled>
        </div>
        <div class="field">
            <label>Phone</label>
            <input class="input" name="phone" value="<?=e($p['phone'])?>">
        </div>
        <div class="field">
            <label>Farm Name</label>
            <input class="input" name="farm_name" value="<?=e($p['farm_name'])?>">
        </div>
        <div class="field">
            <label>Pickup Address</label>
            <input class="input" name="pickup_address_line1" value="<?=e($p['pickup_address_line1'])?>">
        </div>
        <div class="field">
            <label>Address Line 2</label>
            <input class="input" name="pickup_address_line2" value="<?=e($p['pickup_address_line2'])?>">
        </div>
        <div class="field">
            <label>City/Town</label>
            <input class="input" name="pickup_city_town" value="<?=e($p['pickup_city_town'])?>">
        </div>
        <div class="field">
            <label>Postal Code</label>
            <input class="input" name="pickup_postal_code" value="<?=e($p['pickup_postal_code'])?>">
        </div>
        <div class="field">
            <label>District</label>
            <input class="input" value="<?=e($p['district_name'])?>" disabled>
        </div>
        <div class="field">
            <label>Verification</label>
            <input class="input" value="<?=e($p['verification_status'])?>" disabled>
        </div>
    </div>
    <button class="btn">Save Profile</button>
</form>
<div class="card">
    <h2>Supporting Verification Documents</h2>
    <?php while($d=$dr->fetch_assoc()):?>
        <p>
            <strong><?=e($d['document_type'])?></strong> — 
            <?=e($d['original_file_name']?:'Document')?> 
            <span class="badge"><?=e($d['status'])?></span>
        </p>
    <?php endwhile;?>
</div>

<?php page_bottom();?>