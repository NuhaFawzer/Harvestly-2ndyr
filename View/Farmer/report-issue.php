<?php 
require 'includes/auth.php';
require 'includes/layout.php';

if($_SERVER['REQUEST_METHOD']==='POST'){ 
    $oid=(int)$_POST['order_id'];
    $cat=trim($_POST['category']);
    $desc=trim($_POST['description']);
    $chk=$conn->prepare(
        'SELECT order_id 
        FROM orders 
        WHERE order_id=? AND farmer_id=?
    ');
    
    $chk->bind_param('ii',$oid,$farmer_id);
    $chk->execute();
    if(!$chk->get_result()->fetch_assoc()){
        flash('error','That order does not belong to you.');
        redirect('report-issue.php');}$path=null;
        if(!empty($_FILES['evidence']['name'])&&$_FILES['evidence']['error']===UPLOAD_ERR_OK){
            $ext=strtolower(pathinfo($_FILES['evidence']['name'],PATHINFO_EXTENSION));
            if(in_array($ext,['jpg','jpeg','png','webp','pdf'])){
                $fn='issue_'.$farmer_id.'_'.time().'.'.$ext;move_uploaded_file($_FILES['evidence']['tmp_name'],__DIR__.'/uploads/complaints/'.$fn);
                $path='uploads/complaints/'.$fn;
        }
    }
        
    $st=$conn->prepare(
        "INSERT INTO complaints(order_id,complainant_user_id,complainant_role,category,description,evidence_path) 
        VALUES (?,?,'FARMER',?,?,?)"
    );
        
    $st->bind_param('iisss',$oid,$farmer_id,$cat,$desc,$path);
    $st->execute();
    flash('success','Issue submitted.');
    redirect('report-issue.php');
}

$st=$conn->prepare(
    "SELECT * FROM complaints 
    WHERE complainant_user_id=? 
    AND complainant_role='FARMER' 
    ORDER BY created_at DESC"
);

$st->bind_param('i',$farmer_id);
$st->execute();
$rows=$st->get_result();

page_top('Report Issue','report-issue');?>




<div class="page-title">
    <div>
        <h1>Report Issue</h1>
        <p>Submit and track farmer support issues.</p>
    </div>
</div>

<form class="card" method="post" enctype="multipart/form-data">
    <div class="form-grid">
        <div class="field">
            <label>Order ID</label>
            <input class="input" type="number" name="order_id" required>
        </div>
        <div class="field">
            <label>Category</label>
            <input class="input" name="category" required placeholder="Delivery Issue">
        </div>
        <div class="field full">
            <label>Description</label>
            <textarea class="textarea" name="description" required></textarea>
        </div>
        <div class="field full">
            <label>Evidence (optional)</label>
            <input type="file" name="evidence" accept="image/*,.pdf">
        </div>
    </div>
    <button class="btn">Submit Issue</button>
</form>
<div class="card">
    <h2>Existing Issues</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Admin Response</th>
                </tr>
            </thead>
            <tbody>
                <?php while($r=$rows->fetch_assoc()):?>
                    <tr>
                        <td>#<?=$r['complaint_id']?></td>
                        <td>#HV<?=$r['order_id']?></td>
                        <td><?=e($r['category'])?></td>
                        <td><?=e($r['complaint_status'])?></td>
                        <td><?=e($r['admin_response']?:'-')?></td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>

<?php page_bottom();?>