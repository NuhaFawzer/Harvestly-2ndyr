<?php
require_once __DIR__ . '/_bridge.php'; buyer_require_login();$bid=currentBuyerId();$success='';$error='';
function loadBuyerProfile(int $bid): array {
    $r=db_fetch_one("SELECT u.full_name name,u.email,u.phone,u.created_at,bp.default_address_line1 address,bp.default_city_town city,d.district_name district FROM users u LEFT JOIN buyer_profiles bp ON bp.buyer_id=u.user_id LEFT JOIN districts d ON d.district_id=bp.default_district_id WHERE u.user_id=?",'i',[$bid]);
    if(!$r)return []; $r['profile_image']=''; return $r;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $name=trim((string)($_POST['name']??''));$email=trim((string)($_POST['email']??''));$phone=trim((string)($_POST['phone']??''));$city=trim((string)($_POST['city']??''));$district=trim((string)($_POST['district']??''));$address=trim((string)($_POST['address']??''));
        if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL))throw new RuntimeException('Please enter a valid name and email.');
        $conn=db();$conn->begin_transaction();$st=$conn->prepare('UPDATE users SET full_name=?,email=?,phone=? WHERE user_id=?');$st->bind_param('sssi',$name,$email,$phone,$bid);$st->execute();$st->close();
        $did=null;if($district!==''){$dr=db_fetch_one('SELECT district_id FROM districts WHERE district_name=?','s',[$district]);$did=$dr?(int)$dr['district_id']:null;}
        $st=$conn->prepare('INSERT INTO buyer_profiles(buyer_id,default_address_line1,default_city_town,default_district_id) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE default_address_line1=VALUES(default_address_line1),default_city_town=VALUES(default_city_town),default_district_id=VALUES(default_district_id)');$st->bind_param('issi',$bid,$address,$city,$did);$st->execute();$st->close();$conn->commit();$_SESSION['user_name']=$name;$_SESSION['user_email']=$email;$success='Profile updated successfully.';
    }catch(Throwable $e){$error=$e->getMessage();}
}
$buyer=loadBuyerProfile($bid);$orderStats=['total'=>0,'delivered'=>0,'pending'=>0,'cancelled'=>0];foreach(buyer_orders() as $o){$orderStats['total']++;if(in_array($o['status'],['Delivered','Completed'],true))$orderStats['delivered']++;elseif($o['status']==='Cancelled')$orderStats['cancelled']++;else$orderStats['pending']++;}
require __DIR__.'/../../View/Buyer/profile.php';
