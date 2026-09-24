<?php 
require 'includes/auth.php';
require 'includes/layout.php';


if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['all'])){
        $st=$conn->prepare(
            'UPDATE notifications 
            SET is_read=1,read_at=NOW() 
            WHERE user_id=? AND is_read=0
        ');
        $st->bind_param('i',$farmer_id);
    }else{
        $id=(int)$_POST['id'];
        $st=$conn->prepare(
            'UPDATE notifications 
            SET is_read=1,read_at=NOW() 
            WHERE notification_id=? 
            AND user_id=?
        ');
        $st->bind_param('ii',$id,$farmer_id);
    }
    
    $st->execute();
    redirect('notifications.php');
}

$st=$conn->prepare(
    'SELECT * FROM notifications 
    WHERE user_id=? 
    ORDER BY created_at 
    DESC'
);
$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();
page_top('Notifications','notifications'); 

$notification_total=$rows->num_rows;?>



<div class="page-title">
    <div>
        <h1>Notifications</h1>
        <p>Your database notifications.</p>
    </div>

    <?php if($notification_total>0): ?>
        
    <form method="post">
        <button class="btn secondary" name="all">Mark All Read</button>
    </form>
    
    <?php endif; ?>
</div>

<?php if($notification_total===0): ?>
    
<div class="card empty">You have no notifications.</div>

<?php endif; ?>
<?php while($r=$rows->fetch_assoc()):?>
    
<div class="card notification-item <?=$r['is_read']?'':'unread'?>">
    <div class="section-head">
        <strong><?=e($r['title'])?></strong>
        <span class="badge"><?=$r['is_read']?'Read':'Unread'?></span>
    </div>
    <p><?=e($r['message'])?></p>
    <small class="muted"><?=e($r['created_at'])?></small>
    <?php if(!$r['is_read']):?>
        
    <form method="post">
        <input type="hidden" name="id" value="<?=$r['notification_id']?>">
        <button class="btn secondary small">Mark Read</button>
    </form>
    
    <?php endif;?>
</div>

<?php endwhile;?>
<?php page_bottom();?>