<?php
require_once __DIR__ . '/../../config/app.php';

function buyer_require_login(): void { requireBuyerAuth(); }

function buyer_product_image(string $name, ?string $stored = null): string {
    if ($stored) {
        if (preg_match('#^https?://#i', $stored) || str_starts_with($stored, '/')) return $stored;
        return url($stored);
    }
    $n = strtolower($name);
    $map = [
        'carrot'=>'assets/images/Fresh Fruits & Vegetables.jpg','onion'=>'assets/images/Dambulla Red Onions.jpg','mango'=>'assets/images/Jaffna Karutha Colomban.jpg',
        'gotu'=>'assets/images/gotukola.jpg','potato'=>'assets/images/Dambulla Baby Potatoes.jpg','pineapple'=>'assets/images/Golden Pineapple.jpg',
        'spinach'=>'assets/images/Ceylon Spinach Bunch.jpg','cinnamon'=>'assets/images/Ceylon Cinnamon Quills.jpg','orange'=>'assets/images/orange.jpg',
        'passion'=>'assets/images/Uva Passion Fruit.jpg','tomato'=>'assets/images/tomato.jpg','watermelon'=>'assets/images/watermelon.jpg'
    ];
    foreach ($map as $k=>$v) if (str_contains($n,$k)) return url($v);
    return url('assets/images/Fresh Fruits & Vegetables.jpg');
}

function buyer_products(): array {
    $sql = "SELECT p.*, u.full_name farmer_name, u.email farmer_email, u.phone farmer_phone,
        fp.farm_name, fp.pickup_address_line1, fp.pickup_address_line2, fp.pickup_city_town,
        fp.pickup_postal_code, fp.verification_status farmer_verification, d.district_name,
        (SELECT image_path FROM product_images pi WHERE pi.product_id=p.product_id ORDER BY pi.is_primary DESC,pi.image_id LIMIT 1) image_path,
        COALESCE((SELECT AVG(r.rating) FROM reviews r WHERE r.farmer_id=p.farmer_id),0) farmer_rating,
        (SELECT COUNT(*) FROM reviews r WHERE r.farmer_id=p.farmer_id) review_count
        FROM products p JOIN users u ON u.user_id=p.farmer_id
        LEFT JOIN farmer_profiles fp ON fp.farmer_id=p.farmer_id
        LEFT JOIN districts d ON d.district_id=fp.district_id
        WHERE p.listing_status='ACTIVE' ORDER BY p.created_at DESC, p.product_id DESC";

    $rows = db_fetch_all($sql);

    return array_map(function($r){
        $img = buyer_product_image($r['product_name'], $r['image_path'] ?? null);

        // The seed data has some older rows where growing_method is NULL.
        // Use the description/name as a safe demo fallback so the Buyer filters
        // still behave predictably without requiring a database rewrite.
        $growingMethod = strtoupper(trim((string)($r['growing_method'] ?? '')));
        if ($growingMethod === '') {
            $textForOrganic = strtolower(($r['product_name'] ?? '') . ' ' . ($r['description'] ?? ''));
            $growingMethod = str_contains($textForOrganic, 'organic') ? 'ORGANIC' : 'CONVENTIONAL';
        }

        $listingType = strtoupper(trim((string)($r['listing_type'] ?? 'AVAILABLE_NOW')));
        $availableQuantity = (float)($r['available_quantity'] ?? 0);
        $harvestDate = $r['harvest_date'] ?? null;
        $bestBefore = $r['best_before_date'] ?? null;
        $freshToday = $listingType === 'AVAILABLE_NOW'
            || ($harvestDate && date('Y-m-d', strtotime((string)$harvestDate)) === date('Y-m-d'))
            || ($bestBefore && date('Y-m-d', strtotime((string)$bestBefore)) >= date('Y-m-d') && $listingType !== 'HARVEST_SOON');

        return [
            'id' => (int)$r['product_id'],
            'name' => $r['product_name'],
            'price' => (float)$r['unit_price'],
            'unit' => $r['unit_label'],
            'farmer' => $r['farmer_name'],
            'farmer_id' => (int)$r['farmer_id'],
            'rating' => (float)$r['farmer_rating'],
            'reviews' => (int)$r['review_count'],
            'fresh' => (bool)$freshToday,
            'organic' => $growingMethod === 'ORGANIC',
            'stock' => (int)$availableQuantity,
            'image' => $img,
            'description' => $r['description'] ?? '',
            'harvest_date' => $harvestDate ?: ($listingType === 'AVAILABLE_NOW' ? 'Today' : ''),
            'best_before_date' => $bestBefore ?: '',
            'farm' => trim(($r['farm_name'] ?: '') . (($r['district_name'] ?? '') ? ', '.$r['district_name'] : ''), ', '),
            'district' => $r['district_name'] ?? '',
            'farmer_email' => $r['farmer_email'] ?? '',
            'farmer_phone' => $r['farmer_phone'] ?? '',
            'farmer_address' => trim(implode(', ', array_filter([$r['pickup_address_line1'] ?? '', $r['pickup_address_line2'] ?? '', $r['pickup_city_town'] ?? '', $r['district_name'] ?? '', $r['pickup_postal_code'] ?? '']))),
            'farmer_verification' => $r['farmer_verification'] ?? '',
            'farmer_rating' => (float)$r['farmer_rating'],
            'experience' => ($r['farmer_verification'] ?? '') === 'APPROVED' ? 'Verified Farmer' : 'Harvestly Farmer',
            'delivery' => 'District-based delivery available',
            'listing_type' => $listingType,
            'growing_method' => $growingMethod,
            'declared_grade' => $r['declared_grade'] ?? '',
            'images' => [$img],
            'farmerImage' => ''
        ];
    }, $rows);
}

function buyer_product(int $id): ?array { foreach (buyer_products() as $p) if ($p['id']===$id) return $p; return null; }

function buyer_cart_items(): array {
    $id = currentBuyerId();
    return db_fetch_all("SELECT
        p.product_id id,
        p.product_name name,
        p.farmer_id,
        u.full_name seller,
        ci.quantity,
        p.unit_price price,
        p.unit_label unit,
        p.available_quantity available_quantity,
        p.listing_type,
        (SELECT image_path FROM product_images pi WHERE pi.product_id=p.product_id ORDER BY pi.is_primary DESC,pi.image_id LIMIT 1) image_path
        FROM carts c
        JOIN cart_items ci ON ci.cart_id=c.cart_id
        JOIN products p ON p.product_id=ci.product_id
        JOIN users u ON u.user_id=p.farmer_id
        WHERE c.buyer_id=? ORDER BY ci.added_at", 'i', [$id]);
}
function buyer_cart_normalized(): array {
    return array_map(function($r){
        $r['image'] = buyer_product_image($r['name'], $r['image_path'] ?? null);
        $r['quantity'] = (float)$r['quantity'];
        $r['price'] = (float)$r['price'];
        $r['farmer_id'] = (int)($r['farmer_id'] ?? 0);
        $r['available_quantity'] = (float)($r['available_quantity'] ?? 0);
        return $r;
    }, buyer_cart_items());
}
function buyer_cart_id(): int {
    $bid=currentBuyerId();
    $r=db_fetch_one('SELECT cart_id FROM carts WHERE buyer_id=? LIMIT 1','i',[$bid]);
    if($r) return (int)$r['cart_id'];
    $st=db()->prepare('INSERT INTO carts(buyer_id) VALUES(?)');
    $st->bind_param('i',$bid);
    $st->execute();
    $id=db()->insert_id;
    $st->close();
    return $id;
}
function buyer_cart_add(int $pid, float $qty=1): bool {
    $qty = max(1, $qty);
    $product = db_fetch_one('SELECT available_quantity FROM products WHERE product_id=? AND listing_status IN (\'ACTIVE\',\'SOLD_OUT\') LIMIT 1','i',[$pid]);
    if (!$product || (float)$product['available_quantity'] <= 0) return false;
    $cid=buyer_cart_id();
    $existing = db_fetch_one('SELECT quantity FROM cart_items WHERE cart_id=? AND product_id=? LIMIT 1','ii',[$cid,$pid]);
    $newQty = min((float)$product['available_quantity'], (float)($existing['quantity'] ?? 0) + $qty);
    $st=db()->prepare('INSERT INTO cart_items(cart_id,product_id,quantity) VALUES(?,?,?) ON DUPLICATE KEY UPDATE quantity=VALUES(quantity)');
    $st->bind_param('iid',$cid,$pid,$newQty);
    $ok=$st->execute();
    $st->close();
    return $ok;
}
function buyer_cart_set(int $pid, float $qty): bool {
    if($qty<=0)return buyer_cart_remove($pid);
    $product = db_fetch_one('SELECT available_quantity FROM products WHERE product_id=? LIMIT 1','i',[$pid]);
    if (!$product) return false;
    $qty = min($qty, (float)$product['available_quantity']);
    if ($qty <= 0) return buyer_cart_remove($pid);
    $bid=currentBuyerId();
    $st=db()->prepare('UPDATE cart_items ci JOIN carts c ON c.cart_id=ci.cart_id SET ci.quantity=? WHERE c.buyer_id=? AND ci.product_id=?');
    $st->bind_param('dii',$qty,$bid,$pid);
    $ok=$st->execute();
    $st->close();
    return $ok;
}
function buyer_cart_remove(int $pid): bool {$bid=currentBuyerId();$st=db()->prepare('DELETE ci FROM cart_items ci JOIN carts c ON c.cart_id=ci.cart_id WHERE c.buyer_id=? AND ci.product_id=?');$st->bind_param('ii',$bid,$pid);$ok=$st->execute();$st->close();return $ok;}
function buyer_cart_clear(): bool {$bid=currentBuyerId();$st=db()->prepare('DELETE ci FROM cart_items ci JOIN carts c ON c.cart_id=ci.cart_id WHERE c.buyer_id=?');$st->bind_param('i',$bid);$ok=$st->execute();$st->close();return $ok;}
function buyer_cart_summary(array $items): array {
    $sub=0.0;
    $q=0.0;
    $farmers=[];
    foreach($items as $x){
        $sub+=(float)$x['price']*(float)$x['quantity'];
        $q+=(float)$x['quantity'];
        $fid=(int)($x['farmer_id'] ?? 0);
        if($fid>0)$farmers[$fid]=true;
    }
    $farmerCount=max(0,count($farmers));
    $delivery=$items?350.0*$farmerCount:0.0;
    return ['subtotal'=>$sub,'quantity'=>$q,'farmerCount'=>$farmerCount,'deliveryFee'=>$delivery,'serviceFee'=>0.0,'total'=>$sub+$delivery];
}

function buyer_checkout_place_orders(array $data, array $items): array {
    if (!$items) {
        throw new RuntimeException('Your cart is empty.');
    }

    $districtName = trim((string)($data['destination_district'] ?? ''));
    $district = db_fetch_one('SELECT district_id, district_name FROM districts WHERE district_name=? LIMIT 1','s',[$districtName]);
    if (!$district) {
        throw new RuntimeException('Please select a valid destination district.');
    }

    $groups=[];
    foreach($items as $item){
        $fid=(int)($item['farmer_id'] ?? 0);
        if($fid<=0) continue;
        $groups[$fid][]=$item;
    }
    if(!$groups) throw new RuntimeException('No valid farmer products were found in the cart.');

    $db=db();
    $db->begin_transaction();
    $created=[];

    try {
        foreach($groups as $farmerId=>$farmerItems){
            $subtotal=0.0;
            foreach($farmerItems as $item){
                $pid=(int)$item['id'];
                $row=db_fetch_one('SELECT product_id, product_name, unit_price, available_quantity, declared_grade FROM products WHERE product_id=? AND listing_status=\'ACTIVE\' FOR UPDATE','i',[$pid]);
                if(!$row) throw new RuntimeException('One of the selected products is no longer available.');
                $qty=(float)$item['quantity'];
                if($qty<=0 || (float)$row['available_quantity'] < $qty){
                    throw new RuntimeException('Not enough stock available for '.$row['product_name'].'.');
                }
                $subtotal += (float)$row['unit_price'] * $qty;
            }

            $deliveryFee=350.0;
            $serviceFee=0.0;
            $grandTotal=$subtotal+$serviceFee+$deliveryFee;

            // Prototype sandbox checkout: mark the demo PayHere payment as successful.
            $orderStmt=$db->prepare('INSERT INTO orders
                (buyer_id, farmer_id, source_type, recipient_name, recipient_phone, delivery_address_line1,
                 delivery_city_town, delivery_postal_code, destination_district_id, product_subtotal,
                 farmer_marketplace_fee, buyer_service_fee, delivery_fee, grand_total, order_status, paid_at)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())');
            if(!$orderStmt) throw new RuntimeException('Unable to prepare the order.');
            $status='PAID';
            $sourceType='NORMAL';
            $orderBuyerId=(int)$data['buyer_id'];
            $recipientName=(string)$data['fullName'];
            $recipientPhone=(string)$data['phone'];
            $recipientAddress=(string)$data['address'];
            $recipientCity=(string)$data['city'];
            $recipientPostal=(string)$data['postal'];
            $districtId=(int)$district['district_id'];

            $orderStmt->bind_param(
                'iissssssiddddds',
                $orderBuyerId, $farmerId, $sourceType,
                $recipientName, $recipientPhone, $recipientAddress,
                $recipientCity, $recipientPostal, $districtId,
                $subtotal, $serviceFee, $serviceFee, $deliveryFee, $grandTotal, $status
            );
            if(!$orderStmt->execute()){
                throw new RuntimeException('Unable to create the order.');
            }
            $orderId=(int)$db->insert_id;
            $orderStmt->close();

            $itemStmt=$db->prepare('INSERT INTO order_items
                (order_id, product_id, product_name_snapshot, unit_price_snapshot, quantity, line_total, declared_grade_snapshot)
                VALUES (?,?,?,?,?,?,?)');
            $stockStmt=$db->prepare('UPDATE products SET available_quantity=available_quantity-? WHERE product_id=? AND available_quantity>=?');
            if(!$itemStmt || !$stockStmt) throw new RuntimeException('Unable to prepare order items.');

            foreach($farmerItems as $item){
                $pid=(int)$item['id'];
                $row=db_fetch_one('SELECT product_id, product_name, unit_price, available_quantity, declared_grade FROM products WHERE product_id=? FOR UPDATE','i',[$pid]);
                $qty=(float)$item['quantity'];
                $line=(float)$row['unit_price']*$qty;
                $grade=$row['declared_grade'] ?: null;
                $productNameSnapshot=(string)$row['product_name'];
                $unitPriceSnapshot=(float)$row['unit_price'];
                $itemStmt->bind_param('iisddds',$orderId,$pid,$productNameSnapshot,$unitPriceSnapshot,$qty,$line,$grade);
                if(!$itemStmt->execute()) throw new RuntimeException('Unable to add an order item.');
                $stockStmt->bind_param('did',$qty,$pid,$qty);
                if(!$stockStmt->execute() || $stockStmt->affected_rows!==1) throw new RuntimeException('Stock update failed for '.$row['product_name'].'.');
            }
            $itemStmt->close();
            $stockStmt->close();

            $history=$db->prepare('INSERT INTO order_status_history(order_id,status,changed_by_user_id,note) VALUES(?,?,?,?)');
            $note='Buyer completed PayHere Sandbox demo checkout.';
            $history->bind_param('isis',$orderId,$status,$data['buyer_id'],$note);
            $history->execute();
            $history->close();

            $payment=$db->prepare('INSERT INTO payments(order_id,provider,provider_reference,amount,payment_status,paid_at) VALUES(?,?,?,?,?,NOW())');
            $provider='PAYHERE_SANDBOX';
            $reference='DEMO-'.$orderId.'-'.strtoupper(bin2hex(random_bytes(3)));
            $paymentStatus='SUCCESS';
            $payment->bind_param('issds',$orderId,$provider,$reference,$grandTotal,$paymentStatus);
            if(!$payment->execute()) throw new RuntimeException('Unable to record payment.');
            $payment->close();

            $notify=$db->prepare('INSERT INTO notifications(user_id,notification_type,title,message,related_order_id,is_read) VALUES(?,?,?,?,?,0)');
            $type='Order';
            $title='Order Confirmed';
            $message='Your Harvestly order HV'.$orderId.' has been confirmed.';
            $notifyBuyerId=(int)$data['buyer_id'];
            $notify->bind_param('isssi',$notifyBuyerId,$type,$title,$message,$orderId);
            $notify->execute();
            $notify->close();

            $created[]=['id'=>'HV'.$orderId,'db_id'=>$orderId,'total'=>$grandTotal,'farmer_id'=>$farmerId];
        }

        $bid=(int)$data['buyer_id'];
        $clear=$db->prepare('DELETE ci FROM cart_items ci INNER JOIN carts c ON c.cart_id=ci.cart_id WHERE c.buyer_id=?');
        $clear->bind_param('i',$bid);
        if(!$clear->execute()) throw new RuntimeException('Order created, but the cart could not be cleared.');
        $clear->close();

        $db->commit();
        return $created;
    } catch(Throwable $e){
        $db->rollback();
        throw $e;
    }
}

function buyer_orders(): array {
    $rows=db_fetch_all("SELECT o.*,d.district_name FROM orders o LEFT JOIN districts d ON d.district_id=o.destination_district_id WHERE o.buyer_id=? ORDER BY o.created_at DESC",'i',[currentBuyerId()]);
    $map=['PENDING_PAYMENT'=>'Pending Payment','PAID'=>'Paid','ACCEPTED'=>'Accepted','PREPARING'=>'Preparing','READY_FOR_DELIVERY'=>'Ready for Delivery','PENDING_ASSIGNMENT'=>'Pending Assignment','ASSIGNED'=>'Assigned','PICKED_UP'=>'Picked Up','IN_TRANSIT'=>'In Transit','OUT_FOR_DELIVERY'=>'Out for Delivery','DELIVERED'=>'Delivered','COMPLETED'=>'Completed','UNDELIVERABLE'=>'Undeliverable','REJECTED'=>'Cancelled','CANCELLED'=>'Cancelled'];
    foreach($rows as &$o){$oid=(int)$o['order_id'];$items=db_fetch_all("SELECT oi.product_name_snapshot name,oi.unit_price_snapshot price,oi.quantity,p.unit_label,(SELECT image_path FROM product_images pi WHERE pi.product_id=oi.product_id ORDER BY pi.is_primary DESC,pi.image_id LIMIT 1) image_path FROM order_items oi LEFT JOIN products p ON p.product_id=oi.product_id WHERE oi.order_id=?",'i',[$oid]);foreach($items as &$i){$i['image']=buyer_product_image($i['name'],$i['image_path']??null);}unset($i);$o['db_id']=$oid;$o['id']='HV'.$oid;$o['status']=$map[$o['order_status']]??ucwords(strtolower(str_replace('_',' ',$o['order_status'])));$o['total']=(float)$o['grand_total'];$o['subtotal']=(float)$o['product_subtotal'];$o['service_fee']=(float)$o['buyer_service_fee'];$o['delivery_fee']=(float)$o['delivery_fee'];$o['destination_district']=$o['district_name']??'';$o['items']=$items;$o['images']=array_values(array_filter(array_column($items,'image')));$o['date']=date('M d, Y',strtotime($o['created_at']));$o['status_class']=strtolower(str_replace(' ','-',$o['status']));$o['button']='Track Order';$o['button_icon']='local_shipping';$o['delivery']=$o['delivered_at']?:'';}unset($o);return $rows;
}
function buyer_order_by_code(string $code): ?array {$id=(int)preg_replace('/\D/','',$code);foreach(buyer_orders() as $o) if((int)$o['db_id']===$id)return $o;return null;}
