-- Seed default users, profiles, categories, products, orders, payments, settlements, complaints into harvestly database
USE harvestly;

-- 1. Insert Default Admin & Users
INSERT INTO users (user_id, role, full_name, email, password_hash, phone, account_status) VALUES
(1, 'ADMIN', 'System Administrator', 'admin@harvestly.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 11 234 5678', 'ACTIVE'),
(2, 'BUYER', 'Kasun Jayasinghe', 'buyer@gmail.com', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 77 888 9999', 'ACTIVE'),
(3, 'FARMER', 'Sunil Perera', 'farmer@harvestly.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 77 123 4567', 'ACTIVE'),
(4, 'FARMER', 'Rohan Fernando', 'rohan@farm.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 76 345 6789', 'PENDING'),
(5, 'COURIER_PARTNER', 'Lanka Agro Logistics', 'courier@lankaagro.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 11 234 9999', 'ACTIVE'),
(6, 'COURIER_PARTNER', 'Ceylon Fresh Transit', 'info@ceylontransit.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 81 445 6789', 'PENDING')
ON DUPLICATE KEY UPDATE full_name = VALUES(full_name), account_status = VALUES(account_status);

-- 2. Buyer Profile
INSERT INTO buyer_profiles (buyer_id, default_address_line1, default_city_town, default_district_id) VALUES
(2, '12 Rajagiriya Road', 'Colombo 08', 1)
ON DUPLICATE KEY UPDATE default_address_line1 = VALUES(default_address_line1);

-- 3. Farmer Profiles
INSERT INTO farmer_profiles (farmer_id, farm_name, pickup_address_line1, district_id, verification_status) VALUES
(3, 'Sunlight Highlands Farm', 'Moon Plains, Nuwara Eliya', 6, 'APPROVED'),
(4, 'Vadamarachchi Orchards', 'Point Pedro Road, Jaffna', 10, 'PENDING')
ON DUPLICATE KEY UPDATE verification_status = VALUES(verification_status);

-- 4. Courier Profiles
INSERT INTO courier_partner_profiles (courier_partner_id, organisation_name, contact_person_name, office_district_id, verification_status) VALUES
(5, 'Lanka Agro Logistics Pvt Ltd', 'Nimal Silva', 1, 'APPROVED'),
(6, 'Ceylon Fresh Transit Co.', 'Dinesh Wijesinghe', 6, 'PENDING')
ON DUPLICATE KEY UPDATE verification_status = VALUES(verification_status);

-- 5. Verification Documents
INSERT INTO verification_documents (user_id, document_type, original_file_name, stored_file_path, status) VALUES
(3, 'National Identity Card (NIC)', 'nic_sunil.jpg', 'assets/docs/nic_sunil.jpg', 'APPROVED'),
(4, 'National Identity Card (NIC)', 'nic_rohan.jpg', 'assets/docs/nic_rohan.jpg', 'PENDING'),
(5, 'Business Verification Document', 'doc_lanka_agro.pdf', 'assets/docs/doc_lanka_agro.pdf', 'APPROVED'),
(6, 'Business Verification Document', 'doc_ceylon_fresh.pdf', 'assets/docs/doc_ceylon_fresh.pdf', 'PENDING')
ON DUPLICATE KEY UPDATE status = VALUES(status);

-- 6. Product Categories
INSERT IGNORE INTO product_categories (category_id, category_name, is_active) VALUES
(1, 'Vegetables', 1),
(2, 'Fresh Fruits', 1),
(3, 'Leafy Greens', 1),
(4, 'Spices & Staples', 1),
(5, 'Organic Produce', 1);

-- 7. Products
INSERT INTO products (product_id, farmer_id, category_id, product_name, description, unit_price, available_quantity, unit_label, listing_type, declared_grade, shelf_life_reference_id, listing_status) VALUES
(1, 3, 1, 'Nuwara Eliya Crisp Carrots', 'Farm-fresh organic carrots harvested daily', 420.00, 150.000, 'kg', 'AVAILABLE_NOW', 'A', NULL, 'ACTIVE'),
(2, 3, 1, 'Dambulla Red Onions', 'High quality local red onions', 380.00, 200.000, 'kg', 'AVAILABLE_NOW', 'A', NULL, 'ACTIVE'),
(3, 3, 2, 'Jaffna Karutha Colomban Mangoes', 'Sweet seasonal tropical mangoes', 650.00, 80.000, 'kg', 'SEASONAL', 'A', 11, 'ACTIVE'),
(4, 3, 3, 'Highland Gotu Kola Bundle', 'Fresh nutrient-rich leafy green bundles', 160.00, 50.000, 'bundle', 'AVAILABLE_NOW', 'A', NULL, 'ACTIVE')
ON DUPLICATE KEY UPDATE product_name = VALUES(product_name), shelf_life_reference_id = VALUES(shelf_life_reference_id);

-- 8. Orders
INSERT INTO orders (order_id, buyer_id, farmer_id, recipient_name, recipient_phone, delivery_address_line1, destination_district_id, product_subtotal, delivery_fee, grand_total, order_status) VALUES
(1, 2, 3, 'Kasun Jayasinghe', '+94 77 888 9999', '12 Rajagiriya Road, Colombo 08', 1, 1260.00, 150.00, 1410.00, 'DELIVERED'),
(2, 2, 3, 'Kasun Jayasinghe', '+94 77 888 9999', '12 Rajagiriya Road, Colombo 08', 1, 670.00, 220.00, 890.00, 'IN_TRANSIT'),
(3, 2, 3, 'Kasun Jayasinghe', '+94 77 888 9999', '12 Rajagiriya Road, Colombo 08', 1, 650.00, 150.00, 800.00, 'PENDING_ASSIGNMENT')
ON DUPLICATE KEY UPDATE order_status = VALUES(order_status);

-- 9. Order Items
INSERT INTO order_items (order_item_id, order_id, product_id, product_name_snapshot, unit_price_snapshot, quantity, line_total, declared_grade_snapshot) VALUES
(1, 1, 1, 'Nuwara Eliya Crisp Carrots', 420.00, 3.000, 1260.00, 'A'),
(2, 2, 2, 'Dambulla Red Onions', 380.00, 1.760, 670.00, 'A'),
(3, 3, 3, 'Jaffna Karutha Colomban Mangoes', 650.00, 1.000, 650.00, 'A')
ON DUPLICATE KEY UPDATE product_name_snapshot = VALUES(product_name_snapshot);

-- 10. Deliveries
INSERT INTO deliveries (delivery_id, order_id, courier_partner_id, delivery_status) VALUES
(1, 1, 5, 'DELIVERED'),
(2, 2, 5, 'IN_TRANSIT'),
(3, 3, NULL, 'PENDING_ASSIGNMENT')
ON DUPLICATE KEY UPDATE delivery_status = VALUES(delivery_status);

-- 11. Payments
INSERT INTO payments (payment_id, order_id, provider, provider_reference, amount, payment_status, paid_at) VALUES
(1, 1, 'PAYHERE_SANDBOX', 'PAYHERE-REF-001', 1410.00, 'SUCCESS', '2026-09-01 10:00:00'),
(2, 2, 'PAYHERE_SANDBOX', 'PAYHERE-REF-002', 890.00, 'SUCCESS', '2026-09-03 09:15:00'),
(3, 3, 'PAYHERE_SANDBOX', 'PAYHERE-REF-003', 800.00, 'PENDING', NULL)
ON DUPLICATE KEY UPDATE payment_status = VALUES(payment_status);

-- 12. Settlements
INSERT INTO settlements (settlement_id, period_start, period_end, total_amount, settlement_status) VALUES
(1, '2026-09-01', '2026-09-07', 1260.00, 'COMPLETED')
ON DUPLICATE KEY UPDATE total_amount = VALUES(total_amount);

-- 13. Complaints
INSERT INTO complaints (complaint_id, order_id, complainant_user_id, complainant_role, category, description, complaint_status) VALUES
(1, 1, 2, 'BUYER', 'Damaged Goods', 'Produce cartons were squished during transit resulting in damaged carrots.', 'OPEN')
ON DUPLICATE KEY UPDATE complaint_status = VALUES(complaint_status);

-- 14. Notifications
INSERT INTO notifications (notification_id, user_id, notification_type, title, message, is_read) VALUES
(1, 1, 'VERIFICATION_PENDING', 'New Farmer Pending Verification', 'Farmer Rohan Fernando submitted registration documents for Admin review.', 0)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- 15. Expanded demonstration data for all Admin dashboard tabs
INSERT INTO users (user_id, role, full_name, email, password_hash, phone, account_status) VALUES
(7, 'BUYER', 'Tharushi Weerasinghe', 'tharushi@harvestly.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 71 234 1188', 'ACTIVE'),
(8, 'FARMER', 'Nadeesha Kumari', 'nadeesha@greenfields.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 77 654 2211', 'PENDING'),
(9, 'COURIER_PARTNER', 'Island Harvest Express', 'ops@islandharvest.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 76 876 4433', 'PENDING'),
(10, 'FARMER', 'Dambulla Green Fields', 'farm@dambullagreen.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 75 345 8800', 'ACTIVE'),
(11, 'COURIER_PARTNER', 'Southern Fresh Routes', 'dispatch@southernfresh.lk', '$2y$10$EJJb8beLrsmAgHIf.8.6ROkqPyKD92zu7iEXx0/OATeSinmwiffPK', '+94 91 555 6677', 'ACTIVE')
ON DUPLICATE KEY UPDATE full_name = VALUES(full_name), account_status = VALUES(account_status);

INSERT INTO buyer_profiles (buyer_id, default_address_line1, default_city_town, default_district_id) VALUES
(7, '45 Lake View Avenue', 'Kandy', 4)
ON DUPLICATE KEY UPDATE default_address_line1 = VALUES(default_address_line1), default_district_id = VALUES(default_district_id);

INSERT INTO farmer_profiles (farmer_id, farm_name, pickup_address_line1, district_id, verification_status) VALUES
(8, 'Green Fields Family Farm', 'Welimada Road', 23, 'PENDING'),
(10, 'Dambulla Green Fields', 'Inamaluwa Road', 5, 'APPROVED')
ON DUPLICATE KEY UPDATE farm_name = VALUES(farm_name), verification_status = VALUES(verification_status);

INSERT INTO courier_partner_profiles (courier_partner_id, organisation_name, contact_person_name, office_district_id, availability_status, verification_status) VALUES
(9, 'Island Harvest Express', 'Mihira Perera', 2, 'AVAILABLE', 'PENDING'),
(11, 'Southern Fresh Routes', 'Sahan de Silva', 7, 'AVAILABLE', 'APPROVED')
ON DUPLICATE KEY UPDATE organisation_name = VALUES(organisation_name), availability_status = VALUES(availability_status), verification_status = VALUES(verification_status);

INSERT INTO verification_documents (user_id, document_type, original_file_name, stored_file_path, status) VALUES
(8, 'National Identity Card (NIC)', 'nic_nadeesha.jpg', 'assets/docs/nic_nadeesha.jpg', 'PENDING'),
(9, 'Business Verification Document', 'doc_island_harvest.pdf', 'assets/docs/doc_island_harvest.pdf', 'PENDING'),
(10, 'National Identity Card (NIC)', 'nic_dambulla.jpg', 'assets/docs/nic_dambulla.jpg', 'APPROVED'),
(11, 'Business Verification Document', 'doc_southern_fresh.pdf', 'assets/docs/doc_southern_fresh.pdf', 'APPROVED')
ON DUPLICATE KEY UPDATE status = VALUES(status);

INSERT INTO product_categories (category_id, category_name, description, is_active) VALUES
(6, 'Root Vegetables', 'Fresh carrots, potatoes, onions, and other locally grown roots.', 1),
(7, 'Tropical Fruits', 'Seasonal fruits sourced from Sri Lankan family orchards.', 1),
(8, 'Tea & Herbs', 'Island-grown tea leaves and culinary herbs.', 1)
ON DUPLICATE KEY UPDATE description = VALUES(description), is_active = VALUES(is_active);

INSERT INTO products (product_id, farmer_id, category_id, product_name, description, unit_price, available_quantity, unit_label, listing_type, declared_grade, listing_status) VALUES
(5, 10, 6, 'Dambulla Baby Potatoes', 'Washed baby potatoes harvested from Dambulla farms.', 290.00, 125.000, 'kg', 'AVAILABLE_NOW', 'A', 'ACTIVE'),
(6, 10, 7, 'Kandy Golden Pineapple', 'Naturally sweet pineapple selected at peak ripeness.', 480.00, 60.000, 'piece', 'SEASONAL', 'A', 'ACTIVE'),
(7, 10, 3, 'Ceylon Spinach Bunch', 'Fresh leafy spinach picked each morning.', 180.00, 90.000, 'bunch', 'AVAILABLE_NOW', 'B', 'ACTIVE'),
(8, 3, 4, 'Ceylon Cinnamon Quills', 'Fragrant true cinnamon quills from smallholder growers.', 1250.00, 40.000, 'kg', 'AVAILABLE_NOW', 'A', 'ACTIVE'),
(9, 10, 2, 'Matale Sweet Oranges', 'Juicy oranges from the Matale growing belt.', 360.00, 100.000, 'kg', 'HARVEST_SOON', 'B', 'ACTIVE'),
(10, 8, 7, 'Uva Passion Fruit', 'Small-batch passion fruit from a pending farmer listing.', 720.00, 35.000, 'kg', 'SEASONAL', 'A', 'ACTIVE')
ON DUPLICATE KEY UPDATE product_name = VALUES(product_name), available_quantity = VALUES(available_quantity), listing_status = VALUES(listing_status);

INSERT INTO orders (order_id, buyer_id, farmer_id, recipient_name, recipient_phone, delivery_address_line1, destination_district_id, product_subtotal, delivery_fee, grand_total, order_status, paid_at, delivered_at) VALUES
(4, 7, 10, 'Tharushi Weerasinghe', '+94 71 234 1188', '45 Lake View Avenue, Kandy', 4, 960.00, 185.00, 1145.00, 'COMPLETED', '2026-09-06 09:20:00', '2026-09-08 16:10:00'),
(5, 2, 10, 'Kasun Jayasinghe', '+94 77 888 9999', '12 Rajagiriya Road, Colombo 08', 1, 870.00, 210.00, 1080.00, 'OUT_FOR_DELIVERY', '2026-09-10 08:15:00', NULL),
(6, 7, 3, 'Tharushi Weerasinghe', '+94 71 234 1188', '45 Lake View Avenue, Kandy', 4, 1250.00, 160.00, 1410.00, 'ACCEPTED', '2026-09-11 12:00:00', NULL),
(7, 2, 10, 'Kasun Jayasinghe', '+94 77 888 9999', '12 Rajagiriya Road, Colombo 08', 1, 480.00, 175.00, 655.00, 'PENDING_PAYMENT', NULL, NULL),
(8, 7, 10, 'Tharushi Weerasinghe', '+94 71 234 1188', '45 Lake View Avenue, Kandy', 4, 720.00, 220.00, 940.00, 'READY_FOR_DELIVERY', '2026-09-12 10:40:00', NULL)
ON DUPLICATE KEY UPDATE order_status = VALUES(order_status), grand_total = VALUES(grand_total);

INSERT INTO order_items (order_item_id, order_id, product_id, product_name_snapshot, unit_price_snapshot, quantity, line_total, declared_grade_snapshot) VALUES
(4, 4, 5, 'Dambulla Baby Potatoes', 290.00, 2.000, 580.00, 'A'),
(5, 4, 6, 'Kandy Golden Pineapple', 480.00, 1.000, 480.00, 'A'),
(6, 5, 5, 'Dambulla Baby Potatoes', 290.00, 3.000, 870.00, 'A'),
(7, 6, 8, 'Ceylon Cinnamon Quills', 1250.00, 1.000, 1250.00, 'A'),
(8, 7, 6, 'Kandy Golden Pineapple', 480.00, 1.000, 480.00, 'A'),
(9, 8, 10, 'Uva Passion Fruit', 720.00, 1.000, 720.00, 'A')
ON DUPLICATE KEY UPDATE line_total = VALUES(line_total);

INSERT INTO deliveries (delivery_id, order_id, courier_partner_id, delivery_status, assigned_at, accepted_at, picked_up_at, in_transit_at, out_for_delivery_at, delivered_at, buyer_confirmation_deadline, completed_at) VALUES
(4, 4, 11, 'COMPLETED', '2026-09-07 08:00:00', '2026-09-07 08:20:00', '2026-09-07 11:00:00', '2026-09-07 12:00:00', '2026-09-08 09:00:00', '2026-09-08 16:10:00', '2026-09-10 16:10:00', '2026-09-10 16:10:00'),
(5, 5, 11, 'OUT_FOR_DELIVERY', '2026-09-10 09:00:00', '2026-09-10 09:15:00', '2026-09-10 10:20:00', '2026-09-10 11:00:00', '2026-09-10 14:30:00', NULL, NULL, NULL),
(6, 6, 5, 'ACCEPTED', '2026-09-11 12:30:00', '2026-09-11 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, NULL, 'PENDING_ASSIGNMENT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, 5, 'ASSIGNED', '2026-09-12 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE delivery_status = VALUES(delivery_status), courier_partner_id = VALUES(courier_partner_id);

INSERT INTO payments (payment_id, order_id, provider, provider_reference, amount, payment_status, paid_at) VALUES
(4, 4, 'PAYHERE_SANDBOX', 'PAYHERE-REF-004', 1145.00, 'SUCCESS', '2026-09-06 09:20:00'),
(5, 5, 'PAYHERE_SANDBOX', 'PAYHERE-REF-005', 1080.00, 'SUCCESS', '2026-09-10 08:15:00'),
(6, 6, 'PAYHERE_SANDBOX', 'PAYHERE-REF-006', 1410.00, 'SUCCESS', '2026-09-11 12:00:00'),
(7, 7, 'PAYHERE_SANDBOX', 'PAYHERE-REF-007', 655.00, 'FAILED', NULL),
(8, 8, 'PAYHERE_SANDBOX', 'PAYHERE-REF-008', 940.00, 'SUCCESS', '2026-09-12 10:40:00')
ON DUPLICATE KEY UPDATE payment_status = VALUES(payment_status), amount = VALUES(amount);

INSERT INTO settlements (settlement_id, period_start, period_end, total_amount, settlement_status, processed_by, processed_at) VALUES
(2, '2026-09-08', '2026-09-14', 2140.00, 'PROCESSING', NULL, NULL),
(3, '2026-08-25', '2026-08-31', 3860.00, 'COMPLETED', 1, '2026-09-01 15:00:00')
ON DUPLICATE KEY UPDATE total_amount = VALUES(total_amount), settlement_status = VALUES(settlement_status);

INSERT IGNORE INTO earnings (earning_id, order_id, beneficiary_type, beneficiary_user_id, earning_type, amount, earning_status, settled_at) VALUES
(1, 1, 'FARMER', 3, 'FARMER_NET', 1234.80, 'PAID', '2026-09-07 10:00:00'),
(2, 1, 'COURIER_PARTNER', 5, 'COURIER_DELIVERY', 150.00, 'PAID', '2026-09-07 10:00:00'),
(3, 4, 'FARMER', 10, 'FARMER_NET', 940.80, 'PAID', '2026-09-10 16:30:00'),
(4, 4, 'COURIER_PARTNER', 11, 'COURIER_DELIVERY', 185.00, 'PAID', '2026-09-10 16:30:00'),
(5, 5, 'FARMER', 10, 'FARMER_NET', 852.60, 'PENDING_PAYOUT', NULL),
(6, 6, 'FARMER', 3, 'FARMER_NET', 1225.00, 'EARNED', NULL),
(7, 8, 'COURIER_PARTNER', 5, 'COURIER_DELIVERY', 220.00, 'HELD', NULL);

INSERT IGNORE INTO settlement_items (settlement_item_id, settlement_id, earning_id) VALUES
(1, 1, 1), (2, 1, 2), (3, 3, 3), (4, 3, 4);

INSERT IGNORE INTO delivery_assignment_offers (offer_id, order_id, courier_partner_id, priority_rank, offer_status, offered_at, expires_at, responded_at) VALUES
(1, 3, 5, 1, 'PENDING', '2026-09-05 09:00:00', '2026-09-05 09:30:00', NULL),
(2, 7, 11, 1, 'EXPIRED', '2026-09-11 15:00:00', '2026-09-11 15:30:00', NULL),
(3, 8, 5, 1, 'ACCEPTED', '2026-09-12 11:00:00', '2026-09-12 11:30:00', '2026-09-12 11:08:00');

INSERT IGNORE INTO delivery_attempts (attempt_id, delivery_id, attempt_number, attempt_result, notes, attempted_at) VALUES
(1, 4, 1, 'DELIVERED', 'Buyer received the produce in good condition.', '2026-09-08 16:10:00'),
(2, 5, 1, 'OTHER_ISSUE', 'Traffic delay reported near Colombo city.', '2026-09-10 15:00:00');

INSERT INTO reviews (review_id, order_id, buyer_id, farmer_id, rating, review_text, farmer_response, farmer_responded_at) VALUES
(1, 1, 2, 3, 4, 'Fresh carrots and careful packing. Delivery was slightly delayed.', 'Thank you for the helpful feedback.', '2026-09-09 10:00:00'),
(2, 4, 7, 10, 5, 'Excellent potatoes and pineapple. Everything arrived fresh.', NULL, NULL)
ON DUPLICATE KEY UPDATE rating = VALUES(rating), review_text = VALUES(review_text);

INSERT INTO complaints (complaint_id, order_id, complainant_user_id, complainant_role, category, description, complaint_status, admin_response, reviewed_by, reviewed_at, resolved_at) VALUES
(2, 2, 2, 'BUYER', 'Late Delivery', 'The order remained in transit longer than the expected delivery window.', 'UNDER_REVIEW', NULL, NULL, NULL, NULL),
(3, 4, 7, 'BUYER', 'Packaging', 'One produce box arrived with a loose seal.', 'RESOLVED', 'Packaging guidance sent to the courier partner.', 1, '2026-09-10 09:00:00', '2026-09-10 09:00:00'),
(4, 5, 10, 'FARMER', 'Pickup Delay', 'Courier pickup arrived later than the confirmed pickup window.', 'OPEN', NULL, NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE complaint_status = VALUES(complaint_status), admin_response = VALUES(admin_response);

INSERT INTO notifications (notification_id, user_id, notification_type, title, message, related_order_id, is_read) VALUES
(2, 1, 'DELIVERY_EXCEPTION', 'Delivery exception needs review', 'Order ORD-2026-5 has a reported delivery delay.', 5, 0),
(3, 1, 'PAYMENT_FAILED', 'Payment failed', 'Payment for order ORD-2026-7 requires buyer follow-up.', 7, 0),
(4, 1, 'COURIER_PENDING', 'Courier approval pending', 'Island Harvest Express is waiting for business verification.', NULL, 0),
(5, 7, 'ORDER_UPDATE', 'Order completed', 'Your order ORD-2026-4 has been completed. Thank you for shopping with Harvestly.', 4, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title), message = VALUES(message), is_read = VALUES(is_read);

INSERT IGNORE INTO courier_coverage_routes (route_id, courier_partner_id, origin_district_id, destination_district_id, is_active) VALUES
(1, 5, 6, 1, 1),
(2, 5, 1, 4, 1),
(3, 11, 7, 1, 1),
(4, 11, 5, 4, 1);

INSERT IGNORE INTO order_status_history (history_id, order_id, status, changed_by_user_id, note, created_at) VALUES
(1, 4, 'PAID', 7, 'Payment confirmed.', '2026-09-06 09:20:00'),
(2, 4, 'ACCEPTED', 10, 'Farmer accepted the order.', '2026-09-06 10:00:00'),
(3, 4, 'DELIVERED', 11, 'Courier confirmed doorstep delivery.', '2026-09-08 16:10:00'),
(4, 5, 'OUT_FOR_DELIVERY', 11, 'Courier started final-mile delivery.', '2026-09-10 14:30:00'),
(5, 6, 'ACCEPTED', 3, 'Farmer accepted the order.', '2026-09-11 12:30:00');

INSERT IGNORE INTO preorders (preorder_id, buyer_id, product_id, quantity, preorder_status) VALUES
(1, 7, 10, 2.000, 'WAITING_HARVEST'),
(2, 2, 9, 3.000, 'PAYMENT_INVITED');

INSERT IGNORE INTO carts (cart_id, buyer_id) VALUES
(1, 7), (2, 2);

INSERT IGNORE INTO cart_items (cart_item_id, cart_id, product_id, quantity) VALUES
(1, 1, 7, 2.000),
(2, 1, 8, 1.000),
(3, 2, 5, 4.000);
