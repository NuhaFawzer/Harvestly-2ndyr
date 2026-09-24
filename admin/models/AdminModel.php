<?php
/**
 * AdminModel - Handles all Database Operations for the Harvestly Administrator Module
 * Compatible with harvestly_database_with_distances.sql schema using MySQLi.
 */

require_once __DIR__ . '/../../config/database.php';

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = getDBConnection();
        $this->ensureCategoryDescriptionColumn();
    }

    private function ensureCategoryDescriptionColumn() {
        $result = $this->db->query("SHOW COLUMNS FROM product_categories LIKE 'description'");
        if ($result && $result->num_rows === 0) {
            $this->db->query("ALTER TABLE product_categories ADD COLUMN description VARCHAR(500) NULL AFTER category_name");
        }
    }

    /* ------------------------------------------------------------------
     * 1. OVERVIEW & KPI METRICS
     * ------------------------------------------------------------------ */
    public function getOverviewKPIs() {
        $kpis = [];

        try {
            // Total Farmers
            $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE role = 'FARMER' AND account_status IN ('ACTIVE', 'APPROVED', 'approved')");
            $kpis['total_farmers'] = $res ? (int)$res->fetch_assoc()['c'] : 0;

            // Total Buyers
            $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE role = 'BUYER' AND account_status IN ('ACTIVE', 'active')");
            $kpis['total_buyers'] = $res ? (int)$res->fetch_assoc()['c'] : 0;

            // Total Courier Partners
            $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE role = 'COURIER_PARTNER' AND account_status IN ('ACTIVE', 'APPROVED', 'approved')");
            $kpis['total_couriers'] = $res ? (int)$res->fetch_assoc()['c'] : 0;

            // Total Orders
            $res = $this->db->query("SELECT COUNT(*) as c FROM orders");
            $kpis['total_orders'] = $res ? (int)$res->fetch_assoc()['c'] : 0;

            // Active complaints
            $res = $this->db->query("SELECT COUNT(*) as c FROM complaints WHERE complaint_status IN ('OPEN', 'UNDER_REVIEW', 'investigating', 'open')");
            $kpis['active_complaints'] = $res ? (int)$res->fetch_assoc()['c'] : 0;

            // Pending Verifications
            $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE account_status IN ('PENDING', 'pending')");
            $kpis['pending_verifications'] = $res ? (int)$res->fetch_assoc()['c'] : 0;

            // Weekly Settlement Total
            $res = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as s FROM settlements WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
            $kpis['weekly_settlements'] = $res ? (float)$res->fetch_assoc()['s'] : 0.00;
        } catch (Exception $e) {
            $kpis = [
                'total_farmers' => 0, 'total_buyers' => 0, 'total_couriers' => 0,
                'total_orders' => 0, 'active_complaints' => 0, 'pending_verifications' => 0,
                'weekly_settlements' => 0.00
            ];
        }

        return $kpis;
    }

    public function getRecentActivityLog() {
        $activities = [];
        try {
            $res1 = $this->db->query("SELECT 'New Order' as type, CONCAT('Order #ORD-2026-', order_id, ' placed') as detail, created_at FROM orders ORDER BY created_at DESC LIMIT 5");
            if ($res1) {
                $activities = array_merge($activities, $res1->fetch_all(MYSQLI_ASSOC));
            }

            $res2 = $this->db->query("SELECT 'User Registration' as type, CONCAT(full_name, ' (', role, ') registered') as detail, created_at FROM users WHERE account_status='PENDING' ORDER BY created_at DESC LIMIT 5");
            if ($res2) {
                $activities = array_merge($activities, $res2->fetch_all(MYSQLI_ASSOC));
            }

            usort($activities, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        } catch (Exception $e) {}

        return array_slice($activities, 0, 6);
    }

    /* ------------------------------------------------------------------
     * 2. USER MANAGEMENT
     * ------------------------------------------------------------------ */
    public function getAllUsers($roleFilter = 'all', $statusFilter = 'all') {
        $sql = "
            SELECT u.user_id as id, u.full_name as name, u.email, u.phone, u.role, u.account_status as status, u.created_at,
                   d.district_name as district, d.province_name as province,
                   vd.stored_file_path as document_path
            FROM users u
            LEFT JOIN buyer_profiles bp ON u.user_id = bp.buyer_id
            LEFT JOIN farmer_profiles fp ON u.user_id = fp.farmer_id
            LEFT JOIN courier_partner_profiles cp ON u.user_id = cp.courier_partner_id
            LEFT JOIN districts d ON (fp.district_id = d.district_id OR bp.default_district_id = d.district_id OR cp.office_district_id = d.district_id)
            LEFT JOIN verification_documents vd ON u.user_id = vd.user_id
            WHERE 1=1
        ";

        if ($roleFilter !== 'all') {
            $roleMap = ['buyer' => 'BUYER', 'farmer' => 'FARMER', 'courier' => 'COURIER_PARTNER', 'admin' => 'ADMIN'];
            $mapped = $roleMap[strtolower($roleFilter)] ?? strtoupper($roleFilter);
            $mappedClean = $this->db->real_escape_string($mapped);
            $sql .= " AND u.role = '$mappedClean'";
        }

        if ($statusFilter !== 'all') {
            $statusClean = $this->db->real_escape_string(strtoupper($statusFilter));
            $sql .= " AND u.account_status = '$statusClean'";
        }

        $sql .= " GROUP BY u.user_id ORDER BY u.created_at DESC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function createUser($role, $data) {
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $roleMap = ['buyer' => 'BUYER', 'farmer' => 'FARMER', 'courier' => 'COURIER_PARTNER', 'admin' => 'ADMIN'];
        $roleEnum = $roleMap[strtolower($role)] ?? 'BUYER';
        $status = strtoupper($data['status'] ?? 'ACTIVE');
        $phone = $data['phone'] ?? '';

        $stmt = $this->db->prepare("
            INSERT INTO users (role, full_name, email, password_hash, phone, account_status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) return false;

        $stmt->bind_param("ssssss", $roleEnum, $data['name'], $data['email'], $passwordHash, $phone, $status);
        $stmt->execute();
        $insertId = $this->db->insert_id;
        $stmt->close();
        return $insertId;
    }

    public function updateUserDetails($role, $id, $data) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET full_name = ?, email = ?, phone = ?, account_status = ?
            WHERE user_id = ?
        ");
        if (!$stmt) return false;
        $status = strtoupper($data['status']);
        $stmt->bind_param("ssssi", $data['name'], $data['email'], $data['phone'], $status, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function deleteUser($role, $id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function updateUserStatus($userType, $userId, $status) {
        $stmt = $this->db->prepare("UPDATE users SET account_status = ? WHERE user_id = ?");
        if (!$stmt) return false;
        $statusUpper = strtoupper($status);
        $stmt->bind_param("si", $statusUpper, $userId);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /* ------------------------------------------------------------------
     * 3. FARMER & COURIER APPROVALS
     * ------------------------------------------------------------------ */
    public function getPendingFarmers() {
        $result = $this->db->query("
            SELECT u.user_id as id, u.full_name, u.email, u.phone, u.account_status as status, u.created_at,
                   fp.farm_name, d.district_name as district, vd.stored_file_path as id_document_path, vd.original_file_name
            FROM users u
            JOIN farmer_profiles fp ON u.user_id = fp.farmer_id
            LEFT JOIN districts d ON fp.district_id = d.district_id
            LEFT JOIN verification_documents vd ON u.user_id = vd.user_id
            WHERE u.role = 'FARMER' AND (u.account_status IN ('PENDING', 'REJECTED') OR fp.verification_status = 'PENDING')
            GROUP BY u.user_id
            ORDER BY u.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getPendingCouriers() {
        $result = $this->db->query("
            SELECT u.user_id as id, u.full_name as company_name, cp.contact_person_name as contact_person, u.email, u.phone, u.account_status as status, u.created_at,
                   d.district_name as district, vd.stored_file_path as verification_document_path, vd.original_file_name
            FROM users u
            JOIN courier_partner_profiles cp ON u.user_id = cp.courier_partner_id
            LEFT JOIN districts d ON cp.office_district_id = d.district_id
            LEFT JOIN verification_documents vd ON u.user_id = vd.user_id
            WHERE u.role = 'COURIER_PARTNER' AND (u.account_status IN ('PENDING', 'REJECTED') OR cp.verification_status = 'PENDING')
            GROUP BY u.user_id
            ORDER BY u.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateFarmerVerification($id, $status, $reason = null) {
        $userStatus = ($status === 'approved' || $status === 'APPROVED') ? 'ACTIVE' : 'REJECTED';
        $statusUpper = strtoupper($status);

        $stmtU = $this->db->prepare("UPDATE users SET account_status = ? WHERE user_id = ?");
        if ($stmtU) {
            $stmtU->bind_param("si", $userStatus, $id);
            $stmtU->execute();
            $stmtU->close();
        }

        $stmtP = $this->db->prepare("UPDATE farmer_profiles SET verification_status = ? WHERE farmer_id = ?");
        if ($stmtP) {
            $stmtP->bind_param("si", $statusUpper, $id);
            $stmtP->execute();
            $stmtP->close();
        }

        $stmtV = $this->db->prepare("UPDATE verification_documents SET status = ?, rejection_reason = ? WHERE user_id = ?");
        if ($stmtV) {
            $stmtV->bind_param("ssi", $statusUpper, $reason, $id);
            $res = $stmtV->execute();
            $stmtV->close();
            return $res;
        }
        return false;
    }

    public function updateCourierVerification($id, $status, $reason = null) {
        $userStatus = ($status === 'approved' || $status === 'APPROVED') ? 'ACTIVE' : 'REJECTED';
        $statusUpper = strtoupper($status);

        $stmtU = $this->db->prepare("UPDATE users SET account_status = ? WHERE user_id = ?");
        if ($stmtU) {
            $stmtU->bind_param("si", $userStatus, $id);
            $stmtU->execute();
            $stmtU->close();
        }

        $stmtC = $this->db->prepare("UPDATE courier_partner_profiles SET verification_status = ? WHERE courier_partner_id = ?");
        if ($stmtC) {
            $stmtC->bind_param("si", $statusUpper, $id);
            $stmtC->execute();
            $stmtC->close();
        }

        $stmtV = $this->db->prepare("UPDATE verification_documents SET status = ?, rejection_reason = ? WHERE user_id = ?");
        if ($stmtV) {
            $stmtV->bind_param("ssi", $statusUpper, $reason, $id);
            $res = $stmtV->execute();
            $stmtV->close();
            return $res;
        }
        return false;
    }

    /* ------------------------------------------------------------------
     * 4. PRODUCT MANAGEMENT & LISTINGS MONITOR
     * ------------------------------------------------------------------ */
    public function getAllProducts() {
        $result = $this->db->query("
            SELECT p.product_id as id, p.product_name as title, p.unit_price as price_per_unit, p.unit_label as unit_type,
                   p.declared_grade as grade, p.listing_status as status, p.listing_type,
                     c.category_name as category, u.full_name as farmer_name, d.district_name as district
            FROM products p
            JOIN product_categories c ON p.category_id = c.category_id
            JOIN users u ON p.farmer_id = u.user_id
                 LEFT JOIN farmer_profiles fp ON p.farmer_id = fp.farmer_id
                 LEFT JOIN districts d ON fp.district_id = d.district_id
            ORDER BY p.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateProductStatus($productId, $status) {
        $stmt = $this->db->prepare("UPDATE products SET listing_status = ? WHERE product_id = ?");
        if (!$stmt) return false;
        $statusUpper = strtoupper($status);
        $stmt->bind_param("si", $statusUpper, $productId);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /* ------------------------------------------------------------------
     * 5. PRODUCT CATEGORIES (ADMIN CRUD FEATURE)
     * ------------------------------------------------------------------ */
    public function getAllCategories($search = '', $statusFilter = 'all') {
        $sql = "SELECT category_id as id, category_name, description, is_active as status, created_at FROM product_categories WHERE 1=1";
        $types = '';
        $params = [];
        if ($search !== '') {
            $sql .= " AND category_name LIKE ?";
            $types .= 's';
            $params[] = '%' . $search . '%';
        }
        if ($statusFilter !== 'all') {
            $sql .= " AND is_active = ?";
            $types .= 'i';
            $params[] = ($statusFilter === 'active' || $statusFilter === '1') ? 1 : 0;
        }
        $sql .= " ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        foreach ($rows as &$row) {
            $row['status'] = ($row['status'] == 1) ? 'active' : 'inactive';
            $row['description'] = $row['description'] ?? '';
        }

        return $rows;
    }

    public function getCategoryById($id) {
        $stmt = $this->db->prepare("SELECT category_id as id, category_name, description, is_active as status FROM product_categories WHERE category_id = ?");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            $row['status'] = ($row['status'] == 1) ? 'active' : 'inactive';
            $row['description'] = $row['description'] ?? '';
        }
        return $row;
    }

    public function createCategory($name, $description = '', $status = 'active') {
        $activeVal = ($status === 'active' || $status === '1') ? 1 : 0;
        $stmt = $this->db->prepare("INSERT INTO product_categories (category_name, description, is_active) VALUES (?, ?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("ssi", $name, $description, $activeVal);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function updateCategory($id, $name, $description = '', $status = 'active') {
        $activeVal = ($status === 'active' || $status === '1') ? 1 : 0;
        $stmt = $this->db->prepare("UPDATE product_categories SET category_name = ?, description = ?, is_active = ? WHERE category_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("ssii", $name, $description, $activeVal, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM product_categories WHERE category_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function deactivateCategory($id) {
        $stmt = $this->db->prepare("UPDATE product_categories SET is_active = 0 WHERE category_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /* ------------------------------------------------------------------
     * 6. ORDERS & MANUAL OVERRIDE
     * ------------------------------------------------------------------ */
    public function getAllOrders() {
        $result = $this->db->query("
            SELECT o.order_id as id, CONCAT('ORD-2026-', o.order_id) as order_number, o.grand_total as total_amount,
                   o.delivery_fee, o.order_status as status, o.created_at,
                   b.full_name as buyer_name, f.full_name as farmer_name, cp.full_name as courier_name,
                   pm.payment_status
            FROM orders o
            JOIN users b ON o.buyer_id = b.user_id
            JOIN users f ON o.farmer_id = f.user_id
            LEFT JOIN deliveries d ON o.order_id = d.order_id
            LEFT JOIN users cp ON d.courier_partner_id = cp.user_id
            LEFT JOIN payments pm ON o.order_id = pm.order_id
            ORDER BY o.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function overrideDeliveryAssignment($orderId, $courierId) {
        $stmtD = $this->db->prepare("
            INSERT INTO deliveries (order_id, courier_partner_id, delivery_status, assigned_at)
            VALUES (?, ?, 'ASSIGNED', NOW())
            ON DUPLICATE KEY UPDATE courier_partner_id = ?, delivery_status = 'ASSIGNED', assigned_at = NOW()
        ");
        if ($stmtD) {
            $stmtD->bind_param("iii", $orderId, $courierId, $courierId);
            $stmtD->execute();
            $stmtD->close();
        }

        $stmtO = $this->db->prepare("UPDATE orders SET order_status = 'ASSIGNED' WHERE order_id = ?");
        if ($stmtO) {
            $stmtO->bind_param("i", $orderId);
            $res = $stmtO->execute();
            $stmtO->close();
            return $res;
        }
        return false;
    }

    /* ------------------------------------------------------------------
     * 7. DELIVERIES MANAGEMENT
     * ------------------------------------------------------------------ */
    public function getAllDeliveries() {
        $result = $this->db->query("
            SELECT d.delivery_id as id, o.order_id, CONCAT('ORD-2026-', o.order_id) as order_number,
                   b.full_name as buyer_name, dist_dest.district_name as destination_district,
                   f.full_name as farmer_name, dist_orig.district_name as origin_district,
                   cp.full_name as courier_name, o.delivery_fee, d.delivery_status, d.created_at as order_date
            FROM deliveries d
            JOIN orders o ON d.order_id = o.order_id
            JOIN users b ON o.buyer_id = b.user_id
            JOIN users f ON o.farmer_id = f.user_id
            LEFT JOIN farmer_profiles fp ON f.user_id = fp.farmer_id
            LEFT JOIN districts dist_orig ON fp.district_id = dist_orig.district_id
            LEFT JOIN districts dist_dest ON o.destination_district_id = dist_dest.district_id
            LEFT JOIN users cp ON d.courier_partner_id = cp.user_id
            ORDER BY d.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* ------------------------------------------------------------------
     * 8. PENDING ASSIGNMENTS
     * ------------------------------------------------------------------ */
    public function getPendingAssignments() {
        $result = $this->db->query("
            SELECT o.order_id as id, CONCAT('ORD-2026-', o.order_id) as order_number,
                   b.full_name as buyer_name, dist_dest.district_name as destination_district,
                   f.full_name as farmer_name, dist_orig.district_name as origin_district,
                   o.order_status as reason
            FROM orders o
            JOIN users b ON o.buyer_id = b.user_id
            JOIN users f ON o.farmer_id = f.user_id
            LEFT JOIN farmer_profiles fp ON f.user_id = fp.farmer_id
            LEFT JOIN districts dist_orig ON fp.district_id = dist_orig.district_id
            LEFT JOIN districts dist_dest ON o.destination_district_id = dist_dest.district_id
            LEFT JOIN deliveries d ON o.order_id = d.order_id
            WHERE d.courier_partner_id IS NULL OR o.order_status = 'PENDING_ASSIGNMENT'
            GROUP BY o.order_id
            ORDER BY o.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* ------------------------------------------------------------------
     * 9. DISTRICT DISTANCE REFERENCE DATA
     * ------------------------------------------------------------------ */
    public function getAllDistrictDistances($fromDistrict = '', $toDistrict = '') {
        $sql = "
            SELECT dd.distance_id as id, d1.district_name as from_district, d2.district_name as to_district, dd.distance_km
            FROM district_distances dd
            JOIN districts d1 ON dd.from_district_id = d1.district_id
            JOIN districts d2 ON dd.to_district_id = d2.district_id
            WHERE 1 = 1";
        $params = [];
        $types = '';

        if ($fromDistrict !== '') {
            $sql .= " AND d1.district_name LIKE ?";
            $params[] = '%' . $fromDistrict . '%';
            $types .= 's';
        }
        if ($toDistrict !== '') {
            $sql .= " AND d2.district_name LIKE ?";
            $params[] = '%' . $toDistrict . '%';
            $types .= 's';
        }

        $sql .= " ORDER BY d1.district_name ASC, d2.district_name ASC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $distances = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $distances;
    }

    public function createDistrictDistance($from, $to, $km) {
        $fId = 0; $tId = 0;
        $stmtF = $this->db->prepare("SELECT district_id FROM districts WHERE district_name = ?");
        if ($stmtF) {
            $stmtF->bind_param("s", $from);
            $stmtF->execute();
            $res = $stmtF->get_result();
            if ($row = $res->fetch_assoc()) $fId = (int)$row['district_id'];
            $stmtF->close();
        }

        $stmtT = $this->db->prepare("SELECT district_id FROM districts WHERE district_name = ?");
        if ($stmtT) {
            $stmtT->bind_param("s", $to);
            $stmtT->execute();
            $res = $stmtT->get_result();
            if ($row = $res->fetch_assoc()) $tId = (int)$row['district_id'];
            $stmtT->close();
        }

        if ($fId && $tId) {
            $stmt = $this->db->prepare("INSERT INTO district_distances (from_district_id, to_district_id, distance_km) VALUES (?, ?, ?)");
            if ($stmt) {
                $kmVal = (float)$km;
                $stmt->bind_param("iid", $fId, $tId, $kmVal);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            }
        }
        return false;
    }

    public function updateDistrictDistance($id, $from, $to, $km) {
        $stmt = $this->db->prepare("UPDATE district_distances SET distance_km = ? WHERE distance_id = ?");
        if (!$stmt) return false;
        $kmVal = (float)$km;
        $stmt->bind_param("di", $kmVal, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function deleteDistrictDistance($id) {
        $stmt = $this->db->prepare("DELETE FROM district_distances WHERE distance_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /* ------------------------------------------------------------------
    * 10. COMPLAINTS & ISSUES
     * ------------------------------------------------------------------ */
    public function getAllComplaints() {
        $result = $this->db->query("
                 SELECT c.complaint_id as id, CONCAT('ORD-2026-', o.order_id) as order_number, o.order_id,
                     u.full_name as complainant_name, c.complainant_role as user_role, c.category,
                     c.complaint_status as status, c.description, c.evidence_path, c.created_at
            FROM complaints c
            JOIN orders o ON c.order_id = o.order_id
            JOIN users u ON c.complainant_user_id = u.user_id
            ORDER BY c.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function resolveComplaint($complaintId, $status, $resolutionNotes) {
        $stmt = $this->db->prepare("UPDATE complaints SET complaint_status = ?, admin_response = ? WHERE complaint_id = ?");
        if (!$stmt) return false;
        $statusUpper = strtoupper($status);
        $stmt->bind_param("ssi", $statusUpper, $resolutionNotes, $complaintId);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /* ------------------------------------------------------------------
     * 11. PAYMENTS
     * ------------------------------------------------------------------ */
    public function getAllPayments() {
        $result = $this->db->query("
            SELECT p.payment_id as id, CONCAT('ORD-2026-', o.order_id) as order_number, o.order_id,
                   b.full_name as buyer_name, p.amount, p.provider_reference, p.payment_status, p.created_at as paid_at
            FROM payments p
            JOIN orders o ON p.order_id = o.order_id
            JOIN users b ON o.buyer_id = b.user_id
            ORDER BY p.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* ------------------------------------------------------------------
     * 12. SETTLEMENTS & EARNINGS
     * ------------------------------------------------------------------ */
    public function getSettlements() {
        $result = $this->db->query("
            SELECT CONCAT('SET-', LPAD(s.settlement_id, 4, '0'), '-', LPAD(si.settlement_item_id, 3, '0')) as reference_no,
                   LOWER(e.beneficiary_type) as user_type,
                   e.beneficiary_user_id as user_id,
                   e.amount,
                   LOWER(s.settlement_status) as status,
                   s.created_at as settlement_date
            FROM settlement_items si
            JOIN settlements s ON si.settlement_id = s.settlement_id
            JOIN earnings e ON si.earning_id = e.earning_id
            ORDER BY s.created_at DESC, si.settlement_item_id DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* ------------------------------------------------------------------
     * 13. REPORTS & SETTINGS & NOTIFICATIONS
     * ------------------------------------------------------------------ */
    public function generateReportData($reportType, $startDate, $endDate) {
        if ($reportType === 'settlements') {
            $sql = "
                SELECT CONCAT('SET-', LPAD(s.settlement_id, 4, '0'), '-', LPAD(si.settlement_item_id, 3, '0')) as reference_no,
                       LOWER(e.beneficiary_type) as user_type,
                       e.beneficiary_user_id as user_id,
                       e.amount,
                       LOWER(s.settlement_status) as status,
                       s.created_at as settlement_date
                FROM settlement_items si
                JOIN settlements s ON si.settlement_id = s.settlement_id
                JOIN earnings e ON si.earning_id = e.earning_id
                WHERE DATE(s.created_at) BETWEEN ? AND ?
                ORDER BY s.created_at DESC, si.settlement_item_id DESC";
        } elseif ($reportType === 'farmers') {
            $sql = "
                SELECT full_name, email, phone, LOWER(account_status) as status, created_at
                FROM users
                WHERE role = 'FARMER' AND DATE(created_at) BETWEEN ? AND ?
                ORDER BY created_at DESC";
        } else {
            $sql = "
                SELECT order_id as order_number, grand_total as total_amount, delivery_fee,
                       LOWER(order_status) as status, created_at
                FROM orders
                WHERE DATE(created_at) BETWEEN ? AND ?
                ORDER BY created_at DESC";
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function getNotificationsLog() {
        $result = $this->db->query("
            SELECT n.notification_id as id,
                   CASE
                       WHEN n.notification_type LIKE '%FARMER%' THEN 'farmers'
                       WHEN n.notification_type LIKE '%COURIER%' THEN 'couriers'
                       WHEN u.role = 'BUYER' THEN 'buyers'
                       ELSE 'all'
                   END as recipient_scope,
                   n.title as subject, n.message, n.created_at as sent_at
            FROM notifications n
            LEFT JOIN users u ON n.user_id = u.user_id
            ORDER BY n.created_at DESC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function createNotification($scope, $recipientId, $subject, $message) {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, notification_type, title, message) VALUES (1, 'ADMIN_BROADCAST', ?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("ss", $subject, $message);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function getPlatformSettings() {
        $result = $this->db->query("SELECT setting_key, setting_value FROM platform_settings");
        $settings = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $settings;
    }

    public function updatePlatformSettings($settingsArray) {
        $stmt = $this->db->prepare("INSERT INTO platform_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        if (!$stmt) return false;
        foreach ($settingsArray as $key => $val) {
            $stmt->bind_param("sss", $key, $val, $val);
            $stmt->execute();
        }
        $stmt->close();
        return true;
    }

    public function getAdminProfile($adminId = 1) {
        $stmt = $this->db->prepare("SELECT user_id as id, full_name, email, role, created_at FROM users WHERE user_id = ? AND role = 'ADMIN'");
        if ($stmt) {
            $stmt->bind_param("i", $adminId);
            $stmt->execute();
            $res = $stmt->get_result();
            $profile = $res ? $res->fetch_assoc() : null;
            $stmt->close();
            if ($profile) return $profile;
        }
        return ['id' => 1, 'full_name' => 'System Administrator', 'email' => 'admin@harvestly.lk', 'role' => 'Administrator', 'created_at' => date('Y-m-d H:i:s')];
    }

    public function updateAdminProfile($adminId, $fullName, $email, $newPassword = null) {
        if ($newPassword) {
            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE users SET full_name = ?, email = ?, password_hash = ? WHERE user_id = ?");
            if (!$stmt) return false;
            $stmt->bind_param("sssi", $fullName, $email, $hash, $adminId);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } else {
            $stmt = $this->db->prepare("UPDATE users SET full_name = ?, email = ? WHERE user_id = ?");
            if (!$stmt) return false;
            $stmt->bind_param("ssi", $fullName, $email, $adminId);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
    }
}
