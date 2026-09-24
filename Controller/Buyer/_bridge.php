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
            WHERE p.listing_status='ACTIVE' ORDER BY p.created_at DESC";
    $rows = db_fetch_all($sql);
    return array_map(function($r){
        $img=buyer_product_image($r['product_name'], $r['image_path'] ?? null);
        return [
            'id'=>(int)$r['product_id'],'name'=>$r['product_name'],'price'=>(float)$r['unit_price'],'unit'=>$r['unit_label'],
            'farmer'=>$r['farmer_name'],'rating'=>(float)$r['farmer_rating'],'reviews'=>(int)$r['review_count'],
            'fresh'=>$r['listing_type']==='AVAILABLE_NOW','organic'=>$r['growing_method']==='ORGANIC','stock'=>(int)$r['available_quantity'],
            'image'=>$img,'description'=>$r['description'] ?? '', 'harvest_date'=>$r['harvest_date'] ?: ($r['listing_type']==='AVAILABLE_NOW'?'Today':''),
            'farm'=>trim(($r['farm_name'] ?: '') . (($r['district_name'] ?? '') ? ', '.$r['district_name'] : ''), ', '),
            'farmer_email'=>$r['farmer_email'] ?? '', 'farmer_phone'=>$r['farmer_phone'] ?? '',
            'farmer_address'=>trim(implode(', ', array_filter([$r['pickup_address_line1'] ?? '', $r['pickup_address_line2'] ?? '', $r['pickup_city_town'] ?? '', $r['district_name'] ?? '', $r['pickup_postal_code'] ?? '']))),
            'farmer_verification'=>$r['farmer_verification'] ?? '',
            'farmer_rating'=>(float)$r['farmer_rating'],'experience'=>($r['farmer_verification'] ?? '') === 'APPROVED' ? 'Verified Farmer' : 'Harvestly Farmer','delivery'=>'District-based delivery available',
            'listing_type'=>$r['listing_type'],'growing_method'=>$r['growing_method'],'declared_grade'=>$r['declared_grade'],
            'images'=>[$img], 'farmerImage'=>''
        ];
    }, $rows);
}

function buyer_product(int $id): ?array { foreach (buyer_products() as $p) if ($p['id']===$id) return $p; return null; }

function buyer_cart_items(): array {
    $id=currentBuyerId();
    return db_fetch_all("SELECT p.product_id id,p.product_name name,u.full_name seller,ci.quantity,p.unit_price price,p.unit_label unit,
        (SELECT image_path FROM product_images pi WHERE pi.product_id=p.product_id ORDER BY pi.is_primary DESC,pi.image_id LIMIT 1) image_path
        FROM carts c JOIN cart_items ci ON ci.cart_id=c.cart_id JOIN products p ON p.product_id=ci.product_id JOIN users u ON u.user_id=p.farmer_id
        WHERE c.buyer_id=? ORDER BY ci.added_at", 'i', [$id]);
}
function buyer_cart_normalized(): array { return array_map(function($r){$r['image']=buyer_product_image($r['name'],$r['image_path']??null);$r['quantity']=(float)$r['quantity'];$r['price']=(float)$r['price'];return $r;}, buyer_cart_items()); }
function buyer_cart_id(): int {
    $bid=currentBuyerId(); $r=db_fetch_one('SELECT cart_id FROM carts WHERE buyer_id=? LIMIT 1','i',[$bid]);
    if($r) return (int)$r['cart_id']; $st=db()->prepare('INSERT INTO carts(buyer_id) VALUES(?)');$st->bind_param('i',$bid);$st->execute();$id=db()->insert_id;$st->close();return $id;
}
function buyer_cart_add(int $pid, float $qty=1): bool {$cid=buyer_cart_id();$st=db()->prepare('INSERT INTO cart_items(cart_id,product_id,quantity) VALUES(?,?,?) ON DUPLICATE KEY UPDATE quantity=quantity+VALUES(quantity)');$st->bind_param('iid',$cid,$pid,$qty);$ok=$st->execute();$st->close();return $ok;}
function buyer_cart_set(int $pid, float $qty): bool {if($qty<=0)return buyer_cart_remove($pid);$bid=currentBuyerId();$st=db()->prepare('UPDATE cart_items ci JOIN carts c ON c.cart_id=ci.cart_id SET ci.quantity=? WHERE c.buyer_id=? AND ci.product_id=?');$st->bind_param('dii',$qty,$bid,$pid);$ok=$st->execute();$st->close();return $ok;}
function buyer_cart_remove(int $pid): bool {$bid=currentBuyerId();$st=db()->prepare('DELETE ci FROM cart_items ci JOIN carts c ON c.cart_id=ci.cart_id WHERE c.buyer_id=? AND ci.product_id=?');$st->bind_param('ii',$bid,$pid);$ok=$st->execute();$st->close();return $ok;}
function buyer_cart_clear(): bool {$bid=currentBuyerId();$st=db()->prepare('DELETE ci FROM cart_items ci JOIN carts c ON c.cart_id=ci.cart_id WHERE c.buyer_id=?');$st->bind_param('i',$bid);$ok=$st->execute();$st->close();return $ok;}
function buyer_cart_summary(array $items): array {$sub=0;$q=0;foreach($items as $x){$sub+=(float)$x['price']*(float)$x['quantity'];$q+=(float)$x['quantity'];} $delivery=$items?350:0; return ['subtotal'=>$sub,'quantity'=>$q,'deliveryFee'=>$delivery,'serviceFee'=>0,'total'=>$sub+$delivery];}

function buyer_orders(): array {
    $rows=db_fetch_all("SELECT o.*,d.district_name FROM orders o LEFT JOIN districts d ON d.district_id=o.destination_district_id WHERE o.buyer_id=? ORDER BY o.created_at DESC",'i',[currentBuyerId()]);
    $map=['PENDING_PAYMENT'=>'Pending Payment','PAID'=>'Paid','ACCEPTED'=>'Accepted','PREPARING'=>'Preparing','READY_FOR_DELIVERY'=>'Ready for Delivery','PENDING_ASSIGNMENT'=>'Pending Assignment','ASSIGNED'=>'Assigned','PICKED_UP'=>'Picked Up','IN_TRANSIT'=>'In Transit','OUT_FOR_DELIVERY'=>'Out for Delivery','DELIVERED'=>'Delivered','COMPLETED'=>'Completed','UNDELIVERABLE'=>'Undeliverable','REJECTED'=>'Cancelled','CANCELLED'=>'Cancelled'];
    foreach($rows as &$o){$oid=(int)$o['order_id'];$items=db_fetch_all("SELECT oi.product_name_snapshot name,oi.unit_price_snapshot price,oi.quantity,p.unit_label,(SELECT image_path FROM product_images pi WHERE pi.product_id=oi.product_id ORDER BY pi.is_primary DESC,pi.image_id LIMIT 1) image_path FROM order_items oi LEFT JOIN products p ON p.product_id=oi.product_id WHERE oi.order_id=?",'i',[$oid]);foreach($items as &$i){$i['image']=buyer_product_image($i['name'],$i['image_path']??null);}unset($i);$o['db_id']=$oid;$o['id']='HV'.$oid;$o['status']=$map[$o['order_status']]??ucwords(strtolower(str_replace('_',' ',$o['order_status'])));$o['total']=(float)$o['grand_total'];$o['subtotal']=(float)$o['product_subtotal'];$o['service_fee']=(float)$o['buyer_service_fee'];$o['delivery_fee']=(float)$o['delivery_fee'];$o['destination_district']=$o['district_name']??'';$o['items']=$items;$o['images']=array_values(array_filter(array_column($items,'image')));$o['date']=date('M d, Y',strtotime($o['created_at']));$o['status_class']=strtolower(str_replace(' ','-',$o['status']));$o['button']='Track Order';$o['button_icon']='local_shipping';$o['delivery']=$o['delivered_at']?:'';}unset($o);return $rows;
}
function buyer_order_by_code(string $code): ?array {$id=(int)preg_replace('/\D/','',$code);foreach(buyer_orders() as $o) if((int)$o['db_id']===$id)return $o;return null;}
