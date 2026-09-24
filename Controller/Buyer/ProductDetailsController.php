<?php
require_once __DIR__ . '/_bridge.php'; buyer_require_login();$product=buyer_product((int)($_GET['id']??1));if(!$product){http_response_code(404);exit('Product not found');}$images=$product['images'];$farmerImage='';require __DIR__.'/../../View/Buyer/product-details.php';
