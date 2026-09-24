<?php 
require 'includes/auth.php';
require 'includes/layout.php';


if($_SERVER['REQUEST_METHOD']==='POST'){ 
    $id=(int)$_POST['review_id'];
    $resp=trim($_POST['response']);
    $st=$conn->prepare(
        'UPDATE reviews 
        SET farmer_response=?,farmer_responded_at=NOW() 
        WHERE review_id=? AND farmer_id=?'
    );
    
    $st->bind_param('sii',$resp,$id,$farmer_id);
    $st->execute();
    flash('success','Review response saved.');
    redirect('reviews.php');
}

$st=$conn->prepare(
    'SELECT r.*,u.full_name buyer_name 
    FROM reviews r 
    JOIN users u 
    ON u.user_id=r.buyer_id 
    WHERE r.farmer_id=? 
    ORDER BY r.created_at DESC'
);

$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();

page_top('Reviews','reviews');?>




<div class="page-title">
    <div>
        <h1>Reviews</h1>
        <p>Buyer feedback for completed orders.</p>
    </div>
</div>

<?php while($r=$rows->fetch_assoc()):?>
    
<form class="card" method="post">
    <input type="hidden" name="review_id" value="<?=$r['review_id']?>">
    <div class="section-head">
        <strong><?=e($r['buyer_name'])?> · Order #HV<?=$r['order_id']?></strong>
        <span class="badge"><?=str_repeat('★',(int)$r['rating'])?> <?=e($r['rating'])?>/5</span>
    </div>
    <p><?=e($r['review_text'])?></p>
    <div class="field">
        <label>Your Response</label>
        <textarea class="textarea" name="response"><?=e($r['farmer_response'])?></textarea>
    </div>
    <button class="btn">Save Response</button>
</form>

<?php endwhile;?><?php page_bottom();?>