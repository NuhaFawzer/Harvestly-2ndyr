<?php
require_once __DIR__ . '/_bridge.php'; buyer_require_login();
$u=db_fetch_one('SELECT full_name FROM users WHERE user_id=?','i',[currentBuyerId()]);
$buyerName=$u['full_name']??($_SESSION['user_name']??'Buyer');
$n=db_fetch_one('SELECT COUNT(*) c FROM notifications WHERE user_id=? AND is_read=0','i',[currentBuyerId()]);$notificationCount=(int)($n['c']??0);
$items=buyer_cart_normalized();$cartCount=(int)array_sum(array_map(fn($x)=>(float)$x['quantity'],$items));
require __DIR__ . '/../../View/Buyer/dashboard.php';
