<?php
require_once __DIR__ . '/_bridge.php'; buyer_require_login();$farmer='';$cartItems=buyer_cart_normalized();if(!$cartItems)redirect('Controller/Buyer/CartController.php');$s=buyer_cart_summary($cartItems);$subtotal=$s['subtotal'];$serviceFee=$s['serviceFee'];$deliveryFee=$s['deliveryFee'];$total=$s['total'];$totalQuantity=$s['quantity'];$success=false;$successOrderId=null;$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){header('Content-Type: application/json');echo json_encode(['success'=>false,'message'=>'PayHere Sandbox checkout integration is kept for the next backend step. The merged interim build preserves the checkout UI and cart data.']);exit;}
require __DIR__.'/../../View/Buyer/checkout.php';
