<?php
/**
 * Farmer Product Model
 * Handles database operations for Farmer Product Management & Scientific Shelf-Life References using MySQLi
 */

require_once __DIR__ . '/../../config/database.php';

class FarmerProductModel {
    private $db;

    public function __construct() {
        $this->db = getDBConnection();
    }

    /**
     * Get all active scientific shelf-life reference records (25 Rajapaksha et al. 2021 records)
     */
    public function getActiveShelfLifeReferences() {
        $result = $this->db->query("
            SELECT shelf_life_reference_id, product_reference_name, 
                   temperature_min_c, temperature_max_c,
                   humidity_min_percent, humidity_max_percent,
                   storage_life_min_days, storage_life_max_days,
                   default_shelf_life_days, storage_guidance, source_reference
            FROM shelf_life_references
            WHERE is_active = 1
            ORDER BY product_reference_name ASC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Get single shelf-life reference by ID
     */
    public function getShelfLifeReferenceById($referenceId) {
        if (empty($referenceId)) {
            return null;
        }
        $stmt = $this->db->prepare("
            SELECT shelf_life_reference_id, product_reference_name, 
                   temperature_min_c, temperature_max_c,
                   humidity_min_percent, humidity_max_percent,
                   storage_life_min_days, storage_life_max_days,
                   default_shelf_life_days, storage_guidance, source_reference
            FROM shelf_life_references
            WHERE shelf_life_reference_id = ? AND is_active = 1
        ");
        if (!$stmt) return null;
        $stmt->bind_param("i", $referenceId);
        $stmt->execute();
        $res = $stmt->get_result();
        $ref = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $ref;
    }

    /**
     * Get active product categories
     */
    public function getCategories() {
        $result = $this->db->query("
            SELECT category_id, category_name
            FROM product_categories
            WHERE is_active = 1
            ORDER BY category_name ASC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Get all products for a given farmer
     */
    public function getFarmerProducts($farmerId) {
        $stmt = $this->db->prepare("
            SELECT p.product_id, p.farmer_id, p.category_id, p.product_name, p.description,
                   p.unit_price, p.available_quantity, p.unit_label, p.listing_type,
                   p.growing_method, p.declared_grade, p.shelf_life_reference_id,
                   p.harvest_date, p.best_before_date, p.storage_guidance, p.listing_status,
                   p.created_at, p.updated_at,
                   c.category_name,
                   r.product_reference_name, r.temperature_min_c, r.temperature_max_c,
                   r.humidity_min_percent, r.humidity_max_percent,
                   r.storage_life_min_days, r.storage_life_max_days,
                   r.storage_guidance AS reference_storage_guidance, r.source_reference
            FROM products p
            JOIN product_categories c ON p.category_id = c.category_id
            LEFT JOIN shelf_life_references r ON p.shelf_life_reference_id = r.shelf_life_reference_id
            WHERE p.farmer_id = ?
            ORDER BY p.created_at DESC
        ");
        if (!$stmt) return [];
        $stmt->bind_param("i", $farmerId);
        $stmt->execute();
        $res = $stmt->get_result();
        $products = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $products;
    }

    /**
     * Get single product by ID (optionally scoped to farmerId)
     */
    public function getProductById($productId, $farmerId = null) {
        if ($farmerId !== null) {
            $stmt = $this->db->prepare("
                SELECT p.product_id, p.farmer_id, p.category_id, p.product_name, p.description,
                       p.unit_price, p.available_quantity, p.unit_label, p.listing_type,
                       p.growing_method, p.declared_grade, p.shelf_life_reference_id,
                       p.harvest_date, p.best_before_date, p.storage_guidance, p.listing_status,
                       p.created_at, p.updated_at,
                       c.category_name,
                       r.product_reference_name, r.temperature_min_c, r.temperature_max_c,
                       r.humidity_min_percent, r.humidity_max_percent,
                       r.storage_life_min_days, r.storage_life_max_days,
                       r.storage_guidance AS reference_storage_guidance, r.source_reference
                FROM products p
                JOIN product_categories c ON p.category_id = c.category_id
                LEFT JOIN shelf_life_references r ON p.shelf_life_reference_id = r.shelf_life_reference_id
                WHERE p.product_id = ? AND p.farmer_id = ?
            ");
            if (!$stmt) return null;
            $stmt->bind_param("ii", $productId, $farmerId);
        } else {
            $stmt = $this->db->prepare("
                SELECT p.product_id, p.farmer_id, p.category_id, p.product_name, p.description,
                       p.unit_price, p.available_quantity, p.unit_label, p.listing_type,
                       p.growing_method, p.declared_grade, p.shelf_life_reference_id,
                       p.harvest_date, p.best_before_date, p.storage_guidance, p.listing_status,
                       p.created_at, p.updated_at,
                       c.category_name,
                       r.product_reference_name, r.temperature_min_c, r.temperature_max_c,
                       r.humidity_min_percent, r.humidity_max_percent,
                       r.storage_life_min_days, r.storage_life_max_days,
                       r.storage_guidance AS reference_storage_guidance, r.source_reference
                FROM products p
                JOIN product_categories c ON p.category_id = c.category_id
                LEFT JOIN shelf_life_references r ON p.shelf_life_reference_id = r.shelf_life_reference_id
                WHERE p.product_id = ?
            ");
            if (!$stmt) return null;
            $stmt->bind_param("i", $productId);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $product;
    }

    /**
     * Create product with shelf_life_reference_id
     */
    public function createProduct($data) {
        $stmt = $this->db->prepare("
            INSERT INTO products (
                farmer_id, category_id, product_name, description, unit_price,
                available_quantity, unit_label, listing_type, growing_method,
                declared_grade, shelf_life_reference_id, harvest_date,
                best_before_date, storage_guidance, listing_status
            ) VALUES (
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?
            )
        ");
        if (!$stmt) return false;

        $farmerId        = (int)$data['farmer_id'];
        $categoryId      = (int)$data['category_id'];
        $productName     = $data['product_name'];
        $description     = $data['description'] ?? null;
        $unitPrice       = (float)$data['unit_price'];
        $availQty        = (float)($data['available_quantity'] ?? 0.0);
        $unitLabel       = $data['unit_label'] ?? 'kg';
        $listingType     = $data['listing_type'] ?? 'AVAILABLE_NOW';
        $growingMethod   = $data['growing_method'] ?? 'CONVENTIONAL';
        $declaredGrade   = $data['declared_grade'] ?? 'A';
        $shelfLifeRefId  = !empty($data['shelf_life_reference_id']) ? (int)$data['shelf_life_reference_id'] : null;
        $harvestDate     = !empty($data['harvest_date']) ? $data['harvest_date'] : null;
        $bestBeforeDate  = !empty($data['best_before_date']) ? $data['best_before_date'] : null;
        $storageGuidance = $data['storage_guidance'] ?? null;
        $listingStatus   = $data['listing_status'] ?? 'ACTIVE';

        $stmt->bind_param(
            "iissddssssissss",
            $farmerId, $categoryId, $productName, $description, $unitPrice,
            $availQty, $unitLabel, $listingType, $growingMethod,
            $declaredGrade, $shelfLifeRefId, $harvestDate,
            $bestBeforeDate, $storageGuidance, $listingStatus
        );

        $stmt->execute();
        $insertId = $this->db->insert_id;
        $stmt->close();
        return $insertId;
    }

    /**
     * Update product record
     */
    public function updateProduct($productId, $farmerId, $data) {
        $stmt = $this->db->prepare("
            UPDATE products SET
                category_id             = ?,
                product_name            = ?,
                description             = ?,
                unit_price              = ?,
                available_quantity      = ?,
                unit_label              = ?,
                listing_type            = ?,
                growing_method          = ?,
                declared_grade          = ?,
                shelf_life_reference_id = ?,
                harvest_date            = ?,
                best_before_date        = ?,
                storage_guidance        = ?,
                listing_status          = ?
            WHERE product_id = ? AND farmer_id = ?
        ");
        if (!$stmt) return false;

        $pId             = (int)$productId;
        $fId             = (int)$farmerId;
        $categoryId      = (int)$data['category_id'];
        $productName     = $data['product_name'];
        $description     = $data['description'] ?? null;
        $unitPrice       = (float)$data['unit_price'];
        $availQty        = (float)($data['available_quantity'] ?? 0.0);
        $unitLabel       = $data['unit_label'] ?? 'kg';
        $listingType     = $data['listing_type'] ?? 'AVAILABLE_NOW';
        $growingMethod   = $data['growing_method'] ?? 'CONVENTIONAL';
        $declaredGrade   = $data['declared_grade'] ?? 'A';
        $shelfLifeRefId  = !empty($data['shelf_life_reference_id']) ? (int)$data['shelf_life_reference_id'] : null;
        $harvestDate     = !empty($data['harvest_date']) ? $data['harvest_date'] : null;
        $bestBeforeDate  = !empty($data['best_before_date']) ? $data['best_before_date'] : null;
        $storageGuidance = $data['storage_guidance'] ?? null;
        $listingStatus   = $data['listing_status'] ?? 'ACTIVE';

        $stmt->bind_param(
            "issddssssissssii",
            $categoryId, $productName, $description, $unitPrice,
            $availQty, $unitLabel, $listingType, $growingMethod,
            $declaredGrade, $shelfLifeRefId, $harvestDate,
            $bestBeforeDate, $storageGuidance, $listingStatus,
            $pId, $fId
        );

        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}
