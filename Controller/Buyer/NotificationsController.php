<?php
require_once __DIR__ . '/_bridge.php'; buyer_require_login();
$bid=currentBuyerId();
if($_SERVER['REQUEST_METHOD']==='POST'){
    header('Content-Type: application/json; charset=utf-8');
    $action=(string)($_POST['action']??''); $id=(int)($_POST['id']??0); $ok=false;
    if($action==='read'&&$id){$st=db()->prepare('UPDATE notifications SET is_read=1,read_at=NOW() WHERE notification_id=? AND user_id=?');$st->bind_param('ii',$id,$bid);$ok=$st->execute();$st->close();}
    elseif($action==='read_all'){$st=db()->prepare('UPDATE notifications SET is_read=1,read_at=NOW() WHERE user_id=?');$st->bind_param('i',$bid);$ok=$st->execute();$st->close();}
    elseif($action==='delete'&&$id){$st=db()->prepare('DELETE FROM notifications WHERE notification_id=? AND user_id=?');$st->bind_param('ii',$id,$bid);$ok=$st->execute();$st->close();}
    echo json_encode(['success'=>$ok]); exit;
}
$rows=db_fetch_all('SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC','i',[$bid]);
$notifications=array_map(function($r){$type=ucwords(strtolower(str_replace('_',' ',$r['notification_type']??'System')));$icons=['Order'=>'shopping_bag','Delivery'=>'local_shipping','Payment'=>'payments','Complaint'=>'report_problem','Review'=>'rate_review'];$icon='info';foreach($icons as $k=>$v)if(stripos($type,$k)!==false){$icon=$v;break;}return ['id'=>(int)$r['notification_id'],'type'=>$type,'title'=>$r['title'],'message'=>$r['message'],'unread'=>!(bool)$r['is_read'],'icon'=>$icon,'priority'=>'','high'=>stripos($type,'order')!==false&&!(bool)$r['is_read'],'promotion'=>false,'action'=>'View Orders','action_url'=>buyerRoute('OrdersController.php'),'time'=>date('M d, Y H:i',strtotime($r['created_at']))];},$rows);
$totalNotifications=count($notifications);$unreadNotifications=count(array_filter($notifications,fn($n)=>$n['unread']));$allOrders=buyer_orders();$totalOrders=count($allOrders);$latestOrder=$allOrders[0]??null;$totalDeliveries=count(array_filter($allOrders,fn($o)=>in_array($o['status'],['In Transit','Out for Delivery','Delivered','Completed'],true)));
require __DIR__.'/../../View/Buyer/notifications.php';
