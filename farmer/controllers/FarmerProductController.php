<?php
/**
 * Farmer Product Controller
 * Manages Farmer Product Add / Edit forms and Scientific Shelf-Life Reference integration
 */

require_once __DIR__ . '/../models/FarmerProductModel.php';
require_once __DIR__ . '/../../includes/functions.php';

class FarmerProductController {
    private $model;

    public function __construct() {
        $this->model = new FarmerProductModel();
    }

    /**
     * Get Farmer ID from Session (Fallback to demo farmer ID 3 if session unavailable)
     */
    private function getFarmerId() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && strtoupper($_SESSION['role']) === 'FARMER') {
            return (int)$_SESSION['user_id'];
        }
        return 3; // Demo Farmer ID (Sunil Perera)
    }

    /**
     * Handle Farmer POST actions
     */
    public function handleAction() {
        $action = $_GET['action'] ?? null;
        $farmerId = $this->getFarmerId();

        if ($action === 'farmer_create_product') {
            $this->createProduct($farmerId);
        } elseif ($action === 'farmer_update_product') {
            $this->updateProduct($farmerId);
        }
    }

    /**
     * Process Create Product POST form
     */
    private function createProduct($farmerId) {
        $productName = sanitize($_POST['product_name'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 1);
        $unitPrice   = (float)($_POST['unit_price'] ?? 0.0);
        $quantity    = (float)($_POST['available_quantity'] ?? 0.0);
        $unitLabel   = sanitize($_POST['unit_label'] ?? 'kg');
        $listingType = sanitize($_POST['listing_type'] ?? 'AVAILABLE_NOW');
        $grade       = sanitize($_POST['declared_grade'] ?? 'A');
        $method      = sanitize($_POST['growing_method'] ?? 'CONVENTIONAL');
        $desc        = sanitize($_POST['description'] ?? '');

        // Shelf-Life Reference ID (NULL if 'No Reference Available / Not Applicable')
        $refIdInput  = $_POST['shelf_life_reference_id'] ?? '';
        $shelfLifeRefId = (!empty($refIdInput) && is_numeric($refIdInput) && (int)$refIdInput > 0) ? (int)$refIdInput : null;

        // Farmer Batch Information (Kept strictly independent)
        $harvestDate    = sanitize($_POST['harvest_date'] ?? '');
        $bestBeforeDate = sanitize($_POST['best_before_date'] ?? '');
        $storageGuide   = sanitize($_POST['storage_guidance'] ?? '');

        if (empty($productName) || $unitPrice <= 0) {
            header("Location: index.php?page=farmer_add_product&error=" . urlencode("Product title and valid price are required."));
            exit();
        }

        // Validate shelf-life reference if selected
        if ($shelfLifeRefId !== null) {
            $ref = $this->model->getShelfLifeReferenceById($shelfLifeRefId);
            if (!$ref) {
                $shelfLifeRefId = null; // Fallback to null if invalid ID passed
            }
        }

        $data = [
            'farmer_id'               => $farmerId,
            'category_id'             => $categoryId,
            'product_name'            => $productName,
            'description'             => $desc,
            'unit_price'              => $unitPrice,
            'available_quantity'      => $quantity,
            'unit_label'              => $unitLabel,
            'listing_type'            => $listingType,
            'growing_method'          => $method,
            'declared_grade'          => $grade,
            'shelf_life_reference_id' => $shelfLifeRefId,
            'harvest_date'            => !empty($harvestDate) ? $harvestDate : null,
            'best_before_date'        => !empty($bestBeforeDate) ? $bestBeforeDate : null,
            'storage_guidance'        => !empty($storageGuide) ? $storageGuide : null,
            'listing_status'          => 'ACTIVE'
        ];

        $productId = $this->model->createProduct($data);

        header("Location: index.php?page=farmer_products&success=" . urlencode("Product '{$productName}' listed successfully!"));
        exit();
    }

    /**
     * Process Update Product POST form
     */
    private function updateProduct($farmerId) {
        $productId   = (int)($_POST['product_id'] ?? 0);
        $productName = sanitize($_POST['product_name'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 1);
        $unitPrice   = (float)($_POST['unit_price'] ?? 0.0);
        $quantity    = (float)($_POST['available_quantity'] ?? 0.0);
        $unitLabel   = sanitize($_POST['unit_label'] ?? 'kg');
        $listingType = sanitize($_POST['listing_type'] ?? 'AVAILABLE_NOW');
        $grade       = sanitize($_POST['declared_grade'] ?? 'A');
        $method      = sanitize($_POST['growing_method'] ?? 'CONVENTIONAL');
        $desc        = sanitize($_POST['description'] ?? '');

        // Shelf-Life Reference ID
        $refIdInput  = $_POST['shelf_life_reference_id'] ?? '';
        $shelfLifeRefId = (!empty($refIdInput) && is_numeric($refIdInput) && (int)$refIdInput > 0) ? (int)$refIdInput : null;

        // Farmer Batch Information (Preserve farmer-provided Best Before Date without overwrite)
        $harvestDate    = sanitize($_POST['harvest_date'] ?? '');
        $bestBeforeDate = sanitize($_POST['best_before_date'] ?? '');
        $storageGuide   = sanitize($_POST['storage_guidance'] ?? '');
        $status         = sanitize($_POST['listing_status'] ?? 'ACTIVE');

        if ($productId <= 0 || empty($productName) || $unitPrice <= 0) {
            header("Location: index.php?page=farmer_edit_product&product_id={$productId}&error=" . urlencode("Invalid product data."));
            exit();
        }

        // Validate shelf-life reference if selected
        if ($shelfLifeRefId !== null) {
            $ref = $this->model->getShelfLifeReferenceById($shelfLifeRefId);
            if (!$ref) {
                $shelfLifeRefId = null;
            }
        }

        $data = [
            'category_id'             => $categoryId,
            'product_name'            => $productName,
            'description'             => $desc,
            'unit_price'              => $unitPrice,
            'available_quantity'      => $quantity,
            'unit_label'              => $unitLabel,
            'listing_type'            => $listingType,
            'growing_method'          => $method,
            'declared_grade'          => $grade,
            'shelf_life_reference_id' => $shelfLifeRefId,
            'harvest_date'            => !empty($harvestDate) ? $harvestDate : null,
            'best_before_date'        => !empty($bestBeforeDate) ? $bestBeforeDate : null,
            'storage_guidance'        => !empty($storageGuide) ? $storageGuide : null,
            'listing_status'          => $status
        ];

        $this->model->updateProduct($productId, $farmerId, $data);

        header("Location: index.php?page=farmer_products&success=" . urlencode("Product '{$productName}' updated successfully!"));
        exit();
    }
}
