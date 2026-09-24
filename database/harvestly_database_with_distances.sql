-- Harvestly: Farmer-to-Buyer Marketplace Platform
-- Database schema aligned with the final revised proposal.
-- Compatible with MySQL 8.x and MariaDB 10.4+ (XAMPP).
-- Database access in the PHP application: MySQLi with prepared statements.
-- Passwords must be created in PHP using password_hash(..., PASSWORD_BCRYPT)
-- and verified with password_verify(). Never store plain-text passwords.
--
-- Important delivery-data note:
-- This script seeds Sri Lanka's 25 districts and same-district distance = 0 km.
-- It intentionally does NOT invent inter-district road distances. Populate those
-- values later from your verified reference dataset before the delivery-fee demo.
--
-- Prototype fee note:
-- Farmer 2% + Buyer 2% are demonstration/configuration values only and can be
-- changed by Admin. Delivery base/per-km values are seeded as 0.00 placeholders.

CREATE DATABASE IF NOT EXISTS harvestly
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE harvestly;

SET NAMES utf8mb4;

-- =========================================================
-- 1. LOCATION MASTER DATA
-- =========================================================

CREATE TABLE IF NOT EXISTS districts (
    district_id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    district_name VARCHAR(60) NOT NULL,
    province_name VARCHAR(60) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_district_name (district_name),
    KEY idx_district_active (is_active)
) ENGINE=InnoDB;

INSERT IGNORE INTO districts (district_name, province_name, is_active) VALUES
('Colombo', 'Western Province', 1),
('Gampaha', 'Western Province', 1),
('Kalutara', 'Western Province', 1),
('Kandy', 'Central Province', 1),
('Matale', 'Central Province', 1),
('Nuwara Eliya', 'Central Province', 1),
('Galle', 'Southern Province', 1),
('Matara', 'Southern Province', 1),
('Hambantota', 'Southern Province', 1),
('Jaffna', 'Northern Province', 1),
('Kilinochchi', 'Northern Province', 1),
('Mannar', 'Northern Province', 1),
('Mullaitivu', 'Northern Province', 1),
('Vavuniya', 'Northern Province', 1),
('Trincomalee', 'Eastern Province', 1),
('Batticaloa', 'Eastern Province', 1),
('Ampara', 'Eastern Province', 1),
('Kurunegala', 'North Western Province', 1),
('Puttalam', 'North Western Province', 1),
('Anuradhapura', 'North Central Province', 1),
('Polonnaruwa', 'North Central Province', 1),
('Badulla', 'Uva Province', 1),
('Monaragala', 'Uva Province', 1),
('Ratnapura', 'Sabaragamuwa Province', 1),
('Kegalle', 'Sabaragamuwa Province', 1);

CREATE TABLE IF NOT EXISTS district_distances (
    distance_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    from_district_id SMALLINT UNSIGNED NOT NULL,
    to_district_id SMALLINT UNSIGNED NOT NULL,
    distance_km DECIMAL(8,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_distance_from_district
        FOREIGN KEY (from_district_id) REFERENCES districts(district_id),
    CONSTRAINT fk_distance_to_district
        FOREIGN KEY (to_district_id) REFERENCES districts(district_id),
    UNIQUE KEY uq_district_pair (from_district_id, to_district_id),
    KEY idx_distance_to (to_district_id)
) ENGINE=InnoDB;

-- Same-district deliveries require no inter-district reference distance.
INSERT IGNORE INTO district_distances (from_district_id, to_district_id, distance_km)
SELECT district_id, district_id, 0.00
FROM districts;

-- =========================================================
-- 2. USERS, AUTHENTICATION & PROFILES
-- =========================================================

CREATE TABLE IF NOT EXISTS users (
    user_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role ENUM('BUYER','FARMER','COURIER_PARTNER','ADMIN') NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(25) NULL,
    account_status ENUM('PENDING','ACTIVE','REJECTED','SUSPENDED') NOT NULL DEFAULT 'PENDING',
    last_login_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_role_status (role, account_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS buyer_profiles (
    buyer_id BIGINT UNSIGNED PRIMARY KEY,
    default_address_line1 VARCHAR(180) NULL,
    default_address_line2 VARCHAR(180) NULL,
    default_city_town VARCHAR(100) NULL,
    default_postal_code VARCHAR(20) NULL,
    default_district_id SMALLINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_buyer_user
        FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    CONSTRAINT fk_buyer_default_district
        FOREIGN KEY (default_district_id) REFERENCES districts(district_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS farmer_profiles (
    farmer_id BIGINT UNSIGNED PRIMARY KEY,
    farm_name VARCHAR(150) NULL,
    pickup_address_line1 VARCHAR(180) NOT NULL,
    pickup_address_line2 VARCHAR(180) NULL,
    pickup_city_town VARCHAR(100) NULL,
    pickup_postal_code VARCHAR(20) NULL,
    district_id SMALLINT UNSIGNED NOT NULL,
    verification_status ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_farmer_user
        FOREIGN KEY (farmer_id) REFERENCES users(user_id),
    CONSTRAINT fk_farmer_district
        FOREIGN KEY (district_id) REFERENCES districts(district_id),
    KEY idx_farmer_district (district_id),
    KEY idx_farmer_verification (verification_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS courier_partner_profiles (
    courier_partner_id BIGINT UNSIGNED PRIMARY KEY,
    organisation_name VARCHAR(160) NOT NULL,
    contact_person_name VARCHAR(120) NULL,
    office_address_line1 VARCHAR(180) NULL,
    office_address_line2 VARCHAR(180) NULL,
    office_city_town VARCHAR(100) NULL,
    office_postal_code VARCHAR(20) NULL,
    office_district_id SMALLINT UNSIGNED NULL,
    availability_status ENUM('AVAILABLE','UNAVAILABLE') NOT NULL DEFAULT 'UNAVAILABLE',
    verification_status ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_courier_user
        FOREIGN KEY (courier_partner_id) REFERENCES users(user_id),
    CONSTRAINT fk_courier_office_district
        FOREIGN KEY (office_district_id) REFERENCES districts(district_id),
    KEY idx_courier_availability (availability_status),
    KEY idx_courier_verification (verification_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    reset_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reset_user
        FOREIGN KEY (user_id) REFERENCES users(user_id),
    UNIQUE KEY uq_reset_token_hash (token_hash),
    KEY idx_reset_user_expiry (user_id, expires_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS verification_documents (
    document_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    document_type VARCHAR(80) NOT NULL,
    original_file_name VARCHAR(255) NULL,
    stored_file_path VARCHAR(500) NOT NULL,
    status ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
    rejection_reason VARCHAR(500) NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_verification_user
        FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_verification_reviewer
        FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    KEY idx_verification_user_status (user_id, status)
) ENGINE=InnoDB;

-- =========================================================
-- 3. COURIER COVERAGE
-- =========================================================

CREATE TABLE IF NOT EXISTS courier_coverage_routes (
    route_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    courier_partner_id BIGINT UNSIGNED NOT NULL,
    origin_district_id SMALLINT UNSIGNED NOT NULL,
    destination_district_id SMALLINT UNSIGNED NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_route_courier
        FOREIGN KEY (courier_partner_id) REFERENCES users(user_id),
    CONSTRAINT fk_route_origin
        FOREIGN KEY (origin_district_id) REFERENCES districts(district_id),
    CONSTRAINT fk_route_destination
        FOREIGN KEY (destination_district_id) REFERENCES districts(district_id),
    UNIQUE KEY uq_courier_route (courier_partner_id, origin_district_id, destination_district_id),
    KEY idx_route_lookup (origin_district_id, destination_district_id, is_active)
) ENGINE=InnoDB;

-- =========================================================
-- 4. PRODUCT REFERENCE DATA & LISTINGS
-- =========================================================

CREATE TABLE IF NOT EXISTS product_categories (
    category_id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(80) NOT NULL,
    description VARCHAR(500) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_category_name (category_name)
) ENGINE=InnoDB;

INSERT IGNORE INTO product_categories (category_name) VALUES
('Fruits'),
('Vegetables'),
('Herbs'),
('Plants'),
('Other Agricultural Produce');

CREATE TABLE IF NOT EXISTS quality_grade_references (
    grade_reference_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_reference_name VARCHAR(120) NOT NULL,
    grade ENUM('A','B','C') NOT NULL,
    criteria TEXT NOT NULL,
    source_reference VARCHAR(500) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_quality_reference (product_reference_name, grade),
    KEY idx_quality_active (is_active)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS shelf_life_references (
    shelf_life_reference_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_reference_name VARCHAR(120) NOT NULL,
    temperature_min_c DECIMAL(5,2) NULL,
    temperature_max_c DECIMAL(5,2) NULL,
    humidity_min_percent DECIMAL(5,2) NULL,
    humidity_max_percent DECIMAL(5,2) NULL,
    storage_life_min_days SMALLINT UNSIGNED NULL,
    storage_life_max_days SMALLINT UNSIGNED NULL,
    default_shelf_life_days SMALLINT UNSIGNED NULL,
    storage_guidance TEXT NULL,
    source_reference VARCHAR(500) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_shelf_life_product (product_reference_name),
    KEY idx_shelf_life_active (is_active)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    product_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    farmer_id BIGINT UNSIGNED NOT NULL,
    category_id SMALLINT UNSIGNED NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    available_quantity DECIMAL(12,3) NOT NULL DEFAULT 0.000,
    unit_label VARCHAR(30) NOT NULL DEFAULT 'kg',
    listing_type ENUM('AVAILABLE_NOW','HARVEST_SOON','SEASONAL') NOT NULL,
    growing_method ENUM('ORGANIC','CONVENTIONAL','MIXED') NULL,
    declared_grade ENUM('A','B','C') NULL,
    grade_reference_id BIGINT UNSIGNED NULL,
    shelf_life_reference_id BIGINT UNSIGNED NULL,
    freshness_tracking_applicable TINYINT(1) NOT NULL DEFAULT 1,
    harvest_date DATE NULL,
    available_from_date DATE NULL,
    season_start_date DATE NULL,
    season_end_date DATE NULL,
    best_before_date DATE NULL,
    storage_guidance TEXT NULL,
    listing_status ENUM('ACTIVE','INACTIVE','EXPIRED','SOLD_OUT') NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_farmer
        FOREIGN KEY (farmer_id) REFERENCES users(user_id),
    CONSTRAINT fk_product_category
        FOREIGN KEY (category_id) REFERENCES product_categories(category_id),
    CONSTRAINT fk_product_grade_reference
        FOREIGN KEY (grade_reference_id) REFERENCES quality_grade_references(grade_reference_id),
    CONSTRAINT fk_product_shelf_reference
        FOREIGN KEY (shelf_life_reference_id) REFERENCES shelf_life_references(shelf_life_reference_id),
    KEY idx_product_farmer_status (farmer_id, listing_status),
    KEY idx_product_category_status (category_id, listing_status),
    KEY idx_product_listing_type (listing_type),
    KEY idx_product_best_before (best_before_date),
    KEY idx_product_name (product_name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_images (
    image_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    is_grade_evidence TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_image_product
        FOREIGN KEY (product_id) REFERENCES products(product_id),
    KEY idx_product_images_product (product_id)
) ENGINE=InnoDB;

-- =========================================================
-- 5. CART & ORDERS
-- =========================================================

CREATE TABLE IF NOT EXISTS carts (
    cart_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buyer_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_buyer
        FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    UNIQUE KEY uq_buyer_cart (buyer_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart_items (
    cart_item_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_item_cart
        FOREIGN KEY (cart_id) REFERENCES carts(cart_id),
    CONSTRAINT fk_cart_item_product
        FOREIGN KEY (product_id) REFERENCES products(product_id),
    UNIQUE KEY uq_cart_product (cart_id, product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    order_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buyer_id BIGINT UNSIGNED NOT NULL,
    farmer_id BIGINT UNSIGNED NOT NULL,
    source_type ENUM('NORMAL','PREORDER') NOT NULL DEFAULT 'NORMAL',

    recipient_name VARCHAR(120) NOT NULL,
    recipient_phone VARCHAR(25) NOT NULL,
    delivery_address_line1 VARCHAR(180) NOT NULL,
    delivery_address_line2 VARCHAR(180) NULL,
    delivery_city_town VARCHAR(100) NULL,
    delivery_postal_code VARCHAR(20) NULL,
    destination_district_id SMALLINT UNSIGNED NOT NULL,

    product_subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    farmer_marketplace_fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    buyer_service_fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    delivery_fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    grand_total DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    order_status ENUM(
        'PENDING_PAYMENT','PAID','ACCEPTED','PREPARING','READY_FOR_DELIVERY',
        'PENDING_ASSIGNMENT','ASSIGNED','PICKED_UP','IN_TRANSIT','OUT_FOR_DELIVERY',
        'DELIVERED','COMPLETED','UNDELIVERABLE','REJECTED','CANCELLED'
    ) NOT NULL DEFAULT 'PENDING_PAYMENT',

    paid_at DATETIME NULL,
    accepted_at DATETIME NULL,
    ready_for_delivery_at DATETIME NULL,
    delivered_at DATETIME NULL,
    completed_at DATETIME NULL,
    review_deadline DATETIME NULL,
    cancelled_at DATETIME NULL,
    cancellation_reason VARCHAR(500) NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_buyer
        FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    CONSTRAINT fk_order_farmer
        FOREIGN KEY (farmer_id) REFERENCES users(user_id),
    CONSTRAINT fk_order_destination_district
        FOREIGN KEY (destination_district_id) REFERENCES districts(district_id),

    KEY idx_order_buyer (buyer_id, created_at),
    KEY idx_order_farmer_status (farmer_id, order_status),
    KEY idx_order_status (order_status),
    KEY idx_order_destination (destination_district_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    order_item_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_name_snapshot VARCHAR(150) NOT NULL,
    unit_price_snapshot DECIMAL(12,2) NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    line_total DECIMAL(12,2) NOT NULL,
    declared_grade_snapshot ENUM('A','B','C') NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_item_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_order_item_product
        FOREIGN KEY (product_id) REFERENCES products(product_id),
    KEY idx_order_items_order (order_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_status_history (
    history_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    status ENUM(
        'PENDING_PAYMENT','PAID','ACCEPTED','PREPARING','READY_FOR_DELIVERY',
        'PENDING_ASSIGNMENT','ASSIGNED','PICKED_UP','IN_TRANSIT','OUT_FOR_DELIVERY',
        'DELIVERED','COMPLETED','UNDELIVERABLE','REJECTED','CANCELLED'
    ) NOT NULL,
    changed_by_user_id BIGINT UNSIGNED NULL,
    note VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_history_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_order_history_user
        FOREIGN KEY (changed_by_user_id) REFERENCES users(user_id),
    KEY idx_order_history_order (order_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS preorders (
    preorder_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buyer_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    preorder_status ENUM('WAITING_HARVEST','PAYMENT_INVITED','CONVERTED_TO_ORDER','CANCELLED') NOT NULL DEFAULT 'WAITING_HARVEST',
    converted_order_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_preorder_buyer
        FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    CONSTRAINT fk_preorder_product
        FOREIGN KEY (product_id) REFERENCES products(product_id),
    CONSTRAINT fk_preorder_order
        FOREIGN KEY (converted_order_id) REFERENCES orders(order_id),
    KEY idx_preorder_product_status (product_id, preorder_status),
    KEY idx_preorder_buyer_status (buyer_id, preorder_status)
) ENGINE=InnoDB;

-- =========================================================
-- 6. PAYHERE SANDBOX PAYMENT
-- =========================================================

CREATE TABLE IF NOT EXISTS payments (
    payment_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    provider VARCHAR(40) NOT NULL DEFAULT 'PAYHERE_SANDBOX',
    provider_reference VARCHAR(190) NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_status ENUM('PENDING','SUCCESS','FAILED','CANCELLED') NOT NULL DEFAULT 'PENDING',
    paid_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    UNIQUE KEY uq_payment_provider_reference (provider_reference),
    KEY idx_payment_order_status (order_id, payment_status)
) ENGINE=InnoDB;

-- =========================================================
-- 7. EARNINGS & WEEKLY SETTLEMENT
-- =========================================================

CREATE TABLE IF NOT EXISTS earnings (
    earning_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    beneficiary_type ENUM('FARMER','COURIER_PARTNER','PLATFORM') NOT NULL,
    beneficiary_user_id BIGINT UNSIGNED NULL,
    earning_type ENUM(
        'FARMER_NET','COURIER_DELIVERY','FARMER_MARKETPLACE_FEE','BUYER_SERVICE_FEE'
    ) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    earning_status ENUM('HELD','PENDING_PAYOUT','EARNED','PAID','CANCELLED') NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    settled_at DATETIME NULL,
    CONSTRAINT fk_earning_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_earning_beneficiary
        FOREIGN KEY (beneficiary_user_id) REFERENCES users(user_id),
    KEY idx_earning_beneficiary_status (beneficiary_user_id, earning_status),
    KEY idx_earning_order (order_id),
    KEY idx_earning_status (earning_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS settlements (
    settlement_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    total_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    settlement_status ENUM('PROCESSING','COMPLETED','CANCELLED') NOT NULL DEFAULT 'PROCESSING',
    processed_by BIGINT UNSIGNED NULL,
    processed_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_settlement_admin
        FOREIGN KEY (processed_by) REFERENCES users(user_id),
    KEY idx_settlement_period (period_start, period_end)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS settlement_items (
    settlement_item_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    settlement_id BIGINT UNSIGNED NOT NULL,
    earning_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_settlement_item_settlement
        FOREIGN KEY (settlement_id) REFERENCES settlements(settlement_id),
    CONSTRAINT fk_settlement_item_earning
        FOREIGN KEY (earning_id) REFERENCES earnings(earning_id),
    UNIQUE KEY uq_settlement_earning (earning_id),
    KEY idx_settlement_items_settlement (settlement_id)
) ENGINE=InnoDB;

-- =========================================================
-- 8. AUTOMATIC COURIER ASSIGNMENT & DELIVERY
-- =========================================================

CREATE TABLE IF NOT EXISTS delivery_assignment_offers (
    offer_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    courier_partner_id BIGINT UNSIGNED NOT NULL,
    priority_rank INT UNSIGNED NOT NULL DEFAULT 1,
    offer_status ENUM('PENDING','ACCEPTED','REJECTED','EXPIRED','CANCELLED') NOT NULL DEFAULT 'PENDING',
    rejection_reason VARCHAR(500) NULL,
    offered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NULL,
    responded_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_offer_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_offer_courier
        FOREIGN KEY (courier_partner_id) REFERENCES users(user_id),
    KEY idx_offer_order_status (order_id, offer_status),
    KEY idx_offer_courier_status (courier_partner_id, offer_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS deliveries (
    delivery_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    courier_partner_id BIGINT UNSIGNED NULL,
    delivery_status ENUM(
        'PENDING_ASSIGNMENT','ASSIGNED','ACCEPTED','PICKED_UP','IN_TRANSIT',
        'OUT_FOR_DELIVERY','DELIVERED','COMPLETED','UNDELIVERABLE','CANCELLED'
    ) NOT NULL DEFAULT 'PENDING_ASSIGNMENT',
    assigned_at DATETIME NULL,
    accepted_at DATETIME NULL,
    picked_up_at DATETIME NULL,
    in_transit_at DATETIME NULL,
    out_for_delivery_at DATETIME NULL,
    delivered_at DATETIME NULL,
    buyer_confirmation_deadline DATETIME NULL,
    completed_at DATETIME NULL,
    active_attempt_count TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_delivery_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_delivery_courier
        FOREIGN KEY (courier_partner_id) REFERENCES users(user_id),
    UNIQUE KEY uq_delivery_order (order_id),
    KEY idx_delivery_courier_status (courier_partner_id, delivery_status),
    KEY idx_delivery_status (delivery_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS delivery_attempts (
    attempt_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    delivery_id BIGINT UNSIGNED NOT NULL,
    attempt_number TINYINT UNSIGNED NOT NULL,
    attempt_result ENUM('DELIVERED','BUYER_UNAVAILABLE','OTHER_ISSUE') NOT NULL,
    notes VARCHAR(500) NULL,
    attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_delivery_attempt_delivery
        FOREIGN KEY (delivery_id) REFERENCES deliveries(delivery_id),
    UNIQUE KEY uq_delivery_attempt_no (delivery_id, attempt_number),
    KEY idx_delivery_attempt_result (attempt_result)
) ENGINE=InnoDB;

-- =========================================================
-- 9. REVIEWS, COMPLAINTS & NOTIFICATIONS
-- =========================================================

CREATE TABLE IF NOT EXISTS reviews (
    review_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    buyer_id BIGINT UNSIGNED NOT NULL,
    farmer_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    review_text TEXT NULL,
    farmer_response TEXT NULL,
    farmer_responded_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_review_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_review_buyer
        FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    CONSTRAINT fk_review_farmer
        FOREIGN KEY (farmer_id) REFERENCES users(user_id),
    UNIQUE KEY uq_review_order (order_id),
    KEY idx_review_farmer (farmer_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS complaints (
    complaint_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    complainant_user_id BIGINT UNSIGNED NOT NULL,
    complainant_role ENUM('BUYER','FARMER','COURIER_PARTNER') NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    evidence_path VARCHAR(500) NULL,
    complaint_status ENUM('OPEN','UNDER_REVIEW','RESOLVED','REJECTED') NOT NULL DEFAULT 'OPEN',
    admin_response TEXT NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at DATETIME NULL,
    resolved_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_complaint_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_complaint_user
        FOREIGN KEY (complainant_user_id) REFERENCES users(user_id),
    CONSTRAINT fk_complaint_admin
        FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    KEY idx_complaint_order (order_id),
    KEY idx_complaint_status (complaint_status),
    KEY idx_complaint_user (complainant_user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
    notification_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    notification_type VARCHAR(60) NOT NULL,
    title VARCHAR(160) NOT NULL,
    message TEXT NOT NULL,
    related_order_id BIGINT UNSIGNED NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    read_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notification_user
        FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_notification_order
        FOREIGN KEY (related_order_id) REFERENCES orders(order_id),
    KEY idx_notification_user_read (user_id, is_read, created_at)
) ENGINE=InnoDB;

-- =========================================================
-- 10. CONFIGURABLE PLATFORM SETTINGS
-- =========================================================

CREATE TABLE IF NOT EXISTS platform_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL,
    value_type ENUM('INTEGER','DECIMAL','BOOLEAN','STRING') NOT NULL DEFAULT 'STRING',
    description VARCHAR(500) NULL,
    updated_by BIGINT UNSIGNED NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_setting_admin
        FOREIGN KEY (updated_by) REFERENCES users(user_id)
) ENGINE=InnoDB;

INSERT INTO platform_settings (setting_key, setting_value, value_type, description) VALUES
('farmer_marketplace_fee_percent', '2.00', 'DECIMAL', 'Prototype demonstration value; Admin configurable.'),
('buyer_service_fee_percent', '2.00', 'DECIMAL', 'Prototype demonstration value; Admin configurable.'),
('delivery_base_fee', '0.00', 'DECIMAL', 'Set an agreed LKR base delivery fee before demonstration.'),
('delivery_per_km_rate', '0.00', 'DECIMAL', 'Set an agreed LKR per-kilometre rate before demonstration.'),
('courier_assignment_response_minutes', '30', 'INTEGER', 'Configurable time allowed for a Courier Partner to accept or reject an offer.'),
('review_window_days', '14', 'INTEGER', 'Buyer review period after order completion.'),
('max_delivery_attempts', '2', 'INTEGER', 'Second Buyer-unavailable attempt leads to Undeliverable/Admin review.'),
('currency', 'LKR', 'STRING', 'Display currency used by the prototype.')
ON DUPLICATE KEY UPDATE
    setting_value = VALUES(setting_value),
    value_type = VALUES(value_type),
    description = VALUES(description);

-- =========================================================
-- END OF SCHEMA
-- =========================================================
-- Next data preparation tasks:
-- 1. Populate verified inter-district distances in district_distances.
-- 2. Add approved quality-grade references only where suitable criteria exist.
-- 3. Add shelf-life references only where suitable reference information exists.
-- 4. Create the first Admin account through PHP so the password is bcrypt-hashed.
-- 5. Configure delivery_base_fee and delivery_per_km_rate before testing checkout.


-- =========================================================
-- FULL UPDATED DISTRICT DISTANCE MATRIX (25 x 25)
-- =========================================================

-- Harvestly: Updated Island-wide District Distance Seed Data
-- Generated from the updated distance dataset previously prepared from the AgroLink reference structure.
-- IMPORTANT: Values are approximate road-distance reference values between district reference points/capital areas.
-- They are NOT GPS/address-to-address distances and should be treated as prototype reference data.
-- This script maps by district_name, so it is safe even if district_id ordering differs.

DROP TEMPORARY TABLE IF EXISTS tmp_district_distances;
CREATE TEMPORARY TABLE tmp_district_distances (
    from_district_name VARCHAR(60) NOT NULL,
    to_district_name VARCHAR(60) NOT NULL,
    distance_km DECIMAL(8,2) NOT NULL,
    PRIMARY KEY (from_district_name, to_district_name)
) ENGINE=InnoDB;

INSERT INTO tmp_district_distances (from_district_name, to_district_name, distance_km) VALUES
('Colombo','Colombo',0),
('Colombo','Gampaha',30),
('Colombo','Kalutara',44),
('Colombo','Kandy',115),
('Colombo','Matale',140),
('Colombo','Nuwara Eliya',170),
('Colombo','Galle',120),
('Colombo','Matara',160),
('Colombo','Hambantota',227),
('Colombo','Jaffna',391),
('Colombo','Kilinochchi',331),
('Colombo','Mannar',275),
('Colombo','Vavuniya',255),
('Colombo','Mullaitivu',330),
('Colombo','Batticaloa',297),
('Colombo','Ampara',304),
('Colombo','Trincomalee',265),
('Colombo','Kurunegala',95),
('Colombo','Puttalam',133),
('Colombo','Anuradhapura',205),
('Colombo','Polonnaruwa',215),
('Colombo','Badulla',227),
('Colombo','Monaragala',250),
('Colombo','Ratnapura',101),
('Colombo','Kegalle',80),
('Gampaha','Colombo',30),
('Gampaha','Gampaha',0),
('Gampaha','Kalutara',66),
('Gampaha','Kandy',112),
('Gampaha','Matale',122),
('Gampaha','Nuwara Eliya',187),
('Gampaha','Galle',144),
('Gampaha','Matara',190),
('Gampaha','Hambantota',257),
('Gampaha','Jaffna',375),
('Gampaha','Kilinochchi',315),
('Gampaha','Mannar',305),
('Gampaha','Vavuniya',239),
('Gampaha','Mullaitivu',314),
('Gampaha','Batticaloa',294),
('Gampaha','Ampara',334),
('Gampaha','Trincomalee',252),
('Gampaha','Kurunegala',71),
('Gampaha','Puttalam',121),
('Gampaha','Anuradhapura',181),
('Gampaha','Polonnaruwa',227),
('Gampaha','Badulla',224),
('Gampaha','Monaragala',280),
('Gampaha','Ratnapura',131),
('Gampaha','Kegalle',53),
('Kalutara','Colombo',44),
('Kalutara','Gampaha',66),
('Kalutara','Kalutara',0),
('Kalutara','Kandy',159),
('Kalutara','Matale',184),
('Kalutara','Nuwara Eliya',212),
('Kalutara','Galle',78),
('Kalutara','Matara',124),
('Kalutara','Hambantota',203),
('Kalutara','Jaffna',435),
('Kalutara','Kilinochchi',375),
('Kalutara','Mannar',319),
('Kalutara','Vavuniya',299),
('Kalutara','Mullaitivu',374),
('Kalutara','Batticaloa',341),
('Kalutara','Ampara',313),
('Kalutara','Trincomalee',309),
('Kalutara','Kurunegala',137),
('Kalutara','Puttalam',177),
('Kalutara','Anuradhapura',247),
('Kalutara','Polonnaruwa',259),
('Kalutara','Badulla',217),
('Kalutara','Monaragala',229),
('Kalutara','Ratnapura',77),
('Kalutara','Kegalle',119),
('Kandy','Colombo',115),
('Kandy','Gampaha',112),
('Kandy','Kalutara',159),
('Kandy','Kandy',0),
('Kandy','Matale',25),
('Kandy','Nuwara Eliya',75),
('Kandy','Galle',235),
('Kandy','Matara',275),
('Kandy','Hambantota',267),
('Kandy','Jaffna',328),
('Kandy','Kilinochchi',268),
('Kandy','Mannar',270),
('Kandy','Vavuniya',192),
('Kandy','Mullaitivu',267),
('Kandy','Batticaloa',182),
('Kandy','Ampara',249),
('Kandy','Trincomalee',155),
('Kandy','Kurunegala',42),
('Kandy','Puttalam',129),
('Kandy','Anuradhapura',134),
('Kandy','Polonnaruwa',130),
('Kandy','Badulla',112),
('Kandy','Monaragala',168),
('Kandy','Ratnapura',141),
('Kandy','Kegalle',59),
('Matale','Colombo',140),
('Matale','Gampaha',122),
('Matale','Kalutara',184),
('Matale','Kandy',25),
('Matale','Matale',0),
('Matale','Nuwara Eliya',100),
('Matale','Galle',260),
('Matale','Matara',300),
('Matale','Hambantota',290),
('Matale','Jaffna',303),
('Matale','Kilinochchi',243),
('Matale','Mannar',245),
('Matale','Vavuniya',167),
('Matale','Mullaitivu',242),
('Matale','Batticaloa',201),
('Matale','Ampara',239),
('Matale','Trincomalee',130),
('Matale','Kurunegala',51),
('Matale','Puttalam',138),
('Matale','Anuradhapura',109),
('Matale','Polonnaruwa',105),
('Matale','Badulla',137),
('Matale','Monaragala',193),
('Matale','Ratnapura',164),
('Matale','Kegalle',82),
('Nuwara Eliya','Colombo',170),
('Nuwara Eliya','Gampaha',187),
('Nuwara Eliya','Kalutara',212),
('Nuwara Eliya','Kandy',75),
('Nuwara Eliya','Matale',100),
('Nuwara Eliya','Nuwara Eliya',0),
('Nuwara Eliya','Galle',269),
('Nuwara Eliya','Matara',278),
('Nuwara Eliya','Hambantota',224),
('Nuwara Eliya','Jaffna',403),
('Nuwara Eliya','Kilinochchi',343),
('Nuwara Eliya','Mannar',345),
('Nuwara Eliya','Vavuniya',267),
('Nuwara Eliya','Mullaitivu',342),
('Nuwara Eliya','Batticaloa',219),
('Nuwara Eliya','Ampara',196),
('Nuwara Eliya','Trincomalee',230),
('Nuwara Eliya','Kurunegala',117),
('Nuwara Eliya','Puttalam',204),
('Nuwara Eliya','Anuradhapura',209),
('Nuwara Eliya','Polonnaruwa',205),
('Nuwara Eliya','Badulla',59),
('Nuwara Eliya','Monaragala',115),
('Nuwara Eliya','Ratnapura',135),
('Nuwara Eliya','Kegalle',134),
('Galle','Colombo',120),
('Galle','Gampaha',144),
('Galle','Kalutara',78),
('Galle','Kandy',235),
('Galle','Matale',260),
('Galle','Nuwara Eliya',269),
('Galle','Galle',0),
('Galle','Matara',46),
('Galle','Hambantota',132),
('Galle','Jaffna',511),
('Galle','Kilinochchi',451),
('Galle','Mannar',395),
('Galle','Vavuniya',375),
('Galle','Mullaitivu',450),
('Galle','Batticaloa',395),
('Galle','Ampara',325),
('Galle','Trincomalee',385),
('Galle','Kurunegala',215),
('Galle','Puttalam',253),
('Galle','Anuradhapura',325),
('Galle','Polonnaruwa',335),
('Galle','Badulla',274),
('Galle','Monaragala',241),
('Galle','Ratnapura',134),
('Galle','Kegalle',197),
('Matara','Colombo',160),
('Matara','Gampaha',190),
('Matara','Kalutara',124),
('Matara','Kandy',275),
('Matara','Matale',300),
('Matara','Nuwara Eliya',278),
('Matara','Galle',46),
('Matara','Matara',0),
('Matara','Hambantota',86),
('Matara','Jaffna',551),
('Matara','Kilinochchi',491),
('Matara','Mannar',435),
('Matara','Vavuniya',415),
('Matara','Mullaitivu',490),
('Matara','Batticaloa',349),
('Matara','Ampara',279),
('Matara','Trincomalee',425),
('Matara','Kurunegala',255),
('Matara','Puttalam',293),
('Matara','Anuradhapura',365),
('Matara','Polonnaruwa',375),
('Matara','Badulla',251),
('Matara','Monaragala',195),
('Matara','Ratnapura',143),
('Matara','Kegalle',225),
('Hambantota','Colombo',227),
('Hambantota','Gampaha',257),
('Hambantota','Kalutara',203),
('Hambantota','Kandy',267),
('Hambantota','Matale',290),
('Hambantota','Nuwara Eliya',224),
('Hambantota','Galle',132),
('Hambantota','Matara',86),
('Hambantota','Hambantota',0),
('Hambantota','Jaffna',543),
('Hambantota','Kilinochchi',483),
('Hambantota','Mannar',485),
('Hambantota','Vavuniya',407),
('Hambantota','Mullaitivu',482),
('Hambantota','Batticaloa',263),
('Hambantota','Ampara',193),
('Hambantota','Trincomalee',400),
('Hambantota','Kurunegala',239),
('Hambantota','Puttalam',326),
('Hambantota','Anuradhapura',349),
('Hambantota','Polonnaruwa',311),
('Hambantota','Badulla',165),
('Hambantota','Monaragala',109),
('Hambantota','Ratnapura',126),
('Hambantota','Kegalle',208),
('Jaffna','Colombo',391),
('Jaffna','Gampaha',375),
('Jaffna','Kalutara',435),
('Jaffna','Kandy',328),
('Jaffna','Matale',303),
('Jaffna','Nuwara Eliya',403),
('Jaffna','Galle',511),
('Jaffna','Matara',551),
('Jaffna','Hambantota',543),
('Jaffna','Jaffna',0),
('Jaffna','Kilinochchi',65),
('Jaffna','Mannar',117),
('Jaffna','Vavuniya',136),
('Jaffna','Mullaitivu',124),
('Jaffna','Batticaloa',370),
('Jaffna','Ampara',428),
('Jaffna','Trincomalee',233),
('Jaffna','Kurunegala',304),
('Jaffna','Puttalam',271),
('Jaffna','Anuradhapura',194),
('Jaffna','Polonnaruwa',294),
('Jaffna','Badulla',440),
('Jaffna','Monaragala',496),
('Jaffna','Ratnapura',417),
('Jaffna','Kegalle',335),
('Kilinochchi','Colombo',331),
('Kilinochchi','Gampaha',315),
('Kilinochchi','Kalutara',375),
('Kilinochchi','Kandy',268),
('Kilinochchi','Matale',243),
('Kilinochchi','Nuwara Eliya',343),
('Kilinochchi','Galle',451),
('Kilinochchi','Matara',491),
('Kilinochchi','Hambantota',483),
('Kilinochchi','Jaffna',65),
('Kilinochchi','Kilinochchi',0),
('Kilinochchi','Mannar',106),
('Kilinochchi','Vavuniya',76),
('Kilinochchi','Mullaitivu',59),
('Kilinochchi','Batticaloa',310),
('Kilinochchi','Ampara',368),
('Kilinochchi','Trincomalee',173),
('Kilinochchi','Kurunegala',244),
('Kilinochchi','Puttalam',211),
('Kilinochchi','Anuradhapura',134),
('Kilinochchi','Polonnaruwa',234),
('Kilinochchi','Badulla',380),
('Kilinochchi','Monaragala',436),
('Kilinochchi','Ratnapura',357),
('Kilinochchi','Kegalle',275),
('Mannar','Colombo',275),
('Mannar','Gampaha',305),
('Mannar','Kalutara',319),
('Mannar','Kandy',270),
('Mannar','Matale',245),
('Mannar','Nuwara Eliya',345),
('Mannar','Galle',395),
('Mannar','Matara',435),
('Mannar','Hambantota',485),
('Mannar','Jaffna',117),
('Mannar','Kilinochchi',106),
('Mannar','Mannar',0),
('Mannar','Vavuniya',78),
('Mannar','Mullaitivu',153),
('Mannar','Batticaloa',312),
('Mannar','Ampara',370),
('Mannar','Trincomalee',175),
('Mannar','Kurunegala',246),
('Mannar','Puttalam',213),
('Mannar','Anuradhapura',136),
('Mannar','Polonnaruwa',236),
('Mannar','Badulla',382),
('Mannar','Monaragala',438),
('Mannar','Ratnapura',359),
('Mannar','Kegalle',277),
('Vavuniya','Colombo',255),
('Vavuniya','Gampaha',239),
('Vavuniya','Kalutara',299),
('Vavuniya','Kandy',192),
('Vavuniya','Matale',167),
('Vavuniya','Nuwara Eliya',267),
('Vavuniya','Galle',375),
('Vavuniya','Matara',415),
('Vavuniya','Hambantota',407),
('Vavuniya','Jaffna',136),
('Vavuniya','Kilinochchi',76),
('Vavuniya','Mannar',78),
('Vavuniya','Vavuniya',0),
('Vavuniya','Mullaitivu',75),
('Vavuniya','Batticaloa',234),
('Vavuniya','Ampara',292),
('Vavuniya','Trincomalee',97),
('Vavuniya','Kurunegala',168),
('Vavuniya','Puttalam',135),
('Vavuniya','Anuradhapura',58),
('Vavuniya','Polonnaruwa',158),
('Vavuniya','Badulla',304),
('Vavuniya','Monaragala',360),
('Vavuniya','Ratnapura',281),
('Vavuniya','Kegalle',199),
('Mullaitivu','Colombo',330),
('Mullaitivu','Gampaha',314),
('Mullaitivu','Kalutara',374),
('Mullaitivu','Kandy',267),
('Mullaitivu','Matale',242),
('Mullaitivu','Nuwara Eliya',342),
('Mullaitivu','Galle',450),
('Mullaitivu','Matara',490),
('Mullaitivu','Hambantota',482),
('Mullaitivu','Jaffna',124),
('Mullaitivu','Kilinochchi',59),
('Mullaitivu','Mannar',153),
('Mullaitivu','Vavuniya',75),
('Mullaitivu','Mullaitivu',0),
('Mullaitivu','Batticaloa',257),
('Mullaitivu','Ampara',327),
('Mullaitivu','Trincomalee',120),
('Mullaitivu','Kurunegala',243),
('Mullaitivu','Puttalam',210),
('Mullaitivu','Anuradhapura',133),
('Mullaitivu','Polonnaruwa',218),
('Mullaitivu','Badulla',364),
('Mullaitivu','Monaragala',411),
('Mullaitivu','Ratnapura',356),
('Mullaitivu','Kegalle',274),
('Batticaloa','Colombo',297),
('Batticaloa','Gampaha',294),
('Batticaloa','Kalutara',341),
('Batticaloa','Kandy',182),
('Batticaloa','Matale',201),
('Batticaloa','Nuwara Eliya',219),
('Batticaloa','Galle',395),
('Batticaloa','Matara',349),
('Batticaloa','Hambantota',263),
('Batticaloa','Jaffna',370),
('Batticaloa','Kilinochchi',310),
('Batticaloa','Mannar',312),
('Batticaloa','Vavuniya',234),
('Batticaloa','Mullaitivu',257),
('Batticaloa','Batticaloa',0),
('Batticaloa','Ampara',70),
('Batticaloa','Trincomalee',137),
('Batticaloa','Kurunegala',224),
('Batticaloa','Puttalam',273),
('Batticaloa','Anuradhapura',196),
('Batticaloa','Polonnaruwa',96),
('Batticaloa','Badulla',160),
('Batticaloa','Monaragala',154),
('Batticaloa','Ratnapura',300),
('Batticaloa','Kegalle',241),
('Ampara','Colombo',304),
('Ampara','Gampaha',334),
('Ampara','Kalutara',313),
('Ampara','Kandy',249),
('Ampara','Matale',239),
('Ampara','Nuwara Eliya',196),
('Ampara','Galle',325),
('Ampara','Matara',279),
('Ampara','Hambantota',193),
('Ampara','Jaffna',428),
('Ampara','Kilinochchi',368),
('Ampara','Mannar',370),
('Ampara','Vavuniya',292),
('Ampara','Mullaitivu',327),
('Ampara','Batticaloa',70),
('Ampara','Ampara',0),
('Ampara','Trincomalee',207),
('Ampara','Kurunegala',290),
('Ampara','Puttalam',311),
('Ampara','Anuradhapura',234),
('Ampara','Polonnaruwa',134),
('Ampara','Badulla',137),
('Ampara','Monaragala',84),
('Ampara','Ratnapura',236),
('Ampara','Kegalle',308),
('Trincomalee','Colombo',265),
('Trincomalee','Gampaha',252),
('Trincomalee','Kalutara',309),
('Trincomalee','Kandy',155),
('Trincomalee','Matale',130),
('Trincomalee','Nuwara Eliya',230),
('Trincomalee','Galle',385),
('Trincomalee','Matara',425),
('Trincomalee','Hambantota',400),
('Trincomalee','Jaffna',233),
('Trincomalee','Kilinochchi',173),
('Trincomalee','Mannar',175),
('Trincomalee','Vavuniya',97),
('Trincomalee','Mullaitivu',120),
('Trincomalee','Batticaloa',137),
('Trincomalee','Ampara',207),
('Trincomalee','Trincomalee',0),
('Trincomalee','Kurunegala',181),
('Trincomalee','Puttalam',186),
('Trincomalee','Anuradhapura',109),
('Trincomalee','Polonnaruwa',98),
('Trincomalee','Badulla',244),
('Trincomalee','Monaragala',291),
('Trincomalee','Ratnapura',294),
('Trincomalee','Kegalle',212),
('Kurunegala','Colombo',95),
('Kurunegala','Gampaha',71),
('Kurunegala','Kalutara',137),
('Kurunegala','Kandy',42),
('Kurunegala','Matale',51),
('Kurunegala','Nuwara Eliya',117),
('Kurunegala','Galle',215),
('Kurunegala','Matara',255),
('Kurunegala','Hambantota',239),
('Kurunegala','Jaffna',304),
('Kurunegala','Kilinochchi',244),
('Kurunegala','Mannar',246),
('Kurunegala','Vavuniya',168),
('Kurunegala','Mullaitivu',243),
('Kurunegala','Batticaloa',224),
('Kurunegala','Ampara',290),
('Kurunegala','Trincomalee',181),
('Kurunegala','Kurunegala',0),
('Kurunegala','Puttalam',87),
('Kurunegala','Anuradhapura',110),
('Kurunegala','Polonnaruwa',156),
('Kurunegala','Badulla',154),
('Kurunegala','Monaragala',210),
('Kurunegala','Ratnapura',113),
('Kurunegala','Kegalle',31),
('Puttalam','Colombo',133),
('Puttalam','Gampaha',121),
('Puttalam','Kalutara',177),
('Puttalam','Kandy',129),
('Puttalam','Matale',138),
('Puttalam','Nuwara Eliya',204),
('Puttalam','Galle',253),
('Puttalam','Matara',293),
('Puttalam','Hambantota',326),
('Puttalam','Jaffna',271),
('Puttalam','Kilinochchi',211),
('Puttalam','Mannar',213),
('Puttalam','Vavuniya',135),
('Puttalam','Mullaitivu',210),
('Puttalam','Batticaloa',273),
('Puttalam','Ampara',311),
('Puttalam','Trincomalee',186),
('Puttalam','Kurunegala',87),
('Puttalam','Puttalam',0),
('Puttalam','Anuradhapura',77),
('Puttalam','Polonnaruwa',177),
('Puttalam','Badulla',241),
('Puttalam','Monaragala',297),
('Puttalam','Ratnapura',200),
('Puttalam','Kegalle',118),
('Anuradhapura','Colombo',205),
('Anuradhapura','Gampaha',181),
('Anuradhapura','Kalutara',247),
('Anuradhapura','Kandy',134),
('Anuradhapura','Matale',109),
('Anuradhapura','Nuwara Eliya',209),
('Anuradhapura','Galle',325),
('Anuradhapura','Matara',365),
('Anuradhapura','Hambantota',349),
('Anuradhapura','Jaffna',194),
('Anuradhapura','Kilinochchi',134),
('Anuradhapura','Mannar',136),
('Anuradhapura','Vavuniya',58),
('Anuradhapura','Mullaitivu',133),
('Anuradhapura','Batticaloa',196),
('Anuradhapura','Ampara',234),
('Anuradhapura','Trincomalee',109),
('Anuradhapura','Kurunegala',110),
('Anuradhapura','Puttalam',77),
('Anuradhapura','Anuradhapura',0),
('Anuradhapura','Polonnaruwa',100),
('Anuradhapura','Badulla',246),
('Anuradhapura','Monaragala',302),
('Anuradhapura','Ratnapura',223),
('Anuradhapura','Kegalle',141),
('Polonnaruwa','Colombo',215),
('Polonnaruwa','Gampaha',227),
('Polonnaruwa','Kalutara',259),
('Polonnaruwa','Kandy',130),
('Polonnaruwa','Matale',105),
('Polonnaruwa','Nuwara Eliya',205),
('Polonnaruwa','Galle',335),
('Polonnaruwa','Matara',375),
('Polonnaruwa','Hambantota',311),
('Polonnaruwa','Jaffna',294),
('Polonnaruwa','Kilinochchi',234),
('Polonnaruwa','Mannar',236),
('Polonnaruwa','Vavuniya',158),
('Polonnaruwa','Mullaitivu',218),
('Polonnaruwa','Batticaloa',96),
('Polonnaruwa','Ampara',134),
('Polonnaruwa','Trincomalee',98),
('Polonnaruwa','Kurunegala',156),
('Polonnaruwa','Puttalam',177),
('Polonnaruwa','Anuradhapura',100),
('Polonnaruwa','Polonnaruwa',0),
('Polonnaruwa','Badulla',146),
('Polonnaruwa','Monaragala',202),
('Polonnaruwa','Ratnapura',269),
('Polonnaruwa','Kegalle',187),
('Badulla','Colombo',227),
('Badulla','Gampaha',224),
('Badulla','Kalutara',217),
('Badulla','Kandy',112),
('Badulla','Matale',137),
('Badulla','Nuwara Eliya',59),
('Badulla','Galle',274),
('Badulla','Matara',251),
('Badulla','Hambantota',165),
('Badulla','Jaffna',440),
('Badulla','Kilinochchi',380),
('Badulla','Mannar',382),
('Badulla','Vavuniya',304),
('Badulla','Mullaitivu',364),
('Badulla','Batticaloa',160),
('Badulla','Ampara',137),
('Badulla','Trincomalee',244),
('Badulla','Kurunegala',154),
('Badulla','Puttalam',241),
('Badulla','Anuradhapura',246),
('Badulla','Polonnaruwa',146),
('Badulla','Badulla',0),
('Badulla','Monaragala',56),
('Badulla','Ratnapura',140),
('Badulla','Kegalle',171),
('Monaragala','Colombo',250),
('Monaragala','Gampaha',280),
('Monaragala','Kalutara',229),
('Monaragala','Kandy',168),
('Monaragala','Matale',193),
('Monaragala','Nuwara Eliya',115),
('Monaragala','Galle',241),
('Monaragala','Matara',195),
('Monaragala','Hambantota',109),
('Monaragala','Jaffna',496),
('Monaragala','Kilinochchi',436),
('Monaragala','Mannar',438),
('Monaragala','Vavuniya',360),
('Monaragala','Mullaitivu',411),
('Monaragala','Batticaloa',154),
('Monaragala','Ampara',84),
('Monaragala','Trincomalee',291),
('Monaragala','Kurunegala',210),
('Monaragala','Puttalam',297),
('Monaragala','Anuradhapura',302),
('Monaragala','Polonnaruwa',202),
('Monaragala','Badulla',56),
('Monaragala','Monaragala',0),
('Monaragala','Ratnapura',152),
('Monaragala','Kegalle',227),
('Ratnapura','Colombo',101),
('Ratnapura','Gampaha',131),
('Ratnapura','Kalutara',77),
('Ratnapura','Kandy',141),
('Ratnapura','Matale',164),
('Ratnapura','Nuwara Eliya',135),
('Ratnapura','Galle',134),
('Ratnapura','Matara',143),
('Ratnapura','Hambantota',126),
('Ratnapura','Jaffna',417),
('Ratnapura','Kilinochchi',357),
('Ratnapura','Mannar',359),
('Ratnapura','Vavuniya',281),
('Ratnapura','Mullaitivu',356),
('Ratnapura','Batticaloa',300),
('Ratnapura','Ampara',236),
('Ratnapura','Trincomalee',294),
('Ratnapura','Kurunegala',113),
('Ratnapura','Puttalam',200),
('Ratnapura','Anuradhapura',223),
('Ratnapura','Polonnaruwa',269),
('Ratnapura','Badulla',140),
('Ratnapura','Monaragala',152),
('Ratnapura','Ratnapura',0),
('Ratnapura','Kegalle',82),
('Kegalle','Colombo',80),
('Kegalle','Gampaha',53),
('Kegalle','Kalutara',119),
('Kegalle','Kandy',59),
('Kegalle','Matale',82),
('Kegalle','Nuwara Eliya',134),
('Kegalle','Galle',197),
('Kegalle','Matara',225),
('Kegalle','Hambantota',208),
('Kegalle','Jaffna',335),
('Kegalle','Kilinochchi',275),
('Kegalle','Mannar',277),
('Kegalle','Vavuniya',199),
('Kegalle','Mullaitivu',274),
('Kegalle','Batticaloa',241),
('Kegalle','Ampara',308),
('Kegalle','Trincomalee',212),
('Kegalle','Kurunegala',31),
('Kegalle','Puttalam',118),
('Kegalle','Anuradhapura',141),
('Kegalle','Polonnaruwa',187),
('Kegalle','Badulla',171),
('Kegalle','Monaragala',227),
('Kegalle','Ratnapura',82),
('Kegalle','Kegalle',0);

-- Replace existing distance rows with the complete 25 x 25 reference matrix.
DELETE FROM district_distances;

INSERT INTO district_distances (from_district_id, to_district_id, distance_km)
SELECT
    fd.district_id,
    td.district_id,
    t.distance_km
FROM tmp_district_distances t
JOIN districts fd ON fd.district_name = t.from_district_name
JOIN districts td ON td.district_name = t.to_district_name;

DROP TEMPORARY TABLE tmp_district_distances;

-- Expected result: 625 rows (25 x 25, including same-district 0 km rows).
SELECT COUNT(*) AS district_distance_rows FROM district_distances;
