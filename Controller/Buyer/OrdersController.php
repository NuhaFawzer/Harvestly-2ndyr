<?php
require_once __DIR__ . '/_bridge.php'; buyer_require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){header('Content-Type: application/json');$code=(string)($_POST['order_id']??'');$id=(int)preg_replace('/\D/','',$code);$a=$_POST['action']??'';$ok=false;if($a==='cancel'&&$id){$st=db()->prepare("UPDATE orders SET order_status='CANCELLED',cancelled_at=NOW() WHERE order_id=? AND buyer_id=? AND order_status IN ('PENDING_PAYMENT','PAID')");$bid=currentBuyerId();$st->bind_param('ii',$id,$bid);$ok=$st->execute()&&$st->affected_rows>0;$st->close();}echo json_encode(['success'=>$ok,'order'=>$ok?buyer_order_by_code('HV'.$id):null,'message'=>$ok?'Order updated successfully.':'Order could not be updated.']);exit;}
$orders=buyer_orders();require __DIR__.'/../../View/Buyer/orders.php';
